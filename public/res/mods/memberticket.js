layui.define(['layer', 'table', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var form = layui.form;

	form.verify({
		passwd: [/^[\S]{6,16}$/,'密码必须6到16位，除空格外的任意字符'],
		qq:function(value){
			if (value != ''&& !/^[1-9][0-9]{5,10}$/.test(value)) {
				return '请输入正确的QQ号码';
			}
		}
	});

	form.on('submit(ticket)', function(data){

		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/member/ticket/addajax',
			type: 'post',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				layer.msg(res.msg,{icon:1,time:5000});
			} else {
				layer.msg(res.msg,{icon:2,time:5000});
			}
		})
		.fail(function() {
			layer.msg('服务器连接失败，请联系管理员',{icon:2,time:5000});
		})
		.always(function() {
			layer.close(i);
		});

		return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
	});


	table.render({
		elem: '#ticketlist',
		url: '/member/ticket/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'subject', title: '标题'},
			{field: 'addtime', title: '添加时间', width:160, templet: '#addtime',align:'center'},
			{title: '详情', width:100, templet: '#details', align:'center'}
		]]
	});


	exports('memberticket',null)
});