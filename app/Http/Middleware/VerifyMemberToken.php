<?php

namespace App\Http\Middleware;

use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyMemberToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token');

        if (!$token) {
            return response()->json(['status' => false, 'message' => 'Token not provided'], 401);
        }

        $member = Member::where('token', $token)->first();

        if (!$member) {
            return response()->json(['status' => false, 'message' => 'Invalid token'], 401);
        }
        $request->attributes->set('member', $member);

        return $next($request);
    }
}
