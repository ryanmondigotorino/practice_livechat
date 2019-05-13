@extends ('Finder.layout.main')

@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    @include('Finder.layout.includes.view-other-profile')
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
                                    <p>: {{$viewUser->firstname}}</p>
                                    <p>: {{$viewUser->middlename == ' ' ? 'Middlename not set' : $viewUser->middlename}}</p>
                                    <p>: {{$viewUser->lastname}}</p>
                                    <p>: {{$viewUser->contact_num != null || isset($viewUser->contact_num) ? '(+63)-'.$viewUser->contact_num : 'No Contact number'}}</p>
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
                                    <p>: {{$viewUser->gender}}</p>
                                    <p>: {{$viewUser->email}}</p>
                                    <p>: {{date('M j Y',strtotime($viewUser->created_at))}}</p>
                                    <p>: {{date('Y') - $viewUser->birthdate}} years old</p>
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
<script>
    $('button[type="button"].btn-send-message').on('click',function(){
        var url = $(this).attr('data-url'),
            id = $(this).attr('data-id'),
            token = $(this).attr('data-token');
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                id: id,
                _token: token
            },
            success: function(result){
                console.log(result);
                if(result['status'] == 'success'){
                    location.href=result['url'];
                }
            }
        });
    });
</script>
@endsection