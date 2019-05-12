<?php

namespace App\Traits;

use ClassFactory as CF;
use Auth;


trait ViewRender {

    public function render($page) {
        
        $this->loadGlobalData();
        
        return static::$view_path.'::Views.'.$page;
    }

    public function loadGlobalData(){
      
        if(isset(request()->route()->action['guard'])){
            if (Auth::guard('admin')->check()){
                $user =  Auth::guard('admin')->user();
                \View::share('base_data', $user);
            }
            if (Auth::guard('finder')->check()){
                $user =  Auth::guard('finder')->user();
                $middlename = $user->middlename == null || $user->middlename == '' ? ' ' : ' '.$user->middlename.' ';
                \View::share('base_data', $user);
                \View::share('middlename', $middlename);
            }
        }
    }
}