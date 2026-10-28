<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Di production semua alamat diarahkan ke satu bentuk resmi: https dan tanpa "www.".
 * Google tidak lagi melihat http/www sebagai website kembar, login admin selalu terenkripsi,
 * dan www.literacy… tetap membuka website Literacy (route subdomain tidak mengenal "www.").
 */
class CanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->isProduction()) {
            return $next($request);
        }

        $host = $request->getHost();
        $bare = preg_replace('/^www\./i', '', $host);

        if ($request->isSecure() && $bare === $host) {
            return $next($request);
        }

        return redirect()->to('https://'.$bare.$request->getRequestUri(), 301);
    }
}
