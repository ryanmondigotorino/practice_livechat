var GlobalForm = {

    INIT: function(){
        this.EVENTS();
        this.LOGOUT();
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
            }).done(function(result){
                if(result['status'] == 'success'){
                    $(targetBtn).html('<span class="fa fa-check"></span> Please wait.. <i class="fa fa-spinner fa-pulse"></i>');
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
                    $(targetBtn).prop('disabled',false);
                    $(targetBtn).html('<span class="fa fa-edit"></span> Re-submit');
                    swal({
                        title: "Warning",
                        text: result['messages'],
                        icon: result['status'],
                        button: "Ok",
                    });
                }else{
                    $(targetBtn).prop('disabled',false);
                    $(targetBtn).html('<span class="fa fa-edit"></span> Re-submit');
                    swal({
                        title: "Error",
                        text: result['messages'],
                        icon: result['status'],
                        button: "Ok",
                    });
                }
            });
        });
    }
}