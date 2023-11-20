<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Customers;
use App\Models\Seller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
//    protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo() {
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'account_type' => ['in:2,3'],
            'term-of-accept' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['account_type'],
        ]);

        if ($data['account_type'] === '2') {
            Seller::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
                'zip_code' => $data['ZipCode'],
                'term_condition' => $data['term-of-accept'],
            ]);
        }
        else if($data['account_type'] === '3'){
            Buyer::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
                'zip_code' => $data['ZipCode'],
                'term_condition' => $data['term-of-accept'],
            ]);
        }


        return $user;
    }
}
