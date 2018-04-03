window.onload = function () {

	var otype = findurl('type');
	
	if(otype=='student'){
		$('.childTitle').text("教师评价");
		$('.user-typespan').text("教师");
		$('.usersure').text("教师签字");

	}




	'use strict';
	common();
	togetfatherdepart(function (arr) {
		writesel('','.sel-fatherdepart',arr,1,function () {
			togetchilddepart($('.sel-fatherdepart').val(),function (arr) {
				writesel('','.sel-childdepart',arr,1,function () {
					getusernews();
				});
			});
		});
	});



		function  getusernews () {

				Api.getStudents({
					json:{
						depid:$(".sel-childdepart").val(),
						dtype:0
					},
					fn:function (arr) {
						console.log(arr.length);

						$('.users').children().remove();
						for(var i=0 ;i<arr.length;i++){
							$('.users').append('<option value="'+arr[i].id+'">'+arr[i].name+'</option>');
						}


						if(arr.length!=0){

							$('.bgmodel').removeClass('none');
							$('.score-data').text(0);

							getquestions ();

						}else{
							$('.bgmodel').addClass('none');

							$('.users').append('<option value="0">暂无</option>');
							$('.tishi-message').html('请选择有用户的科室');
						}

					}

				});

		}


	//获取评价问题
	function getquestions () {
		Api.getEvaluateQuestions({
			json:{
				depid:$(".sel-childdepart").val()
			},
			fn:function (zzarr) {
				console.log(zzarr)

				if(zzarr.info.length==0){

					$('.bgmodel').addClass('none');

					return ;
				}



				$('.tishi-message').html('');

				$('.question-content').children().remove();
				$('.mainid').val(zzarr.info[0].id);
				for(var i=0 ;i<zzarr.base.length;i++){

					$('.question-content').append('<div class=" type-model "style="border: none">'+zzarr.base[i].base+'</div>');

					for(var j=0; j<zzarr.info.length;j++){

						if(zzarr.base[i].id==zzarr.info[j].tid){

							$('.question-content').append('<div class="type-model clearfix ">	<input type="hidden"  value="'+zzarr.info[j].cid+'" name="ids[]" /><span class="smalltitle">'+zzarr.info[j].content+'</span>  <span class="rightinput" grades="'+zzarr.info[j].score+'">分值:'+zzarr.info[j].score+'&nbsp; &nbsp;<input type="text" class="scoreinput form-control" maxlength="2" name="scores[]" /> </span> </div>');
						}

					}


				}
			}

		});
	}


	$(".evaluating_id").val(getCookie("id"));
	$(".token").val(getCookie("token"));


	$("body").on('keyup','.scoreinput',function(){

		this.value = this.value.replace(/[^\d]/g, '');


		if(parseInt($(this).val())>parseInt($(this).parent().attr("grades"))){

					$(this).val('');

					uselayer(3,"请填写比该题分值少的分值",function () {

					});
				}

		var score = 0;
		for(var i=0; i<$('.scoreinput').length;i++){


			if($('.scoreinput').eq(i).val()){
				score = Number(score)+  Number($('.scoreinput').eq(i).val()) ;
			}

		}
		$('.score-data').text(score);


	})


	$('.addbtn').click(function () {

		for(var i =0;i<$('.scoreinput').length;i++){
			if ($.trim($('.scoreinput').eq(i).val())=='') {

				layer.alert('请填写全部的分值');

				return false;
			}
		}
		var layernews ='是否确定要提交';

		var ilayer = layer.confirm(
			layernews,
			{btn:['确定','取消']},
			function () {
				layer.close(ilayer);
				surebtn();
			},
			function () {

				layer.close(ilayer);

			}
		);

	});


		function  surebtn () {

			var postData = $(".evaluateform").serialize();

			Api.adEvaluateScores({
				istoken:1,
				json:$(".evaluateform").serialize(),
				fn:function (json) {
				console.log(json);
					if (json.status==1) {
						uselayer(3,"新增成功，自动跳转下一个",function () {

						});


						$('.score-data').text(0);
						var modeluser = $(".users").val();
						for(var i=0; i<$('.scoreinput').length;i++) {

							$('.scoreinput').eq(i).val('');
						}

						for(var j=0; j<$('.users').children().length;j++){

							if($('.users').children().eq(j).val()==modeluser){
								$('.users').children().eq(j).remove();
								break;
							}
						}

						if($('.users').children().length==0){
							//$('.promit-message').removeClass('none');
							$('.bgmodel').addClass('none');
						}

					}else{
						uselayer(3,json.msg,function () {

						});
					}
				}
			});
		}


};














