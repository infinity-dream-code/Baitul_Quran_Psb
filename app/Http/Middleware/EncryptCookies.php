<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Cookie auth cadangan di-sign sendiri — jangan dienkripsi Laravel
     * supaya tidak “hilang” saat decrypt gagal.
     */
    public function isDisabled($name)
    {
        if (parent::isDisabled($name)) {
            return true;
        }

        $auth = RestoreAuthFromCookie::cookieName();

        return $name === $auth
            || $name === RestoreAuthFromCookie::staffCookieName()
            || $name === 'psbku_uid'
            || $name === 'psbku_uid_staff'
            || $name === 'al_istiqomah_psb_uid'
            || $name === 'al_istiqomah_psb_uid_staff'
            || $name === 'psb_baitul_quran'
            || $name === 'psb_baitul_quran_staff';
    }
}
