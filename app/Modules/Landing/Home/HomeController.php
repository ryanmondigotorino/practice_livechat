<?php

namespace App\Modules\Landing\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailVerification;
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

    public function signup(Request $request){
        return view($this->render('contents.sign-up'));
    }

    public function signupsubmit(Request $request){
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(),[
                'password' => 'required_with:confirm_password|min:8|same:confirm_password',
                'confirm_password' => 'required|min:8'
            ]);
            if($validator->fails()){
                return array(
                    'status' => 'error',
                    'messages' => $validator->errors()->first()
                );
            }
            $email = strtolower($request->email);
            $username = strtolower($request->username);
            $patients = array(
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $email,
                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
                'username' => $username,
                'password' => bcrypt($request->confirm_password),
                'account_line' => 0,
                'account_status' => 0
            );
            $result = CF::model('Finder')->saveData($patients, true);
            $result['url'] = route('landing.home.index');
            $result['message'] = 'Account created Successfully! Please Check your email for account verification.';
            DB::commit();
            $data = array(
                'name' => $request->firstname.' '.$request->lastname,
                'username' => strtolower($request->username),
                'email' => $email
            );
            Mail::to($email)->send(new SendEmailVerification($data));
            return $result;
        }catch(\Exception $e){
            $errors = json_decode($e->getMessage(), true);
            $display_errors = [];
            foreach($errors as $key => $value){
                $display_errors[] = $value[0];
            }
            $result = [
                'status' => 'error',
                'message' => implode("\n",$display_errors)
            ];
            DB::rollBack();
            return $result;
        }
        Session::flash('message',$result['status']);
        return back();
    }

    public function accountVerification($userName){
        $customerDetails = CF::model('Finder')->where('username',$userName)->get();
        $customerDetails[0]->account_status = 1;
        $customerDetails[0]->save();
        return redirect()->route('landing.home.index');
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
