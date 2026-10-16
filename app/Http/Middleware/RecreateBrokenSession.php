<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Jika session file gagal dibaca / corrupt: buat ulang session di tempat.
 * Tidak logout dan tidak redirect ke login — restore auth dilakukan middleware berikutnya.
 */
class RecreateBrokenSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasSession()) {
            return $next($request);
        }

        try {
            $request->session()->get('_token');
        } catch (Throwable $e) {
            $this->recreate($request, $e);
        }

        return $next($request);
    }

    private function recreate(Request $request, Throwable $cause): void
    {
        Log::warning('session.recreate_after_read_failure', [
            'path' => $request->path(),
            'error' => $cause->getMessage(),
        ]);

        try {
            $request->session()->regenerate(true);
        } catch (Throwable $e) {
            try {
                $request->session()->migrate(true);
            } catch (Throwable $ignored) {
            }
        }

        try {
            if (!$request->session()->token()) {
                $request->session()->regenerateToken();
            }
        } catch (Throwable $ignored) {
        }
    }
}
