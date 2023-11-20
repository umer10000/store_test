<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
//use FedEx\AddressValidationService\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
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
    //    protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo()
    {
        $role = Auth::user()->role_id;
        switch ($role) {
            case '1':
                return 'admin/dashboard';
                break;
            case '2':
                return 'seller/dashboard';
                break;
            case '3':
                return 'buyer/dashboard';
                break;
            default:
                return '/home';
                break;
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function authenticate(Request $request){
    //     die('das');
    //     if ($this->guard()->validate($this->credentials($request))) {

    //                 $user = $this->guard()->getLastAttempted();
    //                 // dd($user);
    //                 if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
    //                     return $this->redirectTo();
    //                 } else {
    //                     $this->incrementLoginAttempts($request);
    //                     return false;
    //         }
    //     }
    // }

    protected function login(Request $request)
    {

        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        if ($this->guard()->validate($credentials)) {
            $user = User::where('email', $credentials['email'])->where('status', 1)->first();
            if ($user !== null) {
                Auth::attempt($credentials);
                if ($user->role_id == 1) {
                    return redirect()->to('admin/dashboard');
                } elseif ($user->role_id == 2) {
                    return redirect()->to('seller/dashboard');
                } elseif ($user->role_id == 3) {
                    return redirect()->to('buyer/dashboard');
                } else {
                    return 'home';
                }
            }
            return redirect()->back()->withErrors('Your Account Might Be DeActivated!');
        }

        return redirect()->back()->withErrors('You have entered an invalid Email or Password');
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     if ($user->role_id == 1) {
    //         //return redirect()->route('trainer.dashboard');
    //         return redirect()->to('admin/dashboard');
    //     } elseif($user->role_id == 2) {
    //         //return redirect()->route('customer.dashboard');
    //         return redirect()->to('seller/dashboard');
    //     } elseif($user->role_id == 3) {
    //         //return redirect()->route('customer.dashboard');
    //         return redirect()->to('buyer/dashboard');
    //     }else{
    //         return 'home';
    //     }
    // }

}
