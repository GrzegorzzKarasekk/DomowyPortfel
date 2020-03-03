<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    protected $redirectTo = RouteServiceProvider::HELLO;

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
        $data['name'] = mb_convert_case($data['name'],MB_CASE_TITLE,"UTF-8");
        
        return Validator::make($data, [
            'name' => ['regex:/^[A-ZŁŚŻŹ]{1}[a-ząęółśżźćń]+$/i','required', 'string', 'between:3,255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
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
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        $userId = DB::table('users')->get()->last()->id;

        $default_incomes = DB::table('default_incomes')->get();

        foreach ($default_incomes as $default_income)
        {
            DB::table('users_incomes')->insertGetId(
                array('user_id' => $userId, 'category_name' => $default_income->category_name)
            );
        }

        $default_expenses = DB::table('default_expenses')->get();

        foreach ($default_expenses as $default_expense)
        {
            DB::table('users_expenses')->insertGetId(
                array('user_id' => $userId, 'category_name' => $default_expense->category_name)
            );
        }

        $default_payment_methods = DB::table('default_payment_methods')->get();

        foreach ($default_payment_methods as $default_payment_method)
        {
            DB::table('users_payment_methods')->insertGetId(
                array('user_id' => $userId, 'payment_method' => $default_payment_method->payment_method)
            );
        }


        //DB::insert('insert into users_incomes (user_id,category_name) SELECT category_name FROM default_incomes'); 

        //DB::update('update users_incomes set user_id = $userId where user_id = 0');
        $defaults_incomes = [];
        $userId = [];

        return $user;
        

    }
}
