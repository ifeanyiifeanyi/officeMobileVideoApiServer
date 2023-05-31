<?php

namespace App\Listeners;

use App\Events\SubscriptionExpiration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;

class CheckSubscriptionExpiration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SubscriptionExpiration  $event
     * @return void
     */
    public function handle(SubscriptionExpiration $event)
    {
        $activePlan = $event->activePlans;
        $currentDate = Carbon::now();
        $expirationDate = $activePlan->created_at->addDays($activePlan->duration);

        if ($currentDate->greaterThanOrEqualTo($expirationDate)) {
            // The subscription has expired, perform necessary actions here (e.g., delete active plan from table)
            $activePlan->delete();

            // Update the user's subscription details in the users table
            $user = $activePlan->user;
            $user->subscription_id = null;
            $user->subcribe_date = null;
            $user->save();
        } else {
            $daysRemaining = $currentDate->diffInDays($expirationDate);
            // You can perform any necessary actions here for the remaining days of the subscription
        }

    }
}
