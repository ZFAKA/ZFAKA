
layui.define(['layer', 'table', 'element'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var element = layui.element;

	table.render({
		elem: '#question',
		url: '/member/help/ajax',
		page: true,
		cols: [[
			{field: 'title', title: '常见问题'},
			{field: 'addtime', title: '发布时间', width:160, templet: '#addtime',align:'center'},
			{title: '详情', width:120, templet: '#details', align:'center'}
		]]
	});
	exports('memberhelp',null)
});