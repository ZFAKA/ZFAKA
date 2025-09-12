layui.define(['layer', 'table', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var form = layui.form;


	table.render({
		elem: '#table',
		url: '/'+ADMIN_DIR+'/order/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{type: 'checkbox', fixed: 'left'},
			{field: 'id', title: 'ID', width:80},
			{field: 'orderid', title: '订单号', minWidth:100},
			{field: 'email', title: '邮箱', minWidth:120},
			{field: 'productname', title: '商品', minWidth:100},
			{field: 'addtime', title: '时间', templet: '#addtime',minWidth:120},
			{field: 'status', title: '状态', width:80, templet: '#status',align:'center'},
			{field: 'paymoney', title: '支付金额',width:80},
			{field: 'number', title: '数量',width:80},
			{field: 'opt', title: '操作', templet: '#opt',align:'center',fixed: 'right', width: 160},
		]]
	});
    $('#deleteALL').on('click',function () {
        var checkStatus = table.checkStatus('table');
        var data=checkStatus.data;
		var ids=[];
		for(var i in data){
			ids.push(data[i].id);
		}
		if(ids.length>0){
			layer.confirm('确认删除选中订单吗？', function(index) {
				var param = {'ids': ids};
				$.ajax({
					url: '/'+ADMIN_DIR+'/order/delete',//请求的url地址
					dataType: 'json',//返回的格式为json
					data: {'id': JSON.stringify(param),'csrf_token':TOKEN},//参数值
					type: "POST"
				})
				.done(function (data) {
				if (data.code == 1) {
					layer.msg(data.msg, {icon: 1});
					location.reload();
				} else {
					layer.msg(data.msg, {icon: 2});
					}
				})
			})
		}else{
			layer.msg('请选中需要删除的订单',{icon: 2});
		}
	});
	form.on('submit(order-pay-button)', function(data){
		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/'+ADMIN_DIR+'/order/payajax/',
			type: 'POST',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				layer.open({
					title: '提示',
					content: '确认支付成功',
					btn: ['确定'],
					yes: function(index, layero){
					    location.href = '/'+ADMIN_DIR+'/order/view/?id='+data.field.id;
					},
					cancel: function(){ 
					    location.reload();
					}
				});
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
	
	form.on('submit(order-send-button)', function(data){
		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/'+ADMIN_DIR+'/order/sendajax/',
			type: 'POST',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				layer.open({
					title: '提示',
					content: '手工发货成功',
					btn: ['确定'],
					yes: function(index, layero){
					    location.href = '/'+ADMIN_DIR+'/order/view/?id='+data.field.id;
					},
					cancel: function(){ 
					    location.reload();
					}
				});
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
	
    form.on('submit(search)', function(data){
        table.reload('table', {
            url: '/'+ADMIN_DIR+'/order/ajax',
            where: data.field
        });
        return false;
    });

	
	exports('adminorder',null)
});