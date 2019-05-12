<?php

namespace App\Modules\Landing\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;
use Browser;
use Validator;

class HomeController extends Controller
{
    public static $view_path = "Landing.Home";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        if(Auth::guard('finder')->check()){
            $base_data = Auth::guard('finder')->user();
            return redirect()->route('finder.profile.index',$base_data->username);
        }if(Auth::guard('admin')->check()){
            return redirect()->route('admin.dashboard.index');
        }
        return view($this->render('index'));
    }

    public function loginSave(Request $request){
        $field = filter_var($request->email_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $finderguard = Auth::guard('finder')->attempt([$field => $request->email_username,'password' => $request->password]);
        $adminGuard = Auth::guard('admin')->attempt([$field => $request->email_username,'password' => $request->password]);
        $finder = Auth::guard('finder')->attempt([$field => $request->email_username,'password' => $request->password,'account_status' => 1]);
        $admin = Auth::guard('admin')->attempt([$field => $request->email_username,'password' => $request->password,'account_status' => 1]);
        if($finderguard || $adminGuard){
            if($finder || $admin){
                if(Auth::guard('admin')->check()){
                    $user = Auth::guard('admin')->user();
                }elseif(Auth::guard('finder')->check()){
                    $user = Auth::guard('finder')->user();
                }
                $result['status'] = 'success';
                $result['url'] = 'none';
                $result['messages'] = 'Login Successful';
            }else{
                $result['status'] = 'warning';
                $result['messages'] = 'Your account was not activated! Please check your email.';
                Auth::guard('finder')->logout();
                Auth::guard('admin')->logout();
            }
        }else{
            $result['status'] = 'error';
            $result['messages'] = 'Invalid username or password!';   
        }
        return $result;
    }

    public function logout(Request $request){
        $guard = $request->guard;
        if(Auth::guard($guard)->check()){
            $accountsData = CF::model($request->model)::find($request->id);
            $accountsData->account_line = 0;
            $accountsData->save();
            Auth::guard($guard)->logout();
            return 'success';
        }
    }
}
