@extends('Finder.layout.main')

@section('pageCss')
<link rel="stylesheet" href="{{ URL::asset('public/css/chatbox.css') }} ">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
@endsection

@section('content')
<div class="profile_container">
    <div class="row justify-content-center h-100">
        <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
            <div class="card-header">
                <div class="input-group">
                    <input type="text" placeholder="Search..." name="" class="form-control search">
                    <div class="input-group-prepend">
                        <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
            <div class="card-body contacts_body">
                <ul class="contacts">
                    @if(isset($messages))
                        @foreach($messages['message_side'] as $key => $value)
                            <li class="detail-{{$value->username}} side-lists" data-user="{{$value->username}}" data-url="{{route('finder.chat-room.index',[$base_data->username,$value->username])}}">
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="{{URL::asset('storage/uploads/profile_images/user('.$value->finders_id.')/'.$value->image)}}" class="rounded-circle user_img">
                                        <span class="online_icon {{$value->account_line == '0' ? 'offline' : 'online'}}"></span>
                                    </div>
                                    <div class="user_info">
                                        <span>{{$value->firstname.' '.$value->lastname}}</span>
                                        <p>{{$value->account_line == '0' ? 'offline' : 'Online now'}}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="card-footer"></div>
        </div></div>
        <div class="col-md-8 col-xl-6 chat">
            <div class="card">
                @if(isset($messages))
                <div class="card-header msg_head">
                        <div class="d-flex bd-highlight">
                            <div class="img_cont">
                                <img src="{{URL::asset('storage/uploads/profile_images/user('.$messages['main_head'][0]->id.')/'.$messages['main_head'][0]->image)}}" class="rounded-circle user_img">
                                <span class="online_icon {{$messages['main_head'][0]->account_line == '0' ? 'offline' : 'online'}}"></span>
                            </div>
                            <div class="user_info">
                                <span>Chat with {{$messages['main_head'][0]->firstname.' '.$messages['main_head'][0]->lastname}}</span>
                                <p>1767 Messages</p>
                            </div>
                            <div class="video_cam">
                                <span><i class="fas fa-video"></i></span>
                                <span><i class="fas fa-phone"></i></span>
                            </div>
                        </div>
                        <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
                        <div class="action_menu">
                            <ul>
                                <li class="view-profile" data-url="{{route('finder.profile.view',$messages['main_head'][0]->username)}}"><i class="fas fa-user-circle"></i> View profile</li>
                                <li><i class="fas fa-users"></i> Add to close friends</li>
                                <li><i class="fas fa-plus"></i> Add to group</li>
                                <li><i class="fas fa-ban"></i> Block</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body msg_card_body">
                        <div class="loaded-messages" data-ref="{{App::environment() == 'local' ? 'chat-room' : 'chat-room-live'}}" data-url="{{route('finder.chat-room.send-chat',[$base_data->username,$messages['main_head'][0]->username])}}"></div>
                        {{-- <div class="loaded-messages-sub"></div> --}}
                    </div>
                    <div class="card-footer">
                        <form action="{{route('finder.chat-room.send-chat',[$base_data->username,$messages['main_head'][0]->username])}}" class="global-chat-form">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                                </div>
                                {{ csrf_field() }}
                                <input type="hidden" name="message_request_id" value="{{$messages['message_side'][0]->id}}">
                                <input type="text" name="type_msg" class="form-control type_msg" autocomplete="off" placeholder="Type your message...">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text send_btn"><i class="fas fa-location-arrow"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/3.1.0/firebase-database.js"></script>
<script>
    GlobalForm.CHATFORM();
</script>
@endsection