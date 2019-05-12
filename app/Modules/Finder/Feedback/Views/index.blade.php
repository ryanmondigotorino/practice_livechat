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
                            <h1 class="h2"><span class="fa fa-send"></span> Feedback</h1>
                        </div>
                    </div><hr>
                    <form action="{{route('finder.feedback.feedback-save',$base_data->username)}}" class="global-form-submit">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea name="content" rows="10" class="form-control" placeholder="Write your thoughts here.."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-secondary pull-right global-form-submit-btn" type="submit"><span class="fa fa-send"></span> Send Feedback</button>
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