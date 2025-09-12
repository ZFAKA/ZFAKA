layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#login',
		url: '/'+ADMIN_DIR+'/logger/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'ip', title: '登录IP', minWidth:160},
			{field: 'addtime', title: '登录时间', width:200, templet: '#addtime',align:'center'}
		]]
	});


	exports('adminlog',null)
});