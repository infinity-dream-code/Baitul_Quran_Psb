<?php

namespace App\Http\Middleware;

/**
 * Alias stabil untuk RestoreAuthFromCookie.
 * Jangan dihapus tanpa pengganti yang setara (cookie persistent + restore Auth::login).
 */
class RestorePersistentLogin extends RestoreAuthFromCookie
{
}
