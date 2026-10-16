<?php

namespace App\Http\Middleware;

use App\Support\AuthRedirect;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    public function handle($request, \Closure $next, ...$guards)
    {
        // Session file kadang kosong di tengah navigasi — coba pulihkan dulu.
        if (!Auth::check()) {
            RestoreAuthFromCookie::attempt($request);
        }

        // Cookie ada tapi session gagal dibaca: recreate session + restore, tetap di halaman ini.
        if (!Auth::check() && $this->hasPersistentCookie($request)) {
            $this->recreateSessionQuietly($request);
            RestoreAuthFromCookie::attempt($request, null, true);
        }

        if (Auth::check()) {
            return $next($request);
        }

        // Cookie masih ada tapi restore belum berhasil (transient): reload halaman yang sama — jangan ke login.
        if ($this->hasPersistentCookie($request)
            && $request->isMethodSafe()
            && !$this->isAjax($request)
        ) {
            Log::info('auth.soft_recover_instead_of_login', [
                'path' => $request->fullUrl(),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            ]);

            return response()->view('errors.soft-recover', [
                'reloadUrl' => $request->fullUrl(),
                'fallbackUrl' => '',
            ], 200);
        }

        return parent::handle($request, $next, ...$guards);
    }

    protected function unauthenticated($request, array $guards)
    {
        RestoreAuthFromCookie::attempt($request, null, true);
        if (Auth::check()) {
            return;
        }

        Log::info('auth.unauthenticated_redirect', [
            'path' => $request->fullUrl(),
            'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            'has_auth_cookie' => $request->cookies->has(RestoreAuthFromCookie::cookieName()),
            'has_staff_cookie' => $request->cookies->has(RestoreAuthFromCookie::staffCookieName()),
        ]);

        // AJAX/fetch: 401 supaya session-guard bisa keepalive + retry (bukan redirect login)
        $redirectTo = $this->isAjax($request) ? null : AuthRedirect::loginUrlFor($request);

        throw new AuthenticationException('Unauthenticated.', $guards, $redirectTo);
    }

    protected function redirectTo(Request $request): ?string
    {
        if ($this->isAjax($request)) {
            return null;
        }

        return AuthRedirect::loginUrlFor($request);
    }

    private function isAjax(Request $request): bool
    {
        return $request->expectsJson()
            || $request->ajax()
            || $request->header('X-Requested-With') === 'XMLHttpRequest';
    }

    private function hasPersistentCookie(Request $request): bool
    {
        return $request->cookies->has(RestoreAuthFromCookie::cookieName())
            || $request->cookies->has(RestoreAuthFromCookie::staffCookieName())
            || $request->cookies->has('al_istiqomah_psb_uid')
            || $request->cookies->has('psbku_uid');
    }

    private function recreateSessionQuietly(Request $request): void
    {
        if (!$request->hasSession()) {
            return;
        }

        try {
            $request->session()->regenerate();
            $request->session()->regenerateToken();
        } catch (\Throwable $e) {
            Log::warning('session.recreate_in_authenticate', [
                'path' => $request->path(),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
