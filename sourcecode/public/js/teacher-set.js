window.onload = function () {
	'use strict';
	common();
	var oList = $('.outline-out-list')[0];
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
		for (var i = 0 ; i < arr.length; i++) {
			zzdata=arr[i];
			var	_str = '<a href="teacher-add.html?from=edit&id='+zzdata.id+'" class="edit">编辑</a>';

			if(zzdata.sex==1){
				var sex = "男"
			}else{
				sex = "女";
			}
			var str =
				'<li class="clearfix">'
				+'<div class="w130 bl">'+zzdata.stu_nu+'</div>'
				+'<div class="w100">'+zzdata.name+'</div>'
				+'<div class="w50">'+sex+'</div>'
				+'<div class="w130">'+zzdata.phone_number+'</div>'
				+'<div class="w220">'+zzdata.email+'</div>'
				+'<div class="w90">'+_str+'</div>'
				+'</li>';
			$('.outline-out-list').append(str);

		}
	};






};














