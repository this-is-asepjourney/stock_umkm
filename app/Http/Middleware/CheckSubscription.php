<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->company) {
            $company = $user->company;

            // If status is not active/trial, or expired
            if (!in_array($company->subscription_status, ['active', 'trial'])) {
                return redirect()->route('subscription.expired');
            }

            if ($company->subscription_ends_at && $company->subscription_ends_at->isPast()) {
                // Auto update status if expired?
                // $company->update(['subscription_status' => 'expired']);
                return redirect()->route('subscription.expired');
            }
        }

        return $next($request);
    }
}
