<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse\DataBuilder;
use Auth;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckUserApi
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $role
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next, $role): JsonResponse|Response
    {
        if (Auth::check() && Auth::user()->tokenCan($role)) {
            return $next($request);
        }

        return (new DataBuilder())->message('not-found')->status(404)->respond();
    }
}
