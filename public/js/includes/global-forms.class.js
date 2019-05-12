var GlobalForm = {

    INIT: function(){
        this.EVENTS();
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