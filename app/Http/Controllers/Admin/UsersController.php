<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ActivePlans;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest()->simplePaginate(3);
        return view('admin.users.index', compact('users'));
    }

    public function details($id)
    {
        $user = User::find($id);

        // find out if the user has active subscription
        $activePlan = ActivePlans::where("userId", $user->id)->first();
        $paymentPlan = null; // incase no active subscription
        $allplans = null;
        if ($activePlan) {
            // then get the subscription details
            $paymentPlan = PaymentPlan::find($activePlan->paymentPlanId);
        } else {
            $allplans = PaymentPlan::latest()->where('status', 1)->get();
        }

        // dd($activePlan);
        return view('admin.users.details', compact('user', 'activePlan', 'paymentPlan', 'allplans'));
    }

    // suspend user account with user if
    public function suspend($id)
    {
        $user_id = User::find($id);
        $user_id->status = '0';
        $user_id->update();
        return redirect()->route('users.all')->with('status', $user_id->name . " account has been Suspended!");
    }
    public function activate($id)
    {
        $user_id = User::find($id);

        $user_id->status = '1';
        $user_id->update();
        return redirect()->route('users.all')->with('status', $user_id->name . " account has been activated!");
    }
    public function MakeAdmin($id)
    {
        $user_id = User::find($id);

        $user_id->role_as = 1;
        $user_id->update();
        return redirect()->route('users.all')->with('status', $user_id->name . " has been made an ADMIN!");
    }
    public function RevokeAdmin($id)
    {
        $user_id = User::find($id);

        $user_id->role_as = 0;
        $user_id->update();
        return redirect()->route('users.all')->with('status', $user_id->name . " Admin access revoked!");
    }


    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.all')->with('status', 'User account deleted');
    }

    public function select($userId, $subscriptionId)
    {
        // return [$subscriptionId, $userId];
        $user = User::where('id', $userId)->first();
        $subscription = PaymentPlan::where('id', $subscriptionId)->first();
        // dd($subscription);
        return view('admin.users.subscribe_for_user', compact('user', 'subscription'));
    }
    public function paySelected(Request $request)
    {
        $request->validate([
            'transaction_reference' => 'required|string|unique:active_plans',
            'payment_type' => 'required|string'
        ]);
    
        $user = User::find($request->userId);
    
        // Check if the user is already subscribed
        if ($user->subscription_id !== null) {
            return back()->with('error', 'User is already subscribed.');
        }
    
        $active_plan = new ActivePlans();
        $active_plan->transaction_reference = $request->transaction_reference;
        $active_plan->payment_type = $request->payment_type;
        $active_plan->userId = $request->userId;
        $active_plan->paymentPlanId = $request->paymentPlanId;
        $active_plan->amount = $request->amount;
        $active_plan->duration = $request->duration;
        $active_plan->expired_at = 1;
        $save_plan = $active_plan->save();
    
        if ($save_plan) {
            $plan = PaymentPlan::find($request->paymentPlanId);
    
            $emailData = [
                'plan_name' => $plan->name,
                'amount' => $request->amount,
                'activated_at' => now()->format('Y-m-d'),
                'user' => $user,
            ];
            Mail::send('email.subscriptionEmailConfirm', $emailData, function ($message) use ($user) {
                $message->to($user->email);
                $message->subject("Subscription Activation Email");
            });
    
            User::where('id', $request->userId)->update([
                'subscription_id' => $request->paymentPlanId,
                'subcribe_date' => Carbon::now(),
            ]);
    
            return redirect()->route('users.all')->with('status', 'New Subscription started for the user.');
        }
    
        return back()->with('error', 'Failed to start the subscription for the user.');
    }
    
}