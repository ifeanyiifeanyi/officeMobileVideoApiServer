<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ActivePlans;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManageUserSubscriptionController extends Controller
{
    // fetch active user plan
    public function userActivePlan($id)
    {
        $active_user_plan = DB::table('users')
            ->join('active_plans', 'users.subscription_id', '=', 'active_plans.paymentPlanId')
            ->join('payment_plans', 'active_plans.paymentPlanId', '=', 'payment_plans.id')

            ->select(
                'active_plans.created_at',
                'active_plans.transaction_reference',
                'payment_plans.name',
                'payment_plans.duration_in_name',
                'payment_plans.amount'
            )
            ->where('users.id', $id)
            ->get();
        if ($active_user_plan) {
            return response()->json($active_user_plan);
        } else {
            return response()->json([
                'error' => $active_user_plan->errors->messages(),
            ], 404);
        }
    }
    // fetch all payment plans
    public function paymentPlan()
    {
        $paymentPlans = PaymentPlan::where('status', 1)->latest()->get();

        if ($paymentPlans) {
            return response()->json($paymentPlans);
        } else {
            return response()->json([
                'error' => $paymentPlans->errors()->messages()
            ], 404);
        }
    }

    public function savePayment(Request $request)
    {
        
        $expired_at = 1;
        $userId = (int) $request->userId;
        $paymentPlanId = (int) $request->paymentPlanId;
        $duration = $request->duration;
        $amount = $request->amount;
        $transaction_reference = $request->transactionReference;
        $payment_type = $request->payment_type;

        // find user and update payment plan
        $user = User::where('id', $userId)->update([
            'subscription_id' => $paymentPlanId,
            'subcribe_date' => Carbon::now(),
        ]);
        if (!$user) {
            return response()->json([
                'error' => $user->errors()->messages()
            ], 404);
        }

        $active_plan = new ActivePlans();
        $active_plan->userId = $userId;
        $active_plan->paymentPlanId = $paymentPlanId;
        $active_plan->duration = $duration;
        $active_plan->expired_at = $expired_at;
        $active_plan->transaction_reference = $transaction_reference;
        $active_plan->payment_type = $payment_type;
        $active_plan->amount = $amount;
        $saved_active_plan = $active_plan->save();

        if ($saved_active_plan) {
            return response()->json($saved_active_plan);
        } else {
            return response()->json([
                'error' => $active_plan->errors()->messages()
            ], 500);
        }
    }


}
