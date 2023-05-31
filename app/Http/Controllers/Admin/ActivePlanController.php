<?php

namespace App\Http\Controllers\Admin;

use App\Events\SubscriptionExpiration;
use App\Models\User;
use Nette\Utils\DateTime;
use App\Models\ActivePlans;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivePlanController extends Controller
{
    public function index($id)
    {
        // come back here and change this just to show the users that are active 
        // then add a btn to see when their plan will end or remaining days

        $activePlans = PaymentPlan::join('active_plans', 'payment_plans.id', '=', 'active_plans.paymentPlanId')
            ->join('users', 'users.id', '=', "active_plans.userId")
            ->where('active_plans.paymentPlanId', '=', $id)
            ->select('payment_plans.*', 'active_plans.*', 'users.*', 'active_plans.created_at AS startDate', 'payment_plans.name AS payment_name', 'payment_plans.duration_in_number AS remaining_days', 'active_plans.id AS activePlanId')->get();
        // dd($activePlans);

        return view('admin.ActivePlans.index', compact('activePlans'));
    }
    public function activeUserSubscription(ActivePlans $activePlans)
    {

        $activePlan = ActivePlans::find($activePlans->id);
        // dd($activePlan);
        $paymentPlan = PaymentPlan::find($activePlan->paymentPlanId);
        $user = User::find($activePlan->userId);
        // Trigger the SubscriptionExpiration event
        // event(new SubscriptionExpiration($activePlan));

        if (!$activePlan) {
            return redirect()->back()->with('error', 'User subscription not found.');
        }



        if (!$paymentPlan || !$user) {
            return redirect()->back()->with('error', 'Payment plan or user not found.');
        }

        $startDate = new DateTime($activePlan->created_at);
        $endDate = clone $startDate;
        $endDate->modify('+' . $paymentPlan->duration_in_number . ' days');
        $today = new DateTime();
        $diff = $today->diff($endDate);
        $remainingDays = $diff->format('%a');
        // dd($paymentPlan);

        return view('admin.ActivePlans.show', compact('activePlan', 'user', 'paymentPlan', 'startDate', 'endDate', 'remainingDays'));



    }
}