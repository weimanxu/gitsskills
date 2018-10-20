//分页点击事件
// (function() {
//     var pages = $("#pages").val();
//     var curr = $("#curr").val();
//     layui.use(['laypage', 'layer'], function() {
//         var laypage = layui.laypage,
//             layer = layui.layer;
//         laypage({
//             cont: 'page',
//             pages: pages,
//             curr: curr,
//             skip: true,
//             jump: function(e, first) {
//                 if (!first) {
//                     tool.setParam('page', e.curr);
//                 }
//             }
//         });
//     });
// })();