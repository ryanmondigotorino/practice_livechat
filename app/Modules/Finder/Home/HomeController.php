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
use Browser;
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
}
