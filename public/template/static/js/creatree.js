 $(function() {
        //渲染部门数据 

        var createForm = {

            init: function() {

            },
            renderDepart: function() {
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
            },
            bindEvent:function(){

            }
        }
        createForm.init();

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
            //autoCommit:true,//文件是否自动上传
            "fileType": ['xlsx', 'png', 'jpg', 'docx', 'doc'] //文件类型限制，默认不限制，注意写的是文件后缀
        })

        // 表单提交submit 
        // $('.submit-info').on('click', function() {
        //     var parentId = getURLParameter('parent_id');
        //     var departValue = $('.select-box option:selected').text();
        //     var datas = {
        //         parent_id: parentId,
        //         en_name: $('#en_name').val(),
        //         cn_name: $('#cn_name').val(),
        //         remark: $('#file_descripe').val(),
        //         depart: departValue,
        //         create_user: 'coco',
        //         address: flags.address,
        //         size: flags.size
        //     }
        //     if (checkForm.valid()) {
        //         if (flags&&!flags.ret_code){
        //             $.post('/api/apiQuaTree1Add', datas, function(res) {
        //                 if (res.ret_code == 0) {
        //                     layer.msg('提交成功')
        //                     var index = parent.layer.getFrameIndex(window.name);
        //                     // history.go(-1); 
        //                     // location.reload();
        //                     parent.layer.close(index);
        //                     // $("#container").jstree(true).refresh();

        //                 } else {
        //                     layer.msg(res.ret_desc);
        //                 }
        //             })
        //         } else {
        //             layer.msg('请先将文件上传')
        //         }
        //     }

        // })

        $('.submit-info').on('click',formSubmit('/api/apiQuaTree1Add'))
        $('.creat-two').on('click',formSubmit('apiQuaTree2Add'))

    });
    function formSubmit(api){

        var parentId = getURLParameter('parent_id');
            var departValue = $('.select-box option:selected').text();
            var datas = {
                parent_id: parentId,
                en_name: $('#en_name').val(),
                cn_name: $('#cn_name').val(),
                remark: $('#file_descripe').val(),
                depart: departValue,
                create_user: 'coco',
                address: flags.address,
                size: flags.size
            }
            if (checkForm.valid()) {
                if (flags&&!flags.ret_code){
                    $.post(api, datas, function(res) {
                        if (res.ret_code == 0) {
                            layer.msg('提交成功')
                            var index = parent.layer.getFrameIndex(window.name);
                            // history.go(-1); 
                            // location.reload();
                            parent.layer.close(index);
                            // $("#container").jstree(true).refresh();

                        } else {
                            layer.msg(res.ret_desc);
                        }
                    })
                } else {
                    layer.msg('请先将文件上传')
                }
            }

        }

    var checkForm = $('#form_creat');
    checkForm.validate({
        rules: {
            cn_name: {
                remote: {
                    url: "/api/apiQuaTreeFileNameCheck",
                    type: "get",
                    dataType: 'json',
                    data: {
                        'cn_name': function() { return $('#cn_name').val(); }
                    }
                }
            }
        },
        messages: {

        }
    })
    var flags = '';

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