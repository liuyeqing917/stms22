'use strict';
var ng = {
	url:Api.url,
	
	fourm:{
		getType:function (a,b,c) {ng.http(a,b,c,'/fourm/getType','get');},  //获取主题
		editType:function (a,b,c) {ng.http(a,b,c,'/fourm/editType','get');},  //新增修改主题
		deleteType:function (a,b,c) {ng.http(a,b,c,'/fourm/deleteType','get');},  //删除主题
		
		
		aa:''
	},
	
	
	
	
	
	
	
	http:function ($http,$cookies,options,url,type) {
		options.json=options.json||{};
		
		if (!options.istoken) {
			if ($cookies.get("token")) {
				options.json.token=$cookies.get("token");
			} else {
				ng.notoken();
				return false;
			}
		} 
		ng.shade = layer.load(1, {
			shade: [0.3,'#000'] //0.1透明度的白色背景
		});
		
		$http[type](ng.url+url,{
			params:options.json
		}).success(function (res) {
			layer.close(ng.shade);
			options.fn&&options.fn(res);
		}).error(function (res) {
			layer.close(ng.shade);
			console.log(res)
			alert('失败了')
		});		
		
		
	},
	
	notoken:function () {
		alert('token失效');
		
	}
	
	
};







