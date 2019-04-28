<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class FinderAccess {

	public function handle($request, Closure $next){
        
	    if (!Auth::guard('finder')->check()){
	    	return redirect()->route('landing.home.login');
	    }

        return $next($request);

    }

}