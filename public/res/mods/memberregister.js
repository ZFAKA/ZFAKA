layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var form = layui.form;

	$('.loadcode').on('click', function(event) {
		event.preventDefault();
		$(this).attr('src','/Captcha?t=register&n=' + Math.random());
	});

	form.verify({
		passwd: [/^[\S]{6,16}$/,'密码必须6到16位，除空格外的任意字符'],
		repasswd: function(value){
			var passwd = $('#L_pass').val();
			if (value!=passwd) {
				return '两次输入的密码不一致';
			}
		},
		vercode: [/^[0-9a-zA-Z]{4}$/,'图形验证码错误']
	});

	form.on('submit(register)', function(data){

		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: data.form.action,
			type: data.form.method,
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				location.pathname = '/member'
			} else {
				$('.loadcode').attr('src','/Captcha?t=register&n=' + Math.random());
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

	exports('memberregister',null)
});