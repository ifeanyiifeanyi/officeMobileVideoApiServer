<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\User;
use App\Models\Videos;
use App\Models\categories;
use App\Models\UserVerify;
use App\Helper\UserService;
use App\Models\ActivePlans;
use App\Models\PaymentPlan;
use Illuminate\Support\Str;
use App\Helper\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $token = Str::random(64);

        $userId = rand(1111, 9999);
        // set user account to 0 none active account
        $status = '0';

        $response = (new UserService($userId, $request->name, $request->username, $request->email, $request->password, $status))->register($request->devicename);

        $userId = $response['user_id'];

        // this token is for email verification
        $token = Str::random(64);

        $user = User::where('id', $userId)->first();
        $user->token = $token;
        $user->save();


        //if registration was successful send a email to verify account to activate it
        if ($response) {
            Mail::send('email.emailVerificationEmail', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject("Email verification");
            });
        }

        if (count($response) > 0) {
            return response()->json($response);
        } else {
            return response()->json([
                'error' => "registration failed",
            ], 404);
        }
    }

    public function verifyAccount($token)
    {
        $verifyUser = User::where('token', $token)->first();
        $message = "Sorry your mail could not be identified";

        if (!is_null($verifyUser)) {


            if ($verifyUser->status === 0) {
                $verifyUser->email_verified_at = now();
                $verifyUser->status = 1;
                $verifyUser->save();
                $message = "Your e-mail is verified. You can now login.";

                return response()->json([
                    'message' => $message
                ]);
            } else {
                $message = "Your e-mail is already verified. You can now login.";
                return response()->json([
                    'error' => $message
                ], 404);
            }
        }
        return redirect()->route('confirm.verify')->with('message', $message);
    }

    public function confirmVerify(Request $request)
    {
        return view('email.confirmVerify');
    }

    public function login(Request $request)
    {
        $response = (new LoginService($request->username, $request->password))->login();
        return response()->json($response);
    }


    // select all categories, limit to 3, in random order
    public function category()
    {

        $category = categories::inRandomOrder()->limit(3)->get();

        if ($category) {
            return response()->json($category);
        } else {
            return response()->json([
                'error' => $category->errors()->messages()
            ], 404);
        }
    }

    // select first category
    public function firstCategory()
    {
        $firstCategory = categories::select('name')->take(1)->first();
        if ($firstCategory) {
            return response()->json($firstCategory);
        } else {
            return response()->json([
                'error' => $firstCategory->errors()->messages()
            ], 404);
        }
    }

    // second category
    public function secondCategory()
    {
        $secondCategory = categories::select('name')->skip(1)->first();
        if ($secondCategory) {
            return response()->json($secondCategory);
        } else {
            return response()->json([
                'error' => $secondCategory->errors()->messages()
            ], 404);
        }
    }

    // third category
    public function thirdCategory()
    {
        $thirdCategory = categories::select('name')->skip(2)->first();
        if ($thirdCategory) {
            return response()->json($thirdCategory);
        } else {
            return response()->json([
                'error' => $thirdCategory->errors()->messages()
            ], 404);
        }
    }

    // select all videos
    public function allVideos()
    {
        $videos = DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->join('genres', 'videos.genres_id', '=', 'genres.id')
            ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
            ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
            ->select('videos.id', 'videos.title', 'videos.thumbnail', 'genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
            ->orderBy('id', 'desc')
            ->where("videos.status", 1)
            ->where("categories.name", "Communion")
            ->inRandomOrder()->get();

        if (!$videos) {
            return response()->json([
                'error' => $videos->errors()->messages()
            ], 404);
        } else {
            return response()->json($videos);
        }
    }

    // select videos based on rating
    public function allVideosByRating()
    {
        $videos = DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->join('genres', 'videos.genres_id', '=', 'genres.id')
            ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
            ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
            ->select('videos.id', 'videos.title', 'videos.thumbnail', 'genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
            ->orderBy('id', 'desc')
            ->where('videos.status', 1)
            ->where('categories.name', 'baptism') // add a condition to filter by category name
            ->inRandomOrder()
            ->get();



        return response()->json($videos);
    }

    // NOTE: This function, come back and remove the static category and status values
    public function allVideosByCategory()
    {
        $videos = DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->join('genres', 'videos.genres_id', '=', 'genres.id')
            ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
            ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
            ->select('videos.id', 'videos.title', 'videos.thumbnail', 'genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
            ->orderBy('id', 'desc')
            ->where("videos.status", 1)
            ->where("categories.name", "Catechism")
            ->inRandomOrder()->get();
        if (!$videos) {
            return response()->json([
                'error' => $videos->errors()->messages()
            ], 404);
        } else {
            return response()->json($videos);
        }
    }

    // fetch a video with id, category, genres, rating and parent_control
    // used this function to set the number of view for each video
    public function playVideo($id)
    {
        $video = DB::table('videos')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->join('genres', 'videos.genres_id', '=', 'genres.id')
            ->join('ratings', 'videos.rating_id', '=', 'ratings.id')
            ->join('parent_controls', 'videos.parent_control_id', '=', 'parent_controls.id')
            ->select('videos.*', 'genres.name AS genName', 'categories.name AS catName', 'ratings.name AS rateName', 'parent_controls.name as PcName')
            ->orderBy('id', 'desc')
            ->where("videos.id", $id)->get();

        if ($video) {
            // if video was found increment the number of views
            DB::table('videos')->where('id', $id)->increment('views');

            return response()->json($video);
        } else {
            return response()->json([
                'error' => $video->errors()->messages(),
            ], 404);
        }
    }

    //  intended for displaying list of thumbnails as carousel(not used yet)
    public function BannerThumbnail()
    {
        $videoThumbnail = Videos::select("id", "title", "thumbnail")
            ->latest()
            ->get();
        return response()->json($videoThumbnail);
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
        // $validation = Validator::make($request, [
        //     'userId'               => 'required',
        //     'paymentPlanId'        => 'required',
        //     'duration'             => 'required',
        //     'amount'               => 'required',
        //     'transactionReference'      => 'required|unique:ActivePlans'
        // ]);

        // if ($validation->fails()) {
        //     return response()->json(['error' => $validation->errors()], 400);
        // }
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

    // video likes
    public function VideoLikes(Request $request)
    {
        $videoId = $request->videoId;
        $userId = $request->userId;

        $video = Videos::find($videoId);
        $user = User::find($userId);

        $likes = DB::table('likes')
            ->where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->first();

        if ($likes) {
            return response()->json([
                'message' => 'You have already liked this video.'
            ], 400);
        }

        DB::table('likes')->insert(['user_id' => $user->id, 'video_id' => $video->id]);

        $video->likes += 1;
        $video->save();

        return response()->json([
            'likes' => $video->likes,
        ]);
    }
    public function VideoDislikes(Request $request)
    {
        // dislike not complete
        $videoId = $request->videoId;
        $userId = $request->userId;

        $video = Videos::find($videoId);
        $user = User::find($userId);

        $like = DB::table('likes')
            ->where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->first();

        if ($like) {
            DB::table('likes')
                ->where('user_id', $user->id)
                ->where('video_id', $video->id)
                ->delete();
            $video->likes -= 1;
            $video->save();
            return response()->json([
                'likes' => $video->likes,
            ]);
        } else {
            return response()->json([
                'message' => 'User has not liked this video.',
            ]);
        }
    }


    public function updatePassword(Request $request)
    {

        $userId = $request->userId;
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $currentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;

        $request->validate([
            'newPassword' => 'required|min:8|max:255',
        ]);
        if (!Hash::check($currentPassword, $user->password)) {

            return response()->json(['error' => 'Current password password is incorrect'], 400);
        }
        if (Hash::check($newPassword, $user->password)) {
            return response()->json(['error' => 'The new password must be different from the old one.'], 400);
        }

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        return response()->json(['message' => 'Password updated successfully.'], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Email address is invalid'
            ], 422);
        }

        // check if user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Email address does not exist'
            ], 422);
        }

        // trying to send password reset link to user email
        $response = Password::sendResetLink($request->only('email'));

        if ($response) {
            return response()->json(['success' => true, 'message' => 'A password reset link has been sent to your email']);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Unable to send password reset link.',
            ], 500);
        }

    }

    public function updateProfile(Request $request)
    {
        $userId = $request->uId;
        $name = $request->name;
        $username = $request->username;

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:6|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ], 422);
        }

        // Update user profile
        $user->name = $name;
        $user->username = $username;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function blogContent(){
        $blogs = Blog::latest()->get();
        if (!$blogs) {
            return response()->json([
                'error' => $blogs->errors()->messages()
            ], 404);
        } else {
            return response()->json($blogs);
        }
    }

}