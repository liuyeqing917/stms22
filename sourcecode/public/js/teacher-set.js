window.onload = function () {
	'use strict';
	common();
	var oList = $('.tbody')[0];
	togetfatherdepart(function (arr) {
		writesel('全部','.sel-fatherdepart',arr,1,function () {
			togetchilddepart($('.sel-fatherdepart').val(),function (arr) {
				var zztext ='';
				if($('.sel-fatherdepart').val()==0){
					zztext='全部科室';
				}
				writesel(zztext,'.sel-childdepart',arr,1,function () {
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
		Api.teacherlist({
			json:{
				userid:0,
				pid:$('.sel-fatherdepart').val(),
				department_id:$('.sel-childdepart').val(),
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
		
		var index =0;
		var zzdata=[];
		if($('.sel-childdepart').val()==0&&$('.sel-fatherdepart').val()==0){
			index =1;
		}
		$('.tbody').empty();
		for (var i = 0 ; i < arr.length; i++) {
			zzdata=arr[i];
			var	_str = '<a href="teacher-add.html?from=edit&id='+zzdata.id+'" class="edit">编辑</a>';

			if(zzdata.sex==1){
				var sex = "男"
			}else{
				sex = "女";
			}
			var str1 ='<tr>'+
			'<td>'+zzdata.stu_nu+'</td>'+
			'<td>'+zzdata.name+'</td>'+
			'<td>'+sex+'</td>'+
			'<td>'+zzdata.phone_number+'</td>'+
			'<td>'+zzdata.email+'</td>'+
			'<td>'+_str+'</td>'+
			'</tr>'
			$('.tbody').append(str1);

		}
	};






};














