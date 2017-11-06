$(function() {
    //实现搜索功能
    var to = false;
    var parent_id = '';
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
        bLengthChange:false,
        searching:true,
        processing:true,
        aaSorting:[
            [0,'asc']
        ],
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
                // types:{
                //     "default" : {
                //       "icon" : "icon iconfont icon-loucengyi"
                //   } 
                // },
                // "state": { "key": "state_demo" },
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

        fileListShow();
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
            title: '创建2一层页面',
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
    })


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
            title: '更新第一层',
            area: ['600px', '420px'],
            content: '/tree/editfirst?parent_id=' + parent_id

        });
    });


    //文件列表展示
    function fileListShow() {
        // console.log("redraw file list");
        id = parent_id;

        console.log(typeof(id));

        // alert(id.substring(0, 1));

        var url = '/api/apiQuaTreeFileListGet?id=' + id;
        if(id!==2){
          table.ajax.url(url).load(null,false); 
        }else{
            alert(2);
            $(".table-show").hide();
        }
  

        // $.post('/api/apiQuaTreeFileListGet?id=' + id, function(res) {
        //     var str = '',
        //         len = res.data.length,
        //         ret = res.data;
        //     for (var i = 0; i < len; i++) {
        //         str += "<tr class='text-c c-666'>" +
        //             "<td>" + "<a href='" + ret[i].address + "'" + "download='" + ret[i].file_name + "'>" + ret[i].file_name + "</a>" + "</td>" +
        //             "<td>" + ret[i].depart + "</td>" +
        //             "<td>" + ret[i].create_time + "</td>" +
        //             "<td>" + ret[i].remark + "</td>" +
        //             "</tr>"
        //     }
        //     $('.table-content').html(str);

        // }
        // )
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