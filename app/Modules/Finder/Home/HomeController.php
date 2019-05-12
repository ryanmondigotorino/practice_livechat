<?php

namespace App\Modules\Finder\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use AuditLogs as AL;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;
use Hash;
use Validator;

class HomeController extends Controller
{
    public static $view_path = "Finder.Home";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        return view($this->render('index'));
    }

    public function imageupload(Request $request){
        $userData = CF::model('Finder')::find($request->id);
        if($userData['image'] != 'noimage.png' || $userData['image'] != null){
            $result = Storage::disk('uploads')->delete('uploads/profile_images/user('.$userData['id'].')/'.$userData['image']);
        }
        $data = [];
        $data['id'] = $request->id;
        $extension = strtolower($request->image_profile->extension());
        $image_file = $request->image_profile;
        $result = [];
        switch ($extension){
            case 'jpg':
            case 'jpeg':
            case 'png':
                $imageName = 'finder_chat_'.time().'_'.$userData['firstname'].'_'.$userData['lastname'].'.'.$extension;
                break;
            default:
                    $result['status'] = 'error';
                    $result['msg'] = 'Invalid File Type';
                    return $result;
                break;
        }
        $data['image'] = $imageName;
        Storage::disk('uploads')->putFileAs('uploads/profile_images/user('.$userData['id'].')',$image_file,$imageName);
        CF::model('Finder')->saveData($data);
        AL::audits('finder',$userData,$request->ip(),'Change my profile picture');
    }

    public function editaccount(Request $request){
        return view($this->render('contents.edit-account'));
    }

    public function editaccountsave(Request $request){
        $base_data = Auth::guard('finder')->user();
        $getUserData = CF::model('finder')::find($base_data->id);
        if(!isset($request->middlename) || $request->middlename == ''){
            $validator = Validator::make($request->all(),[
                'firstname' => 'required|regex:/^[a-zA-Z]+$/u',
                'lastname' => 'required|regex:/^[a-zA-Z]+$/u',
                'birthdate' => 'required',
                'contact_number' => 'numeric'
            ]);
        }else{
            $validator = Validator::make($request->all(),[
                'firstname' => 'required|regex:/^[a-zA-Z]+$/u',
                'middlename' => 'regex:/^[a-zA-Z]+$/u',
                'lastname' => 'required|regex:/^[a-zA-Z]+$/u',
                'birthdate' => 'required',
                'contact_number' => 'numeric'
            ]);
        }
        if($validator->fails()){
            return array(
                'status' => 'error',
                'message' => $validator->errors()->first()
            );
        }elseif(strlen($request->contact_number) != 11){
            return array(
                'status' => 'error',
                'message' => 'Contact number must be at least 11 digits.'
            );
        }
        $getUserData->firstname = $request->firstname;
        $getUserData->middlename = $request->middlename;
        $getUserData->lastname = $request->lastname;
        $getUserData->birthdate = $request->birthdate;
        $getUserData->contact_num = $request->contact_number;
        $getUserData->address = $request->address;
        $getUserData->save();
        AL::audits('finder',$base_data,$request->ip(),'Update my information.');
        return array(
            'status' => 'success',
            'message' => 'Information successfully updated',
            'url' => route('finder.profile.edit-account',$base_data->username)
        );
    }

    public function changepassword(Request $request){
        return view($this->render('contents.change-password'));
    }

    public function changepasswordsubmit(Request $request){
        $base_data = Auth::guard('finder')->user();
        $oldPassword = $request->old_password;
        if(\Hash::check($request->old_password,$base_data->password)){
            $getUserData = CF::model('Finder')::find($base_data->id);
            $validator = Validator::make($request->all(),[
                'password' => 'required_with:confirm_password|min:8|same:confirm_password',
                'confirm_password' => 'required|min:8'
            ]);
            if($validator->fails()){
                return array(
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                );
            }else{
                $getUserData->password = bcrypt($request->confirm_password);
                $getUserData->save();
                AL::audits('finder',$base_data,$request->ip(),'Change my password');
                return array(
                    'status' => 'success',
                    'url' => route('finder.profile.index',$base_data->username),
                    'message' => 'Password Successfully Change!'
                );
            }
        }else{
            return array(
                'status' => 'error',
                'message' => 'Old password doesn\'t match your input.'
            );
        }
    }
}
