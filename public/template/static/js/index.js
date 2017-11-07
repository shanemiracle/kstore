$(function() {
    //实现搜索功能
    var to = false;
    var parent_id = '';
    var parent_parent = '';
    var id = '';
    $('#searchMenu').keyup(function() {
        if (to) { clearTimeout(to); }
        to = setTimeout(function() {
            var v = $('#searchMenu').val();
            // console.log(v);
            $('#container').jstree(true).search(v);
        }, 250);
    });

    var table = $('#fileTable').DataTable({
        "fnInfoCallback":function (oSettings,iStart,iEnd,iMax,iTotal,sPre) {

        },
        "sScrollY":"600",
        "sScrollX":"100%",
        // bScrollInfinite:true,
        bLengthChange:false,
        searching:true,
        processing:true,
        aaSorting:[
            [0,'asc']
        ],
        paging:false,
        info:false,
        bStateSave:true,
        columns:[
            {
                // data:"file_name"
                "sClass":"text-center",
                // "data":{"data":"address"},
                "data":{"data":""},
                "render":function (data,type,full,meta) {
                    // console.log(data.address);
                    // console.log(data.file_name);
                    return "<a href='" + data.address + "'" + "download='" + data.file_name + "'>" + data.file_name+ "</a>"
                }
            },
            {
                data:"file_ver"
            },
            {
                data:"remark"
            },

            {
                data:"depart"
            },
            {
                data:"size"
            },
            {
                data:"create_user"
            },
            {
                data:"create_time"
            }
        ]

    });

    //树的渲染
    $.get('/api/apiQuaTreeListGet', function(res) {
        if (res.ret_code == 0) {
            $('#container').jstree({
                'core': {
                    'data': res.data
                },
                "plugins": ["sort", "state", "search", 'wholerow', "types"]
            });

        } else {
            layer.msg(res.ret_desc);
        }
    });

    //确定选择的节点和对应按钮的展示
    $('#container').on('select_node.jstree', function(e, data) {
        console.log(data.selected);
        parent_id = data.selected;
        var selectedeep = (data.selected[0]);
        var deep = selectedeep.substring(0, 1);
        $('.contents .filter').eq(deep).show().siblings().hide();

        parent_parent = data.node.parent;

        fileListShow();
    });

    // 树的删除
    $('.del-tree').on('click',function(){
        var index =layer.confirm('你确定要删除吗',{
            btn:['是','否'],
            shade: 0,
            icon:3,
            end:function () {
                // $('#container').jstree(true).select(parent_id);
                // $('#container').jstree(true).select_node(parent_id);
                redraw();
                fileListShow();
            }
        },function(){

          var param={};
          param.id= parent_id[0];
          $.post('/api/apiQuaTreeDelete',param,function(res){
            if(res.ret_code==0){
               layer.msg('删除成功');
                parent_id = parent_parent;

            }else{
              layer.msg(res.ret_desc);
            }
          });

            layer.close(index);
        },function(){
            layer.close(index);
        })

    });

      //下一层文件弹窗
    $('.pop-one').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建下一层页面',
            area: ['720px', '510px'],
            content: '/tree/index?parent_id=' + parent_id,
            end:function(){
                redraw();
            }
        });
    });
  
    $('.pop-two').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建下一层页面',
            area: ['560px', '320px'],
            content: '/tree/popsecond?parent_id=' + parent_id,
            end:function(){
                redraw();
            }
        });
    });

    $('.pop-three').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建下一层页面',
            area: ['720px', '510px'],
            content: '/tree/popthird?parent_id=' + parent_id,
            end:function(){
                redraw();
            }

        });
    });
    $('.pop-four').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建下一层页面',
            area: ['720px', '510px'],
            content: '/tree/popfourth?parent_id=' + parent_id,
            end:function(){
                redraw();
            }
        });
    });


      //记录文件弹窗
    $('.note-one').on('click',function(){
        var index = layer.open({
            type: 2,
            title: '创建记录页面',
            area: ['720px', '510px'],
            content: '/tree/recordOne?parent_id=' + parent_id,
            end:function(){
                redraw();
            }
        });

    });
    $('.note-two').on('click',function(){
        var index = layer.open({
            type: 2,
            title: '创建记录页面',
            area: ['720px', '510px'],
            content: '/tree/recordTwo?parent_id=' + parent_id,
            end:function(){
                redraw();
            }
        });

    });
    $('.note-three').on('click',function(){
        var index = layer.open({
            type: 2,
            title: '创建记录页面',
            area: ['720px', '510px'],
            content: '/tree/recordThree?parent_id=' + parent_id,
            end:function(){
                redraw();
            }
        });

    });
   
    //文件更新
    $('.update-file').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '文件版本更新',
            area: ['700px', '560px'],
            content: '/tree/editfirst?parent_id=' + parent_id,
            end:function () {
                redraw();
            }

        });
    });


   //分段描述修改(即是1.5层)
    $('.sec-descibe').on('click',function(){
        // alert(1);
        var index = layer.open({
            type: 2,
            title: '分段描述的修改',
            area: ['480px', '270px'],
            content: '/tree/decSection?parent_id=' + parent_id

        });
    });


    //文件列表展示
    function fileListShow() {
        id = parent_id;

        var url = '/api/apiQuaTreeFileListGet?id=' + id;
        var id_r = new Array();
        id_r = id[0].split("-");
        if(id_r.length != 2){
            console.log("错误id"+id);
        }
        if(id_r[0] != "0" && id_r[0] != "2"){
            $(".table-show").show();
          table.ajax.url(url).load(null,false); 
        }else{
            // console.log("隐藏");
            $(".table-show").hide();
        }

    }

    function redraw(){
        $.get('/api/apiQuaTreeListGet', function(res) {
         if (res.ret_code == 0) {
             $('#container').jstree(true).settings.core.data=res.data;
             $('#container').jstree(true).refresh();

         } else {
             layer.msg(res.ret_desc);
         }
    });


  }




















});