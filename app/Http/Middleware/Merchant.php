<?php

namespace App\Http\Middleware;

use App\Http\Resources\BasicResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Merchant
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return (Auth::guard('api')->user()->type == 'merchant')
            ? $next($request)
            : new BasicResource(false, __('messages.unauthorized'), 'message');
    }
}
