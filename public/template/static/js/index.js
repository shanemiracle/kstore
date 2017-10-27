$(function() {
    $('#container').jstree({
        'core': {
            'data': [{
                "text": "Root node",
                "state": { "opened": true },
                "children": [{
                        "text": "Child node 1",
                        "state": { "selected": true },
                        "icon": "glyphicon glyphicon-flash"
                    },
                    { "text": "Child node 2", "state": { "disabled": true } }
                ]
            }]
        }
    });
    $('.create').on('click', function() {
        // alert(0);
        var index = layer.open({
            type: 2,
            // maxmin: true,
            title: '创建页面',
            area: ['700px', '420px'],
            content: '../../view/create.html'
            // end:function () {
            //     table1.ajax.reload(function () {
            //         countGen($('#login_id').val());
            //     },false);
            // }
        });
    })
});