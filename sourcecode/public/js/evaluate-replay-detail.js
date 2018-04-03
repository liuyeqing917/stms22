window.onload = function () {

	var oid = findurl('id');

	'use strict';
	common();

	getquestions ();
	//获取问题
	function getquestions () {
		Api.getEvaluateDetail({
			json:{
				id:oid
			},
			fn:function (zzarr) {
				console.log(zzarr)
				var score = 0;
				if(zzarr.base.length==0){

					uselayer(3,"数据为空",function () {
								history.go(-1);
					});
				}

				$('.question-content').children().remove();
				for(var i=0 ;i<zzarr.base.length;i++){

					$('.question-content').append('<div class=" type-model "style="border: none">'+zzarr.base[i].base+'</div>');

					var nametext = '姓名:'+zzarr.info[0].name;
					$('.username').text(nametext);
					for(var j=0; j<zzarr.info.length;j++){

						if(zzarr.base[i].id==zzarr.info[j].tid){
							score = Number(score)+  Number(zzarr.info[j].score) ;
							$('.question-content').append('<div class="type-model clearfix "><span class="smalltitle">'+zzarr.info[j].content+'</span> <span class="rightinput"> '+zzarr.info[j].scores+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; '+zzarr.info[j].score+'</span> </div>');
						}

					}

				}

				$('.score-data').text(score);
			}

		});
	}


	$(".evaluating_id").val(getCookie("id"));
	$(".token").val(getCookie("token"));



	$("body").on('change','.scoreinput',function(){

		this.value = this.value.replace(/[^\d]/g, '');


		var score = 0;
		for(var i=0; i<$('.scoreinput').length;i++){

			if($('.scoreinput').eq(i).val()){
				score = Number(score)+  Number($('.scoreinput').eq(i).val()) ;
			}

		}
		$('.score-data').text(score);


	})













};














