window.onload = function () {
'use strict';	
	common();
	var oList = $('.outline-out-list')[0];
	togetfatherdepart(function (arr) {
		writesel('全部','.sel-fatherdepart',arr,1,function () {
			togetlist();
		});
	});	

	function togetlist() {
		oList.showpage = false;
		oList.page=1;			
		getlist();		
	};
	
	function getlist() {			
		Api.managedepart({
			json:{
				pid:$('.sel-fatherdepart').val(),
				department_id:0,
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
		

		if($('.sel-fatherdepart').val()!=0){

			$('.editdepart').removeClass('disnone');

		}else{
			$('.editdepart').addClass('disnone');
		}

		var fatherdeparturl = "depart-add.html?from=edit&type=father&pid=0&id="+$('.sel-fatherdepart').val();

		$('.editdepart').attr('href',fatherdeparturl);

		for (var i = 0 ; i < arr.length; i++) {
			//var _str = '<a href="lesson-student.html?id='+arr[i].id+'" class="view">查看</a>';
			//if (arr[i].ctime>arr[0].nowtime) {
			var _str = '<a href="depart-add.html?from=edit&type=child&pid='+arr[i].pid+'&id='+arr[i].id+'" class="edit">编辑</a>';
			//}

			var teachernames =" ";
			if (arr[i].tearchernames) {
				var dataArray = arr[i].tearchernames;
				for(var j=0; j<dataArray.length;j++){
					teachernames+= dataArray[j]+' ';
				}				
			}

			var str = 
				'<li class="clearfix">'
					+'<div class="w110 bl">'+arr[i].fatherdepart+'</div>'
					+'<div class="w110">'+arr[i].name+'</div>'
					+'<div class="w100">'+arr[i].guanliname+'</div>'
				+'<div class="w210">'+teachernames+'</div>'
					+'<div class="w90">'+_str+'</div>'
				+'</li>';			
			$('.outline-out-list').append(str);
			
		}						
	};
	
	
	
	
	
};














