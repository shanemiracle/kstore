  // 渲染部门页面
     $.post('/api/apiQuaDepartGet', function(res) {
         var str = ''
         if (res.ret_code == 0) {
             var retData = res.data,
                 len = retData.length;
             for (i = 0; i < len; i++) {
                 str += '<option>' + retData[i].depart_name + '</option>'
             }
             $('.select-box .select').html(str);
         } else {
             layer.msg(res.ret_desc);
         }
     })
 


 function editInfo(api){
    var param={};
    param.id=getURLParameter('parent_id');
    // console.log(param);

    $.post('/api/apiQuaTreeInfoGet',param,function(res){
        if(res.ret_code==0){
            var retData = res.data[0];
	        for(var key in retData){
	            $('#' + key).val(retData[key]);
	        }
        // $(".select-box select").find("option[text='"+retData['depart']+"']").attr("selected",true);
           
        }else{
            layer.alert(res.ret_desc);
        }
    }) 
 }
 editInfo();
var j ='';
$('.submit-update').on('click',function(){
    
    var i = getURLParameter('parent_id').substring(0, 1);
    // updateSubmit('/api/apiQuaTree1Update');
    alert(i);
    j = 3;
    if(j!==null&&j!==undefined){
        switch(j){
            case 1: 
            updateSubmit('/api/apiQuaTree1Update');
            break;
            case 3: 
            updateSubmit('/api/apiQuaTree2Update');
            break;
            case 4: 
            updateSubmit('/api/apiQuaTree3Update');  
            break;
            case 5: 
            updateSubmit('/api/apiQuaTree1RecUpdate');
            break;
            case 6: 
            updateSubmit('/api/apiQuaTree2RecUpdate');
            break;  
            case 7: 
            updateSubmit('/api/apiQuaTree3RecUpdate');
            break;  
            default:
            '更新失败'    
        }
    }
    // updateSubmit(api);
})

function  updateSubmit(api){
    // var departValue = $('.select-box option:selected').text();
    alert(121212);
    var param ={
         id: getURLParameter('parent_id'),
         en_name: $('#en_name').val(),
         cn_name: $('#cn_name').val(),
         remark: $('#level_remark').val(),
         create_user: 'coco'
    }
	$.post(api, param, function(res){
		if(res.ret_code==0){
          layer.msg('修改成功')
          var index = parent.layer.getFrameIndex(window.name);
          parent.layer.close(index);

		}else{
			layer.msg(res.ret_desc);
		}
	})
}

 function getURLParameter(name) {
     return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
 }