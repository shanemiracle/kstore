$(function() {
  //实现搜索功能
    var to = false;
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
                // 'themes':{
                //     icons:false
                // }
            },
            // types:{
            //     "default" : {
            //       "icon" : "icon iconfont icon-loucengyi"
            //   } 
            // },
            // "state": { "key": "state_demo" },
            "plugins": ["sort", "state", "search", 'wholerow', "types"]
        });

        // $('#container').jstree.hide_icons();

    })
    //确定哪一层被选中
    // $('#container').on("changed.jstree", function(e, data) {
    //     if (null == data || null == data.selected[0]) {
    //         return;
    //     }

    //     var selectedeep = (data.selected[0]);
    //     var deep = selectedeep.substring(0, 1);
    //     $('.contents .filter').eq(deep).show().siblings().hide();
    // });
    $('#container').on('open_node.jstree', function(e, data) {
        // console.log(data.selected);

    })

    $('#container').on('select_node.jstree', function(e, data) {
        // console.log(data);
        console.log(data.selected);
          var selectedeep = (data.selected[0]);
        var deep = selectedeep.substring(0, 1);
        $('.contents .filter').eq(deep).show().siblings().hide();


    })

    $('.pop-one').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建第一层',
            area: ['700px', '480px'],
            content: '/tree/index'

        });
    })
    $('.edit-first').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '编辑第一层',
            area: ['480px', '320px'],
            content: '/tree/editfirst'

        });
    })
    $('.pop-two').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建页面',
            area: ['700px', '480px'],
            content: '/tree/popsecond'

        });
    })
    $('.pop-three').on('click', function() {
        var index = layer.open({
            type: 2,
            title: '创建页面',
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
});