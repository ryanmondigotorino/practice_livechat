@extends('Finder.layout.main')

@section('pageCss')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="card box_shad mt-5" style="width:100%">
            <div class="card-body">
                <div class="profile_content">
                    <div class="row">
                        <div class="col-lg-10">
                            <h1 class="h2"><span class="fa fa-table"></span> My Activity Logs</h1>
                        </div>
                    </div><hr>
                    <div class="row mt-5">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="w-50 pr-1">
                                    <input type="text" class="dates form-control" name="dates">
                                </div>
                                <div>
                                    <button class="btn btn-secondary runSearch" type="button">Run</button>
                                    <button type="button" class="btn btn-secondary download-reports" data-open-dl="{{route('finder.audit.download-xlsx',$base_data->username)}}"><span class="fa fa-download"></span> Download Logs</button>
                                </div>
                            </div>
                        </div>
                    </div><hr>
                    <div class="row" style="margin-bottom:2%;">
                        <div class="col-lg-12">
                            <table class="table table-striped table_shad table-bordered table-hover global-table-audit" data-url="{{route('finder.audit.get-audit',$base_data->username)}}" data-loader="{{URL::asset("public/icons/loading.gif")}}">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                        <th>IP Address</th>
                                        <th>Device Used</th>
                                        <th>Browser Used</th>
                                        <th>OS Used</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div><hr>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('date-range-js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endsection

@section('pageJs')
<script>
    GlobalTable.AUDIT();
</script>
@endsection