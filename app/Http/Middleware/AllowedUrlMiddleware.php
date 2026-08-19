<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowedUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $allowedUrls = [
        'http://127.0.0.1:8000',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Get the Origin or Referer header
        $origin = $request->headers->get('origin') ?? $request->headers->get('referer');

        if ($origin && in_array(parse_url($origin, PHP_URL_HOST), $this->getAllowedHosts())) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    protected function getAllowedHosts()
    {
        return array_map(function ($url) {
            return parse_url($url, PHP_URL_HOST);
        }, $this->allowedUrls);
    }
}
