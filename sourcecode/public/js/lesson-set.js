window.onload = function () {
'use strict';	
	common();
	var oList = $('.tbody')[0];
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
				$('.tbody').empty()
		for (var i = 0 ; i < arr.length; i++) {
			var _str = '<a href="lesson-student.html?id='+arr[i].id+'" class="view">查看</a>';
			if (arr[i].ctime>arr[0].nowtime) {
				_str = '<a href="lesson-add.html?from=edit&id='+arr[i].id+'" class="edit">编辑</a>';
			}
			var str1 ='<tr>'+
			'<td>'+timetodate(arr[i].ctime)+'</td>'+
			'<td>'+arr[i].cname+'</td>'+
			'<td>'+arr[i].departname+'</td>'+
			'<td>'+arr[i].venue+'</td>'+
			'<td>'+arr[i].teachername+'</td>'+
			'<td>'+_str+'</td>'+
			'</tr>'
			$('.tbody').append(str1);
			
		}						
	};
	
	
	
	
	
	
};














