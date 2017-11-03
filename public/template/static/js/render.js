 
 function editInfo(api){
    var param={};
    param.id=getURLParameter('parent_id');
    // console.log(param);

    $.post('/api/apiQuaTreeInfoGet',param,function(res){
        if(res.ret_code==0){
            var retData = res.data[0];
	        for(var key in retData){
	        	console.log(key);
	            $('#' + key).val(retData[key]);
	        }
           
        }else{
            layer.alert(res.ret_desc);
        }
    }) 
 }
 editInfo();




function  updateSubmit(){

	$.post(api,param,function(res){
		if(res.ret_code==0){

		}else{
			alert(res.ret_desc);
		}
	})

}






 function getURLParameter(name) {
     return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
 }