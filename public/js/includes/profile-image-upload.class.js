var ProfileImageUpload = {

    INIT: function(){
        this.EVENTS();
    },

    EVENTS: function(){
        $('button[type="button"].dp_btn').on('click',function(){
            $(".uploadImage").click();
            $(".uploadImage").on('change', function(){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#profile-picture').attr('src',e.target.result);
                }
                if($(".uploadImage")[0]['files'][0]){
                    reader.readAsDataURL($(".uploadImage")[0]['files'][0]);
                    $('.dp_save').removeClass('d-none');
                    $('.dp_save').on('click',function(){
                        var formData = new FormData();
                        var image = $(".uploadImage")[0]['files'][0];
                        var id = $(".uploadImage").attr('data-id');
                        var token = $(".uploadImage").attr('data-token');
                        formData.append('image_profile',image);
                        formData.append('id',id);
                        formData.append('_token',token);
                        var url = $('button[type="button"].dp_btn').attr('data-url');
                        $.ajax({
                            type:'POST',
                            url: url,
                            data: formData,
                            contentType: false,
                            processData: false,
                            beforeSend:function(){
                                $(".dp_save").prop('disabled',true);
                                $(".dp_save").html('<i class="fa fa-pulse fa-spinner"></i> Processing..')
                            },
                            success:function(result){
                                if(result['status'] == 'error'){
                                    swal({
                                        type: 'error',
                                        html: result['msg'],
                                        title: 'Error'
                                    });
                                    $(".dp_save").html('<i class="fa fa-save"></i> Upload');
                                }else{
                                    $(".dp_save").html('<i class="fa fa-check"></i> Success.. Please wait');
                                    location.reload();
                                }
                            }
                        });
                    });
                }
            });
        });
    },
};