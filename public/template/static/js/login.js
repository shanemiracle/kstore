

$(function(){
	$('.login-btn').on('click',function(){
            var param={
               user_name:$('.user-name').val(),
               pwd:$('.pwd').val()
            };
		$.post('api/apiUserLogin',param,fucntion(res){
			if(res.ret_code==0){
				location.href=
			}else{
				layer.msg(res.ret_code);
			}
		})
	})
})