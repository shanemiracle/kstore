$(function() {
  //实现搜索功能
    var to = false;
    var parent_id ='';
    var  id = '';
    $('#searchMenu').keyup(function() {
        if (to) { clearTimeout(to); }
        to = setTimeout(function() {
            var v = $('#searchMenu').val();
            console.log(v);
            $('#container').jstree(true).search(v);
        }, 250);
    });

    //树的渲染
    $.get('/api/apiQuaTreeListGet', function(res) {
        console.log(res);
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
    })

    $('#container').on('select_node.jstree', function(e, data) {
        // console.log(data);
        console.log(data.selected);
           parent_id=data.selected;
        var selectedeep = (data.selected[0]);
        var deep = selectedeep.substring(0, 1);
        $('.contents .filter').eq(deep).show().siblings().hide();

        fileListShow();
    });
    

    $('.pop-one').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建第一层',
            area: ['720px', '510px'],
            content: '/tree/index?parent_id=' + parent_id
        });
    });

    $('.edit-first').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '编辑第一层',
            area: ['480px', '360px'],
            content: '/tree/editfirst'

        });
    })
    $('.pop-two').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建第二页面',
            area: ['720px', '510px'],
            content: '/tree/popsecond?parent_id=' +parent_id

        });
    })
    $('.pop-three').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建第三页面',
            area: ['700px', '420px'],
            content: '/tree/popthird'

        });
    })
    $('.pop-four').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建页面',
            area: ['700px', '420px'],
            content: '/tree/popfourth'

        });
    })

    
    //文件列表展示
    function fileListShow(){
        id=parent_id;
      
        $.post('/api/apiQuaTreeFileListGet?id='+ id ,  function(res){
          var str='',
              len=res.data.length,
              ret=res.data
          for(var i=0;i<len;i++){

            str+="<td>" + "<a href='" + ret[i].address+"'" + "download='"+ret[i].file_name+"'>" + ret[i].file_name +"</a>" +"</td>" +
                 "<td>" + ret[i].depart +"</td>" +
                 "<td>" + ret[i].create_time +"</td>"+
                 "<td>" + ret[i].remark +"</td>"
          }

          $('.table-content').html(str);

        })   
        
    }




















});