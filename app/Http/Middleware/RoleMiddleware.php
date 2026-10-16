<?php

namespace App\Http\Middleware;

use App\Support\AuthRedirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            RestoreAuthFromCookie::attempt($request);
        }

        if (!Auth::check() && $this->hasPersistentCookie($request)) {
            try {
                $request->session()->regenerate();
                $request->session()->regenerateToken();
            } catch (\Throwable $e) {
            }
            RestoreAuthFromCookie::attempt($request, null, true);
        }

        if (!Auth::check()) {
            Log::info('auth.role_redirect_login', [
                'path' => $request->path(),
                'need_role' => $role,
                'session_id' => $request->session()->getId(),
                'has_auth_cookie' => $request->cookies->has(RestoreAuthFromCookie::cookieName()),
                'has_staff_cookie' => $request->cookies->has(RestoreAuthFromCookie::staffCookieName()),
            ]);

            if ($request->expectsJson()
                || $request->ajax()
                || $request->header('X-Requested-With') === 'XMLHttpRequest'
            ) {
                return response()->json(['message' => 'Unauthenticated.', 'token' => csrf_token()], 401)
                    ->header('X-CSRF-TOKEN', csrf_token());
            }

            // Cookie ada → reload halaman yang sama, jangan lempar ke login
            if ($this->hasPersistentCookie($request) && $request->isMethodSafe()) {
                return response()->view('errors.soft-recover', [
                    'reloadUrl' => $request->fullUrl(),
                    'fallbackUrl' => '',
                ], 200);
            }

            return redirect()->guest(AuthRedirect::loginUrlFor($request));
        }

        if (Auth::user()->role !== $role) {
            // Masih ada cookie role yang diminta? Coba koreksi sekali lagi.
            RestoreAuthFromCookie::attempt($request, null, true);

            if (Auth::check() && Auth::user()->role === $role) {
                return $next($request);
            }

            Log::info('auth.role_mismatch_to_dashboard', [
                'path' => $request->path(),
                'need_role' => $role,
                'user_role' => Auth::user()->role,
                'user_id' => Auth::id(),
            ]);

            return redirect()->to(AuthRedirect::dashboardPath());
        }

        return $next($request);
    }

    private function hasPersistentCookie(Request $request): bool
    {
        return $request->cookies->has(RestoreAuthFromCookie::cookieName())
            || $request->cookies->has(RestoreAuthFromCookie::staffCookieName())
            || $request->cookies->has('al_istiqomah_psb_uid')
            || $request->cookies->has('psbku_uid');
    }
}
