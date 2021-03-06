<div class="d-flex flex-column fixed-top flex-md-row align-items-center p-3 px-md-3 mb-3 border-bottom shadow-sm" style="background-color: #FF9E9E;">
    <h5 class="my-0 mr-1 font-weight-normal"><img src="{{$base_data->image == null || $base_data->image == '' ? URL::asset('public/css/assets/noimage.png') : URL::asset('storage/uploads/profile_images/user('.$base_data->id.')/'.$base_data->image)}}" style="width:50px;height:50px;border-radius:50%;"></h5>
    <nav class="my-1 my-md-0 mr-md-auto">
        <a class="p-3 text-dark" href="{{route('finder.profile.index',$base_data->username)}}">Profile</a>
        <a class="p-3 text-dark chat-room-redirect" data-token="{{csrf_token()}}" data-url="{{route('finder.profile.get-first-acc',$base_data->username)}}" href="#">Chat room</a>
    </nav>
    <a class="fa fa-user pr-2 text-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></a>
    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{route('finder.profile.change-password',$base_data->username)}}"><span class="fa fa-cog"></span> Change Password</a>
        <a class="dropdown-item" href="{{route('finder.profile.edit-account',$base_data->username)}}"><span class="fa fa-edit"></span> Edit My Account</a>
        <a class="dropdown-item" href="{{route('finder.audit.index',$base_data->username)}}"><span class="fa fa-table"></span> Activity Log</a>
        <a class="dropdown-item" href="{{route('finder.feedback.index',$base_data->username)}}"><span class="fa fa-send"></span> Send Feedback</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item logout_click" data-url="{{route("landing.home.logout")}}" data-guard="finder" data-model="Finder" data-id="{{$base_data->id}}" data-token="{{csrf_token()}}" href="#"><span class="fa fa-sign-out"></span> Log-out</a>
    </div>
</div>