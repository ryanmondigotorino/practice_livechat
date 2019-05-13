<?php

namespace App\Modules\Finder\Chatroom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

use Auth;
use View;
use DB;
use URL;
use Browser;
use Validator;

class ChatroomController extends Controller
{
    public static $view_path = "Finder.Chatroom";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request,$slug,$slugto = null){
        // $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firebase_credentials.json');
        // $firebase = (new Factory)
        //     ->withServiceAccount($serviceAccount)
        //     ->create();
        
        // $database = $firebase->getDatabase();
        // $ref = $database->getReference('chat-room');
        // $key = $ref->push()->getKey();
        // $ref->getChild($key)->set([
        //     'message_request_id' => 1,
        //     'from_id' => 1,
        //     'message' => 'Hello'
        // ]);
        // $getKey = $ref->getChildKeys();
        // $getValue = $ref->getValue();
        // return $getValue[$getKey[count($getKey) - 1]];
        // exit;
        $base_data = Auth::guard('finder')->user();
        if(isset($slugto)){
            $getSlugDetails = CF::model('Finder')->where('username',$slugto)->get();
            $getMessagesRequestfrom = CF::model('Message_request')
                ->select(
                    'finders.id as finders_id',
                    'finders.firstname',
                    'finders.lastname',
                    'finders.image',
                    'finders.account_line',
                    'finders.username'
                )   
                ->where('to_id',$base_data->id);
            $getMessagesRequestto = CF::model('Message_request')
                ->select(
                    'finders.id as finders_id',
                    'finders.firstname',
                    'finders.lastname',
                    'finders.image',
                    'finders.account_line',
                    'finders.username'
                )
                ->where('from_id',$base_data->id);
            if($getMessagesRequestfrom->count() > 0){
                $messages['message_side'] = $getMessagesRequestfrom->join('finders','finders.id','message_requests.from_id')->get();
            }elseif($getMessagesRequestto->count() > 0){
                $messages['message_side'] = $getMessagesRequestto->join('finders','finders.id','message_requests.to_id')->get();
            }
            $messages['main_head'] = $getSlugDetails;
        }
        return view($this->render('index'),compact('messages'));
    }

    public function sendchatsample(Request $request){
        return $request->all();
    }

    public function sendchat(Request $request,$slug,$slugto){
        $base_data = Auth::guard('finder')->user();
        //Firebase INIT
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firebase_credentials.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        
        $database = $firebase->getDatabase();
        $ref = $database->getReference('chat-room');
        //End of Firebase INIT
        $getSlugDetails = CF::model('Finder')->where('username',$slugto)->get();
        if(isset($request->type_msg)){
            DB::beginTransaction();
            try{
                $message = array(
                    'message_request_id' => $request->message_request_id,
                    'from_id' => $base_data->id,
                    'message' => $request->type_msg
                );
                //Inserting firebase
                $key = $ref->push()->getKey();
                $getMessage = CF::model('Message')->select('id')->withTrashed()->orderBy('id','desc')->limit(1);
                $getMessageId = $getMessage->count() > 0 ? $getMessage->get()[0]->id + 1 : 1;
                $firebase_message = array(
                    'id' => $getMessageId,
                    'message_request_id' => $request->message_request_id,
                    'from_id' => $base_data->id,
                    'message' => $request->type_msg
                );
                $ref->getChild($key)->set($firebase_message);
                //End firebase insert
                $result = CF::model('Message')->saveData($message, true);
                DB::commit();
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
        $messages_details = CF::model('Message')
            ->select(
                'messages.id',
                'finders.id as finder_id',
                'finders.image',
                'finders.firstname',
                'finders.lastname',
                'messages.message',
                'message_requests.to_id as request_to',
                'message_requests.id as message_request_id',
                'messages.from_id as message_from'
            )
            ->where('messages.id',$request->id)
            ->leftjoin('message_requests','message_requests.id','messages.message_request_id')
            ->leftjoin('finders','finders.id','messages.from_id');
        $messages_details = $messages_details->where(function($query) use ($base_data){
            $query
                ->orwhere('message_requests.to_id',$base_data->id)
                ->orwhere('message_requests.from_id',$base_data->id);
        })->where(function($query) use ($getSlugDetails){
            $query
                ->orwhere('message_requests.to_id',$getSlugDetails[0]->id)
                ->orwhere('message_requests.from_id',$getSlugDetails[0]->id);
        })
        ->get();
        $message = [];
        if(count($messages_details) <= 0){
            return '
                <p class="text-white">First time conversation in this forum. Send your message now.</p>
            ';
        }
        foreach($messages_details as $key => $details){
            if($details->message_from == $base_data->id){
                $message['messages_details'][$key] = '
                    <div class="d-flex justify-content-end mb-4">
                        <div class="msg_cotainer_send">
                            '.$details->message.'
                        </div>
                        <div class="img_cont_msg">
                            <img src="'.URL::asset('storage/uploads/profile_images/user('.$details->finder_id.')/'.$details->image).'" class="rounded-circle user_img_msg">
                        </div>
                    </div>
                ';
            }else{
                $message['messages_details'][$key] = '
                    <div class="d-flex justify-content-start mb-4">
                        <div class="img_cont_msg">
                            <img src="'.URL::asset('storage/uploads/profile_images/user('.$details->finder_id.')/'.$details->image).'" class="rounded-circle user_img_msg">
                        </div>
                        <div class="msg_cotainer">
                            '.$details->message.'
                        </div>
                    </div>
                ';
            }
        }
        $message['message_request_id'] = $messages_details[0]->message_request_id;
        return $message;
    }
}
