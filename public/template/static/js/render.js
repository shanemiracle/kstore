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
 
       //文件上传功能
     $("#fileUploadContent").initUpload({
         "uploadUrl": "/api/apiFileUp", //上传文件信息地址
         //"size":350,//文件大小限制，单位kb,默认不限制
         //"maxFileNumber":3,//文件个数限制，为整数
         //"filelSavePath":"",//文件上传地址，后台设置的根目录
         "beforeUpload": beforeUploadFun, //在上传前执行的函数
         "onUpload": afterUpload, //在上传后执行的函数
         "ismultiple": false, //是否允许上传多个
         "scheduleStandard": true,
         autoCommit:false,//文件是否自动上传
         "fileType": ['xlsx', 'png', 'jpg', 'docx', 'doc'] //文件类型限制，默认不限制，注意写的是文件后缀
     })


     function editInfo(api){
        var param={};
        param.id=getURLParameter('parent_id');
        $.post('/api/apiQuaTreeInfoGet',param,function(res){
            if(res.ret_code==0){
                var retData = res.data[0];

    	        for(var key in retData){
    	            $('#' + key).val(retData[key]);
    	        }
            $(".select-box select").find("option").val(retData['depart']).attr("selected",true);
               
            }else{
                layer.msg(res.ret_desc);
            }
        }) 
    }
    editInfo();

    $('.submit-update').on('click',function(){
        var i = getURLParameter('parent_id').substring(0, 1);

        if(i!==null&&i!==undefined){
            switch(i){
                case '1': 
                updateSubmit('/api/apiQuaTree1Update');
                break;
                case '3': 
                updateSubmit('/api/apiQuaTree2Update');
                break;
                case '4': 
                updateSubmit('/api/apiQuaTree3Update');  
                break;
                case '5': 
                updateSubmit('/api/apiQuaTree1RecUpdate');
                break;
                case '6': 
                updateSubmit('/api/apiQuaTree2RecUpdate');
                break;  
                case '7': 
                updateSubmit('/api/apiQuaTree3RecUpdate');
                break;  
                default:
                console.log('更新失败');  
            }
        }
    })

    function  updateSubmit(api){
        var departValue = $('.select-box option:selected').text();
        var param ={
             id: getURLParameter('parent_id'),
             en_name: $('#en_name').val(),
             cn_name: $('#cn_name').val(),
             remark: $('#level_remark').val(),
             create_user: 'coco',
             depart:departValue,
             address: flags.address,
             size: flags.size
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

 function afterUpload(data, opt) {
     console.log(opt);
     flags = opt
     return flags;
 }

 function beforeUploadFun(opt) {

     opt.otherData = [{ "name": "name", "value": "zxm" }];

 }

 function onUploadFun(opt, data) {

     // alert(data);
     uploadTools.uploadError(opt); //显示上传错误
     uploadTools.uploadSuccess(opt); //显示上传成功
 }


 function testUpload() {

     var opt = uploadTools.getOpt("fileUploadContent");
     uploadEvent.uploadFileEvent(opt);
 }

 function getURLParameter(name) {
     return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
 }