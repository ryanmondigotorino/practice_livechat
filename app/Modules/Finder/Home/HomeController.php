<?php

namespace App\Modules\Finder\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use AuditLogs as AL;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

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
        $base_data = Auth::guard('finder')->user();
        $getuserdata = CF::model('Finder')::find($base_data->id);
        if($base_data->account_line == 0){
            $getuserdata->account_line = 1;
            $getuserdata->save();
        }
        return view($this->render('index'));
    }

    public function getfirstacc(Request $request){
        $base_data = Auth::guard('finder')->user();
        $getMessagesfrom = CF::model('Message_request')->where('from_id',$base_data->id);
        $getMessagesto = CF::model('Message_request')->where('to_id',$base_data->id);
        $array = [];
        if($getMessagesfrom->count() > 0){
            $array = $getMessagesfrom
                ->join('finders','finders.id','message_requests.to_id')
                ->get();
        }elseif($getMessagesto->count() > 0){
            $array = $getMessagesto
                ->join('finders','finders.id','message_requests.from_id')
                ->get();
        }
        $otherRouteUsername = isset($array[0]->username) ? route('finder.chat-room.index',[$base_data->username,$array[0]->username]) : route('finder.chat-room.index',$base_data->username);
        return array(
            'route' => $otherRouteUsername
        );
    }

    public function view(Request $request,$slug){
        $viewUserid = CF::model('Finder')->select('id')->where('username',$slug)->get();
        $viewUser = CF::model('Finder')::find($viewUserid[0]->id);
        return view($this->render('contents.view-users'),compact('viewUser'));
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

    public function sendmessage(Request $request){
        $base_data = Auth::guard('finder')->user();
        $otherUser = CF::model('Finder')::find($request->id);
        $findValidate1 = CF::model('Message_request')
            ->where([
                ['from_id',$base_data->id],
                ['to_id',$otherUser->id],
            ])->count();
        $findValidate2 = CF::model('Message_request')
            ->where([
                ['from_id',$otherUser->id],
                ['to_id',$base_data->id],
            ])->count();
        if($findValidate1 > 0 || $findValidate2 > 0){
            $result['status'] = 'success';
            $result['url'] = route('finder.chat-room.index',[$base_data->username,$otherUser->username]);
            return $result;
        }else{
            //Firebase INIT
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firebase_credentials.json');
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->create();
            
            $database = $firebase->getDatabase();
            $ref = $database->getReference('chat-room');
            //End of Firebase INIT
            DB::beginTransaction();
            try{
                $message_request = array(
                    'from_id' => $base_data->id,
                    'to_id' => $otherUser->id,
                    'status' => 'active'
                );
                $getMR = CF::model('Message_request')->select('id')->withTrashed()->orderBy('id','desc')->limit(1);
                $getMRId = $getMR->count() > 0 ? $getMR->get()[0]->id + 1 : 1;
                $initial_message = array(
                    'message_request_id' => $getMRId,
                    'from_id' => $base_data->id,
                    'message' => 'Hi'
                );
                //Inserting firebase
                $key = $ref->push()->getKey();
                $getMessage = CF::model('Message')->select('id')->withTrashed()->orderBy('id','desc')->limit(1);
                $getMessageId = $getMessage->count() > 0 ? $getMessage->get()[0]->id + 1 : 1;
                $firebase_message = array(
                    'id' => $getMessageId,
                    'message_request_id' => $getMRId,
                    'from_id' => $base_data->id,
                    'message' => 'Hi'
                );
                $ref->getChild($key)->set($firebase_message);
                //End firebase insert
                $result = CF::model('Message_request')->saveData($message_request, true);
                CF::model('Message')->saveData($initial_message, true);
                AL::audits('finder',$base_data,$request->ip(),'Add message request to '.$otherUser->firstname.' '.$otherUser->lastname);
                DB::commit();
                $result['url'] = route('finder.chat-room.index',[$base_data->username,$otherUser->username]);
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
        
    }
}
