@extends('Finder.layout.main')

@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="card box_shad mt-5" style="width:100%">
            <div class="card-body">
                <div class="profile_content">
                    <h1><span class="fa fa-edit"></span> Edit my account</h1><hr>
                    <form action="{{route('finder.profile.edit-account-save',$base_data->username)}}" class="global-form-submit">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" value="{{$base_data->firstname}}" placeholder="Enter First name" name="firstname">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" class="form-control" value="{{$base_data->middlename}}" placeholder="Enter Middle Name (Optional)" name="middlename">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" value="{{$base_data->lastname}}" placeholder="Enter Last name" name="lastname">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="birthdate">Birth Date</label>
                                <select name="birthdate" class="form-control">
                                    <option selected disabled>Choose..</option>
                                    @for($i = date('Y',strtotime('-18 years',time()));$i >= 1970; $i--)
                                        <option {{$base_data->birthdate == $i ? 'selected' : ''}} value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="contactnumber">Contact Number</label>
                                    <input type="text" class="form-control" value="0{{$base_data->contact_num}}" placeholder="Enter Contact Number" name="contact_number">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="address">Address</label>
                                <textarea name="address" rows="5" class="form-control" placeholder="Enter Address">{{$base_data->address}}</textarea>
                            </div>
                        </div><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-success pull-right global-form-submit-btn" type="submit"><span class="fa fa-edit"></span> Edit account</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection