@extends('Finder.layout.main')

@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="card box_shad mt-5" style="width:100%">
            <div class="card-body">
                <div class="profile_content">
                    <div class="row">
                        <div class="col-lg-10">
                            <h1 class="h2"><span class="fa fa-edit"></span> Change password</h1>
                        </div>
                    </div><hr>
                    <div class="row" style="margin-bottom:2%;">
                        <div class="col-lg-12">
                            <form action="{{route('finder.profile.change-password-submit',$base_data->username)}}" class="global-form-submit">
                                {{ csrf_field() }} 
                                <div class="form-group">
                                    <label for="oldpassword">Old password</label>
                                    <input type="password" class="form-control" name="old_password" placeholder="Enter old password">
                                </div><hr>
                                <div class="form-group">
                                    <label for="password">New password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter new password">
                                </div>
                                <div class="form-group">
                                    <label for="password">Confirm password</label>
                                    <input type="password" class="form-control" name="confirm_password" placeholder="Enter confirm password">
                                </div>
                                <div class="form-group pull-right">
                                    <button type="submit" class="btn btn-secondary global-form-submit-btn"><span class="fa fa-edit"></span> Change</button>
                                </div>
                            </form>
                        </div>
                    </div><hr>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection