<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaymentPlansController extends Controller
{
    public function index()
    {
        $allplans  = PaymentPlan::latest()->where('status', 1)->get();

        // fetch active subscription, display activated subscription once 
        // even if multiple users subscribed it
        $activePlans = PaymentPlan::join('active_plans', 'payment_plans.id', '=', 'active_plans.paymentPlanId') 
        ->groupBy('payment_plans.id')
        ->select(DB::raw('MAX(active_plans.created_at) AS Active_date'), 'payment_plans.name AS plan_name', 'payment_plans.duration_in_name AS plan_duration_name', 'payment_plans.id AS plan_id', 'payment_plans.amount As amount')
        ->get();
    
            // dd($activePlans);
        return view('admin.paymentplans.index', compact('allplans', 'activePlans'));
    }

    public function create()
    {
       return view('admin.paymentplans.create');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required|unique:payment_plans|max:100|string|min:3',
            'amount' => 'required|min:3|integer',
            'duration_in_name' => 'required|max:255|string',
            'duration_in_number' => 'required|integer'
        ]);

        $pp = new PaymentPlan();
        $pp->name  = $request->name;
        $pp->amount = $request->amount;
        $pp->duration_in_name = $request->duration_in_name;
        $pp->duration_in_number = $request->duration_in_number;
        $pp->status = $request->status ? 1 : 0;
        $pp->save();

        return redirect()->route('payment.plan')->with('status', "New Payment Plan Created!");
    }


    public function edit($id)
    {
        $plan = PaymentPlan::find($id);
        return view('admin.paymentplans.edit', compact('plan'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|max:100|string|min:3',
            'amount' => 'required|min:3|integer',
            'duration_in_name' => 'required|max:255|string',
            'duration_in_number' => 'required|integer'
        ]);

        $pp = PaymentPlan::find($id);
        $pp->name  = $request->name;
        $pp->amount = $request->amount;
        $pp->duration_in_name = $request->duration_in_name;
        $pp->duration_in_number = $request->duration_in_number;
        $pp->status = $request->status ? 1 : 0;
        $pp->update();

        return redirect()->route('payment.plan')->with('status', "Payment Plan Updated!");
    }

    public function delete($id){
        PaymentPlan::find($id)->delete();
        return redirect()->route('payment.plan')->with('status', "Payment Plan Deleted!");
    }
  
    // fetch active plans
   


}
