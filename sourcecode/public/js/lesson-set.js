window.onload = function () {
'use strict';	
	common();
	var oList = $('.outline-out-list')[0];
	togetfatherdepart(function (arr) {
		writesel('全部方向科室','.sel-fatherdepart',arr,1,function () {
			togetchilddepart($('.sel-fatherdepart').val(),function (arr) {
				writesel('全部科室','.sel-childdepart',arr,1,function () {
					togetlist();
				});
			});
		});
	});	


	function togetlist() {
		oList.showpage = false;
		oList.page=1;			
		getlist();		
	};	
	
	function getlist() {
		$('.outline-in').css('display','none');
		$('.outline-out').css('display','block');
			Api.electiveSearchall({
				json:{
					stuid:0,
					type:0,
					fatherdepartment_id:$('.sel-fatherdepart').val(),
					childdepartment_id:$('.sel-childdepart').val(),
					page:oList.page,
					pagecount:Api.perpage
				},
				fn:function (json) {
					writelist(json,getlist);
				}
			});		
	};
	
	function writelist(json,fn) {
		console.log(json);
		var arr = json.data;
		flipover(json.allcount,Api.perpage,fn,oList);
				
		for (var i = 0 ; i < arr.length; i++) {
			var _str = '<a href="lesson-student.html?id='+arr[i].id+'" class="view">查看</a>';
			if (arr[i].ctime>arr[0].nowtime) {
				_str = '<a href="lesson-add.html?from=edit&id='+arr[i].id+'" class="edit">编辑</a>';
			}
			var str = 
				'<li class="clearfix">'
					+'<div class="w210 bl">'+timetodate(arr[i].ctime)+'</div>'
					+'<div class="w100">'+arr[i].cname+'</div>'
					+'<div class="w110">'+arr[i].departname+'</div>'
					+'<div class="w100">'+arr[i].venue+'</div>'
					+'<div class="w110">'+arr[i].teachername+'</div>'
					+'<div class="w90">'+_str+'</div>'						
				+'</li>';			
			$('.outline-out-list').append(str);
			
		}						
	};
	
	
	
	
	
	
};














