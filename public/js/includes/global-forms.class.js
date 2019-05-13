var GlobalForm = {

    INIT: function(){
        this.LOGOUT();
        this.EVENTS();
        this.REDIRECTCHAT();
    },

    LOGOUT: function(){
        $('a.logout_click').on('click',function(){
            swal({
                title: "Confirmation",
                text: "Logout now?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((result) => {
                if(result){
                    var guard = $(this).attr('data-guard'),
                        model = $(this).attr('data-model'),
                        id = $(this).attr('data-id'),
                        token = $(this).attr('data-token'),
                        url = $(this).attr('data-url');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            guard: guard,
                            model: model,
                            id: id,
                            _token : token
                        },
                        success:function(result){
                            if(result == 'success'){
                                location.reload();
                            }
                        }
                    })
                }
            });
        });
    },

    EVENTS: function(){
        $('form.global-form-submit').on('submit',function(event){
            event.preventDefault();
            var url = $(this).attr('action');
            var targetBtn = $('button[type="submit"].global-form-submit-btn')
            $.ajax({
                type:'post',
                url: url,
                data: $(this).serialize(),
                beforeSend: function(){
                    $(targetBtn).prop('disabled',true);
                    $(targetBtn).html('<i class="fa fa-spinner fa-pulse"></i> Processing..');
                },
                success:function(result){
                    // console.log(result);
                    // return false;
                    if(result['status'] == 'success'){
                        if(result['url'] == 'none'){
                            location.reload();
                        }else{
                            swal({
                                title: "Success",
                                text: result['message'],
                                icon: result['status'],
                            }).then((confirm) => {
                                if(confirm){
                                    location.href = result['url'];
                                }
                            });
                        }
                    }else if(result['status'] == 'warning'){
                        swal({
                            title: "Warning",
                            text: result['messages'],
                            icon: result['status'],
                            button: "Ok",
                        });
                    }else{
                        swal({
                            title: "Error",
                            text: result['messages'],
                            icon: result['status'],
                            button: "Ok",
                        });
                    }
                }
            }).done(function(result){
                if(result['status'] == 'success'){
                    targetBtn.html('<span class="fa fa-check"></span> Success! Please wait <i class="fa fa-spinner fa-pulse"></i>');
                }else{
                    targetBtn.prop('disabled',false);
                    targetBtn.html('<span class="fa fa-edit"></span> Re-submit');
                }
            });
        });
    },

    REDIRECTCHAT: function(){
        $(document).ready(function(){
            $('#action_menu_btn').click(function(){
                $('.action_menu').show();
            });
            var user = $('li.side-lists');
            user.each(function(){
                if(window.location.href.includes($(this).attr('data-user'))){
                    $('li.side-lists.detail-'+$(this).attr('data-user')).addClass('active');
                }
            });
        });
        $('li.side-lists').on('click',function(){
            var url = $(this).attr('data-url');
            var user = $(this).attr('data-user');
            if(url.includes(user)){
                $('li.side-lists').removeClass('active');
                $('li.side-lists.detail-'+user).addClass('active');
            }
            location.href=url;
        });
        $('a.chat-room-redirect').on('click',function(){
            var url = $(this).attr('data-url')
                token = $(this).attr('data-token');
            $.ajax({
                type:'POST',
                url: url,
                data: {
                    _token: token
                },
                success:function(result){
                    location.href=result['route'];
                }
            })
        });
    },
    
    CHATFORM:function(){
        $('div.action_menu li.view-profile').on('click',function(){
            $('.action_menu').hide();
            window.open($(this).attr('data-url'));
        });
        var geturl = $('div.loaded-messages').attr('data-url');
        
        var firebaseConfig = {
            apiKey: "AIzaSyDhO9wmXNi3dabDvaRIVh5MhOdtFRDjewk",
            authDomain: "finder-chat-2bbf6.firebaseapp.com",
            databaseURL: "https://finder-chat-2bbf6.firebaseio.com",
            projectId: "finder-chat-2bbf6",
            storageBucket: "finder-chat-2bbf6.appspot.com",
            messagingSenderId: "846343839126",
            appId: "1:846343839126:web:9c6012c82b8610b3"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
    
        var rootRef = firebase.database().ref().child('chat-room-live');
        rootRef.on("child_added",snap => {
            $.ajax({
                type:'get',
                url: geturl,
                data: {
                    id:snap.val()['id']
                }
            }).done(function(result){
                $('input[type="hidden"][name="message_request_id"]').val(result['message_request_id'])
                if(result['messages_details']){
                    result['messages_details'].forEach(function($value,$key){
                        $('div.loaded-messages').append($value);
                    })
                }
            });
        });
        $('form.global-chat-form').on('submit',function(event){
            event.preventDefault();
            var url = $(this).attr('action');
            $.ajax({
                type:'get',
                url: url,
                data: $(this).serialize(),
                success:function(result){
                    $('input[type="text"][name="type_msg"].type_msg').val('');
                }
            })
        });
    },
}