<?php
namespace App\Helper;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


class LoginService
{
    public $username, $password;
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }


    public function validateInputLogin()
    {

        $validator = Validator::make(
            [
                "username" => $this->username,
                "password" => $this->password
            ],
            [
                'username' => ['required', 'string'],
                'password' => ['required', 'string', Password::min(8)]
            ]
        );
        if ($validator->fails()) {
            return ['status' => false, 'message' => $validator->messages()];
        } else {
            return ['status' => true];
        }
    }

    public function login()
    {
        $validate = $this->validateInputLogin();
        if ($validate['status'] == false) {
            return $validate;
        }

        $user = null; // Initialize $user variable with a default value

        $user = User::where('username', $this->username)
            ->orWhere('email', $this->username)
            ->orWhere('userid', $this->username)
            ->first();

        if ($user && $user->status == 1) {
            if (Hash::check($this->password, $user->password)) {
                $active_user_plan = DB::table('users')
                    ->leftJoin('active_plans', function ($join) use ($user) {
                        $join->on('users.subscription_id', '=', 'active_plans.paymentPlanId')
                            ->where('active_plans.userId', '=', $user->id);
                    })
                    ->leftJoin('payment_plans', 'active_plans.paymentPlanId', '=', 'payment_plans.id')
                    ->select(
                        'active_plans.created_at',
                        'active_plans.transaction_reference',
                        'payment_plans.name',
                        'payment_plans.duration_in_name',
                        'payment_plans.duration_in_number',
                        'payment_plans.amount'
                    )
                    ->where('users.id', $user->id)
                    ->whereNotNull('active_plans.paymentPlanId')
                    ->get();

                if ($active_user_plan->count() > 0) {
                    $durationInNumber = $active_user_plan[0]->duration_in_number;
                    $createdAt = $active_user_plan[0]->created_at;

                    $expiryDate = date('Y-m-d', strtotime("+$durationInNumber days", strtotime($createdAt)));

                    return ['status' => true, 'username' => $user, 'user_plan' => $active_user_plan, 'expiry_date' => $expiryDate];
                } else {
                    return ['status' => true, 'username' => $user, 'user_plan' => [], 'expiry_date' => null];
                }
            } else {
                return ['status' => false, 'message' => 'Incorrect password or username.'];
            }
        } else {
            return ['status' => false, 'message' => 'Account has not been activated. Please contact us.'];
        }
    }



}