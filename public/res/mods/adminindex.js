layui.define(['jquery', 'layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var form = layui.form;

	function checkUpdate(automatic = false) {
		$.ajax({
            url: '/' + ADMIN_DIR + '/index/updatecheckajax',
            type: 'POST',
            data: {
                method: 'updatecheck',
                csrf_token: TOKEN
            },
            dataType: 'json',
            success: function(res) {
                if (res.code === 1) {
					if (res.data.update === 0) {
						if (!automatic) {
							layer.open({
								type: 1,
								title: '版本检查',
								area: ['400px', '150px'],
								shadeClose: true,
								content: '<div style="padding: 25px; text-align: center;">当前已是最新版本</div>',
								btn: ['知道了'], 
                            	btnAlign: 'c'
							});
						}
						return;
					} else {
                    	var allVersions = res.data.all_versions || [];
						var latestVersionSource = allVersions[0]?.source || 'github';
						var downloadUrl = latestVersionSource !== 'github' ? res.data.fallback_zip : res.data.zip;
                    	var popupContent = '';

                    	allVersions.forEach(function(version, index) {
                    	    var body = version.body
                    	        .replace(/\r\n|\n/g, '<br>') // 换行转HTML换行
                    	        .replace(/- (.*?)(<br>|$)/g, '• $1$2') // 列表项优化
                    	        .replace(/\# (.*?)(<br>|$)/g, '<strong class="layui-text-red">$1</strong>$2'); // 标题加粗标红

                    	    var sourceTip = version.source === 'github' 
                    	        ? '<span class="layui-badge layui-bg-blue">来源：GitHub</span>' 
                    	        : '<span class="layui-badge layui-bg-orange">来源：备用地址(无法连接到GitHub)</span>';

                    	    popupContent += `
                    	        <div style="margin-bottom: 18px; padding-bottom: 18px; border-bottom: 1px dashed #eee;">
                    	            <div style="display: flex; justify-content: space-between; align-items: center;">
                    	                <h4 style="margin: 0; font-size: 16px;">版本：${version.tag_name}</h4>
                    	                <div>${sourceTip} 发布时间：${version.published_at}</div>
                    	            </div>
                    	            <div style="margin-top: 10px; color: #666; line-height: 1.8;">${body}</div>
                    	        </div>
                    	    `;
                    	});

                    	layer.open({
                    	    type: 1,
                    	    title: '发现更新！',
                    	    area: ['850px', '650px'],
                    	    shadeClose: true,
                    	    content: `<div style="padding: 25px;">${popupContent}</div>`,
                    	    btn: ['自动更新', '下载ZIP包', '拒绝更新'], 
                    	    success: function(layero){
								  var btn = layero.find('.layui-layer-btn');
								  btn.find('.layui-layer-btn0').attr({
									href: '/' + ADMIN_DIR + '/upgrade'
									,target: '_blank'
								  });
								  btn.find('.layui-layer-btn1').attr({
									href: downloadUrl
									,target: '_blank'
								  });
							},
                    	    btnAlign: 'c'
                    	});
					}
                } else {
                    layer.msg(res.msg, {icon: 5, time: 2000});
                }
            },
            error: function(xhr) {
                layer.msg('检查更新失败（可能是网络或接口问题）', {icon: 5, time: 2000});
            }
        });
		return false;
	}
	
	form.on('submit(check)', function(data){
		checkUpdate(false);
		return false;
	});

	exports('adminindex', {
		checkUpdate: checkUpdate
	});
});