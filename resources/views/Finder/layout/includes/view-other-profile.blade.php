<div class="row">
    <div class="card box_shad card-profile-container" style="width:100%">
        <div class="card-body">
            <div class="profile_content">
                <div class="row" style="margin-bottom:2%;">
                    <div class="col-lg-4">
                        <img src="{{$viewUser->image == null || $viewUser->image == '' ? URL::asset('public/css/assets/noimage.png') : URL::asset('storage/uploads/profile_images/user('.$viewUser->id.')/'.$viewUser->image)}}" alt="profile image" class="image_profile img-fluid">
                    </div>
                    <div class="col-lg-8">
                        <h2 style="margin-top:5%;">{{$viewUser->middlename == null || $viewUser->middlename == '' ? $viewUser->firstname.' '.$viewUser->lastname : $viewUser->firstname.' '.$viewUser->middlename.' '.$viewUser->lastname}}</h2>
                        <h5>{{$viewUser->family_member}}</h5><hr>
                        <h6><b>Email:</b> {{$viewUser->email}}</h6><hr>
                        <h6><b>Address: </b> {{$viewUser->address == null || $viewUser->address == '' ? 'Address not set.' : $viewUser->address}}</h6>
                        <h6><b>Contact Number: </b> {{$viewUser->contact_num != null || isset($viewUser->contact_num) ? '(+63)-'.$viewUser->contact_num : 'No Contact number'}}</h6>
                        <h6><b>Gender: </b> {{$viewUser->gender}}</h6>
                        <h6><b>Age: </b> {{date('Y') - $viewUser->birthdate}} years old</h6>
                        <button type="button" class="btn btn-secondary btn-send-message" data-id="{{$viewUser->id}}" data-token="{{csrf_token()}}" data-url="{{route('finder.profile.send-message',$viewUser->username)}}" style="margin-top:1%;"><span class="fa fa-send"></span> Send a message</button>
                    </div>
                </div><hr>
            </div>
        </div>
    </div>
</div>