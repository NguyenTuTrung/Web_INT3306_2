<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function showLoginForm()
    {
        $pageTitle = "Sign In";
        return view('auth.login', compact('pageTitle'));
    }

    public function login(Request $request)
    {


        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);


        return $this->sendFailedLoginResponse($request);
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        $request->validate($validation_rule);

    }

    public function logout()
    {
        $this->guard()->logout();
        request()->session()->invalidate();
        $notify[] = ['success', 'You have been logged out.'];
        return redirect()->route('login')->withNotify($notify);
    }


    public function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            $notify[] = ['error','Your account has been deactivated.'];
            return redirect()->route('login')->withNotify($notify);
        }
        $user = auth()->user();
        $user->tv = $user->ts == 1 ? 0 : 1;
        $user->save();
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();
        if($user->user_type != 'admin') {
            if ($exist) {
                $userLogin->longitude =  $exist->longitude;
                $userLogin->latitude =  $exist->latitude;
                $userLogin->city =  $exist->city;
                $userLogin->country_code = $exist->country_code;
                $userLogin->country =  $exist->country;
            }else{
                $info = json_decode(json_encode(getIpInfo()), true);
                $userLogin->longitude =  @implode(',',$info['long']);
                $userLogin->latitude =  @implode(',',$info['lat']);
                $userLogin->city =  @implode(',',$info['city']);
                $userLogin->country_code = @implode(',',$info['code']);
                $userLogin->country =  @implode(',', $info['country']);
            }

            $userAgent = osBrowser();
            $userLogin->user_id = $user->id;
            $userLogin->user_ip =  $ip;
            
            $userLogin->browser = @$userAgent['browser'];
            $userLogin->os = @$userAgent['os_platform'];
            $userLogin->save();
        }
        if($user->user_type == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        elseif($user->user_type == 'manager') {
            return redirect()->route('manager.dashboard');
        }
        elseif($user->user_type == 'staff') {
            return redirect()->route('staff.dashboard');
        }
        elseif($user->user_type == 'manager_warehouse') {
            return redirect()->route('manager_warehouse.dashboard');
        }
        elseif($user->user_type == 'staff_warehouse') {
            return redirect()->route('staff_warehouse.dashboard');
        }
        elseif($user->user_type == 'delivery_man') {
            return redirect()->route('delivery_man.dashboard');
        }
    }
}
