@extends ('Landing.layout.main')

@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="card box_shad" style="width:100%;">
            <div class="card-body">
                <div class="profile_content">
                    <h1>Finder chat! </h1><hr>
                    <div class="row">
                        <div class="col-lg-3">
                            <h5>New Dating app for all singles! </h5>
                        </div>
                        <div class="col-lg-7">
                            <form action="{{route('landing.home.login-save')}}" class="global-form-submit">
                                {{ csrf_field() }} 
                                <div class="form-group">
                                    <label for="email_username">Email/User Name</label>
                                    <input type="text" class="form-control" name="email_username" placeholder="Enter here">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter password">
                                </div>
                                <div class="form-group pull-right">
                                    <button type="submit" class="btn btn-secondary global-form-submit-btn" name="sbmt">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection