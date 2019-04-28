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
        return view($this->render('index'));
    }
}
