<?php

namespace App\Modules\Finder\Feedback;

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
use Browser;
use Validator;
use Exporter;

class FeedbackController extends Controller
{
    public static $view_path = "Finder.Feedback";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        return view($this->render('index'));
    }

    public function feedbacksave(Request $request){
        $base_data = Auth::guard('finder')->user();
        DB::beginTransaction();
        try{
            $feedback = array(
                'finder_id' => $base_data->id,
                'content' => $request->content,
            );
            $result = CF::model('Feedback')->saveData($feedback, true);
            $result['url'] = route('finder.profile.index',$base_data->username);
            $result['message'] = 'Feedback successfully sent!';
            AL::audits('finder',$base_data,$request->ip(),'Send a feedback message.');
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
}
