@extends ('Finder.layout.main')

@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    @include('Finder.layout.includes.profile-container')
    <div class="row">
        <div class="card box_shad mt-5" style="width:100%;">
            <div class="card-body">
                <div class="profile_content">
                    <div class="row" style="margin-top:2%;">
                        <div class="col-lg-12">
                            <h2><span class="fa fa-table"></span> About</h2><br>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <p><b>First Name</b></p>
                                    <p><b>Middle Name</b></p>
                                    <p><b>Last Name</b></p>
                                    <p><b>Contact Number</b></p>
                                </div>
                                <div class="col-lg-8">
                                    <p>: {{$base_data->firstname}}</p>
                                    <p>: {{$middlename == ' ' ? 'Middlename not set' : $middlename}}</p>
                                    <p>: {{$base_data->lastname}}</p>
                                    <p>: {{$base_data->contact_num != null || isset($base_data->contact_num) ? '(+63)-'.$base_data->contact_num : 'No Contact number'}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <p><b>Gender</b></p>
                                    <p><b>Email Address</b></p>
                                    <p><b>Date Joined</b></p>
                                    <p><b>Age</b></p>
                                </div>
                                <div class="col-lg-8">
                                    <p>: {{$base_data->gender}}</p>
                                    <p>: {{$base_data->email}}</p>
                                    <p>: {{date('M j Y',strtotime($base_data->created_at))}}</p>
                                    <p>: {{date('Y') - $base_data->birthdate}} years old</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Finder.layout.includes.profile-dp-modal-template')
@endsection

@section('pageJs')
<script src="{{URL::asset('public/js/includes/profile-image-upload.class.js')}}"></script>
<script>
    ProfileImageUpload.INIT();
</script>
@endsection