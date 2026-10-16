<?php

namespace App\Support;

use App\Http\Middleware\RestoreAuthFromCookie;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

/**
 * Facade dokumentasi untuk cookie login persistent.
 *
 * Nama cookie default: psb_baitul_quran (lihat AUTH_COOKIE di .env).
 * Implementasi: RestoreAuthFromCookie / RestorePersistentLogin.
 * Cookie di-set saat login & saat user terautentikasi; dihapus hanya saat logout.
 */
class PersistentLogin
{
    public static function cookieName(): string
    {
        return RestoreAuthFromCookie::cookieName();
    }

    public static function staffCookieName(): string
    {
        return RestoreAuthFromCookie::staffCookieName();
    }

    public static function makeCookie(int $userId, ?string $role = null): SymfonyCookie
    {
        return RestoreAuthFromCookie::makeCookie($userId, $role);
    }

    public static function makeStaffCookie(int $userId, ?string $role = null): SymfonyCookie
    {
        return RestoreAuthFromCookie::makeStaffCookie($userId, $role);
    }

    public static function forgetCookie(): SymfonyCookie
    {
        return RestoreAuthFromCookie::forgetCookie();
    }

    public static function forgetStaffCookie(): SymfonyCookie
    {
        return RestoreAuthFromCookie::forgetStaffCookie();
    }

    public static function attempt(Request $request, ?Request $context = null, bool $force = false): bool
    {
        return RestoreAuthFromCookie::attempt($request, $context, $force);
    }

    public static function attemptForPath(Request $request, ?string $path): bool
    {
        return RestoreAuthFromCookie::attemptForPath($request, $path);
    }
}
