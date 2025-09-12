layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#table',
		url: '/'+ADMIN_DIR+'/user/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'email', title: '邮箱', minWidth:160},
			{field: 'qq', title: 'QQ', minWidth:160},
			{field: 'createtime', title: '注册时间', width:200, templet: '#createtime',align:'center'},
			{field: 'opt', title: '操作', width:200, templet: '#opt',align:'center'}
		]]
	});


	exports('adminuser',null)
});