window.onload = function () {
	'use strict';
	common();
	var otype = findurl('type');

	var dtype =0;
	if(otype==1){
		dtype=2;
		$('.user-typespan').text("学员");
		$('.usersure').text('教师签字');

	}else if(otype==2){
		dtype=1;
		$('.user-typespan').text("教师");
		$('.usersure').text("学员签字");
	}else{
		dtype=3;
		$('.user-typespan').text("教师");
		$('.usersure').text("教师签字");

	}

	var oList = $('.outline-out-list')[0];
	
	togetfatherdepart(function (arr) {
		writesel('','.sel-fatherdepart',arr,1,function () {
			togetchilddepart($('.sel-fatherdepart').val(),function (arr) {
				writesel('全部科室','.sel-childdepart',arr,1,function () {
					getusernews();

				});
			});
		});
	});


	function  getusernews () {

		Api.getStudents({
			json:{
				dtype:dtype,
				pid:$(".sel-fatherdepart").val(),
				depid:$(".sel-childdepart").val()
			},
			fn:function (arr) {
				console.log(arr)
				$('.users').children().remove();
				$('.users').append('<option value="0">全部</option>');
				for(var i=0 ;i<arr.length;i++){
					$('.users').append('<option value="'+arr[i].id+'">'+arr[i].name+'</option>');
				}


				if(arr.length!=0){
					$('.promit-message').addClass('none');
					$('.bgmodel').removeClass('none');
				}else{

					$('.bgmodel').addClass('none');
					$('.promit-message').removeClass('none');
				}
				togetlist();

			}

		});

	}



	$('.users').change(function () {
		togetlist();
	});
	
	function togetlist() {
		oList.showpage = false;
		oList.page=1;			
		getlist();		
	};

	function getlist() {
		
		Api.getEvaluateList({
			json:{
				evaluated_id:$('.users').val(),
				pid:$('.sel-fatherdepart').val(),
				depid:$('.sel-childdepart').val(),
				dtype:dtype,
				pagecount:Api.perpage,
				page:oList.page
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
		
		var datetiame ='';
		for (var i = 0 ; i < arr.length; i++) {

			var	_str = '签字';

			datetiame = timetodate(arr[i].strattime,1) +' ~ ' +  timetodate(arr[i].endtime,1);
			var str =
				'<li class="clearfix line-div">'
				+'<div class="w90 bl">'+arr[i].uname+'</div>'
				+'<div class="w130">'+arr[i].dname+'</div>'
				+'<div class="w220">'+datetiame+'</div>'
				+'<div class="w90">'+arr[i].scores+'</div>'
				+'<div class="w130">'+arr[i].name+'</div>'
				+'<input type="hidden"  value="'+arr[i].id+'" name="token" />'
				+'</li>';
			$('.outline-out-list').append(str);

		}

		if($('.sel-childdepart').val()!=0&&$('.users').val()!=0){
			$('.addlesson').removeClass('none');
		}else{

			$('.users').removeClass('none');
			$('.addlesson').addClass('none');
		}

	}


	$('.addlesson').click(function () {

		Api.getEvaluateStatistic({
			json:{
				role:$('.users').val(),
				depid:$('.sel-childdepart').val()

			},
			fn:function (json) {
				console.log(json);
				var arr1 = [];
				var arr2 = [];

				for (var i=0 ; i<json.length;i++){
					arr1.push(json[i].base);
					arr2.push(json[i].score);
				}
				$('.statistics').removeClass('none');


				writediagram($('.sel-childdepart').find("option:selected").html(),'综合分值',	$('.users').find("option:selected").html(),'得分',arr1,arr2)

			}

		});

	});

		$("body").on('click','.line-div',function(){

			var url = 'evaluate-replay-detail.html?id='+$(this).children('input').val();

			window.location.href=url;



		})








};













