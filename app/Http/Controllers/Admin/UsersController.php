<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ActivePlans;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest()->simplePaginate(3);
        return view('admin.users.index', compact('users'));
    }

    public function details($id){
        $user = User::find($id);

        // find out if the user has active subscription
        $activePlan = ActivePlans::where("userId", $user->id)->first();
        if($activePlan){
            // then get the subscription details
            $paymentPlan = PaymentPlan::find($activePlan->paymentPlanId);
        }
        
        dd($activePlan);
        return view('admin.users.details', compact('user', 'activePlan', 'paymentPlan'));
    }

    // suspend user account with user if
    public function suspend($id){
        $user_id = User::find($id);
        $user_id->status = '0';
        $user_id->update();
        return redirect()->route('users.all')->with('status', $user_id->name." account has been Suspended!");
    }
    public function activate($id){
        $user_id = User::find($id);

        $user_id->status = '1';
        $user_id->update();
        return redirect()->route('users.all')->with('status', $user_id->name." account has been activated!");
    }
    public function MakeAdmin($id){
        $user_id = User::find($id);

        $user_id->role_as =  1;
        $user_id->update();
        return redirect()->route('users.all')->with('status', $user_id->name." has been made an ADMIN!");
    }
    public function RevokeAdmin($id){
        $user_id = User::find($id);

        $user_id->role_as =  0;
        $user_id->update();
        return redirect()->route('users.all')->with('status', $user_id->name." Admin access revoked!");
    } 


    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.all')->with('status', 'User account deleted');
    }
}
