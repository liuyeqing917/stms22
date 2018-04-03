window.onload = function () {
	'use strict';
	common();
	var timer = null;
	Api.Modelexamnews({
		json:{
			userid:getCookie('id')
		},
		fn:function (json) {
			console.log(json);
			
			if (json.code==1||json.code==3||json.code==5) {
				$('.online-start').attr('_id',json.data.id);
				$('.exam-time').attr('endtime',json.data.end);
				$('.exam-title').html(json.data.examname);				
			}
			
			if (json.code==5) {
				startexam();
				return false;
			}
			if (json.code==1||json.code==3) {
				switch (json.data.type){
					case 1:$('.online-msg strong').eq(0).html('出科考试');
						break;
					case 2:$('.online-msg strong').eq(0).html('公共考试');
						break;
					case 3:$('.online-msg strong').eq(0).html('毕业考试');
						break;
				}
				
				$('.online-msg strong').eq(1).html(json.data.departname);
				$('.exam-time').html(timetodate(json.data.start)+'~'+timetodate(json.data.end));
				$('.online-msg strong').eq(3).html(json.data.examname);
				$('.online-msg strong').eq(4).html(json.data.teachername);
				$('.online-msg strong').eq(5).html(json.data.address);
				if (json.code==1) {
					if (json.data.nowtime>json.data.start&&json.data.nowtime<json.data.end) {
						$('.online-start').css('display','block');
					} else {
						$('.online-wait').html('不在考试时间内');
						$('.online-wait').css('display','block');
					}
				} else {
					$('.online-wait').html(json.msg);
					$('.online-wait').css('display','block');
				}
				$('.online-msg').css('display','block');
			} else {
				$('.no-exam').html(json.msg);
				$('.no-exam').css('display','block');
			}
		}
	});
	$('.online-start').click(startexam);
	
	function startexam() {
		$('.online-msg').css('display','none');
		$('.online-start').css('display','none');
		var aZimu = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N'];
		Api.StartExam({
			isrepeat:$('.online-start'),
			json:{
				id:$('.online-start').attr('_id'),
				userid:getCookie('id')
			},
			fn:function (json) {
				console.log(json);
				if (json.status!=1) {
					uselayer(3,json.msg);
					return false;
				}
				$('.time-left').attr('nowtime',json.nowtime);
				countdown();
				$('.exam-msg span').eq(1).html('考题数量：'+json.datas.length+'题');
				$('.exam-msg span').eq(2).html('考生姓名：'+codetostr(getCookie('n')));
				var _json = {};
				for (var i = 0 ; i < json.datas.length; i++) {
					if (!_json[json.datas[i].type]) {
						_json[json.datas[i].type]=[];
					}
					_json[json.datas[i].type].push(json.datas[i]);
				}
				$('.exam').css('display','block');
				for (var name in _json) {
					var arr = _json[name];
					var str = '';
					if (name=='1'||name=='2') {
						for (var i = 0 ; i < arr.length; i++) {
							var _str2 = '';
							var _arr = arr[i].content.replace(/\s/g,"").split(/\w\./g);
							for(var j = 0 ;j<_arr.length;j++){
							    if(_arr[j] == "" || typeof(_arr[j]) == "undefined"){
							        _arr.splice(j,1);
							        j= j-1;
							    }
							}								
							for (var j = 0 ; j< _arr.length; j++) {
								_str2+='<li><span>'+aZimu[j]+'</span>.'+_arr[j]+'</li>';
							}
							str+=
								'<div>'
									+'<p _id="'+arr[i].id+'" _type="'+arr[i].type+'"><span>'+(i+1)+'、'+arr[i].question+'</span><strong>（'+arr[i].score+'分）</strong></p>'
									+'<ul>'
										+_str2
									+'</ul>'
								+'</div>';								
						}						
					}
					if (name=='3') {
						for (var i = 0 ; i < arr.length; i++) {
							str+=
								'<div>'
									+'<p _id="'+arr[i].id+'" _type="'+arr[i].type+'"><span>'+(i+1)+'、'+arr[i].question+'</span><strong>（'+arr[i].score+'分）</strong></p>'
									+'<ul>'
										+'<li>正确</li>'
										+'<li>错误</li>'
									+'</ul>'
								+'</div>';								
						}							
					}
					if (name=='4') {
						for (var i = 0 ; i < arr.length; i++) {
							var _str2 = '';
							var _arr = arr[i].question.replace(/\s/g,"").match(/__/g);							
							for (var j = 0 ; j< _arr.length; j++) {
								_str2+='<li><input type="text" class="form-control" /></li>';
							}
							str+=
								'<div>'
									+'<p _id="'+arr[i].id+'" _type="'+arr[i].type+'"><span>'+(i+1)+'、'+arr[i].question+'</span><strong>（'+arr[i].score+'分）</strong></p>'
									+'<ul>'
										+_str2
									+'</ul>'
								+'</div>';								
						}							
					}
					if (name=='5'||name=='6'||name=='7') {
						for (var i = 0 ; i < arr.length; i++) {
							str+=
								'<div>'
									+'<p _id="'+arr[i].id+'" _type="'+arr[i].type+'"><span>'+(i+1)+'、'+arr[i].question+'</span><strong>（'+arr[i].score+'分）</strong></p>'
									+'<ul>'
										+'<li><textarea class="form-control"></textarea></li>'
									+'</ul>'
								+'</div>';								
						}
					}					
					switch (name){
						case '1':
							$('.exam-con').append('<div class="xuanze"><h4><span>选择题</span></h4>'+str+'</div>');
							break;
						case '2':
							$('.exam-con').append('<div class="duoxuan"><h4><span>多选题</span></h4>'+str+'</div>');
							break;
						case '3':
							$('.exam-con').append('<div class="panduan"><h4><span>判断题</span></h4>'+str+'</div>');
							break;
						case '4':
							$('.exam-con').append('<div class="tiankong"><h4><span>填空题</span></h4>'+str+'</div>');
							break;
						case '5':
							$('.exam-con').append('<div class="mingci"><h4><span>名词解释题</span></h4>'+str+'</div>');
							break;
						case '6':
							$('.exam-con').append('<div class="lunshu"><h4><span>论述题</span></h4>'+str+'</div>');
							break;
						case '7':
							$('.exam-con').append('<div class="jianda"><h4><span>简答题</span></h4>'+str+'</div>');
							break;
					}
				}
				$('.xuanze div,.panduan div').click(function (e) {
					if (e.target.tagName.toLowerCase()=='li') {
						$(e.target).parent().find('li').removeClass('active');
						$(e.target).addClass('active');
					}			
				});
				$('.duoxuan div').click(function (e) {
					if (e.target.tagName.toLowerCase()=='li') {
						if ($(e.target).hasClass('active')) {
							$(e.target).removeClass('active');
						} else {
							$(e.target).addClass('active');
						}
					}			
				});
				$('.exam-sub').css('display','block');
			}
		});	
	};

	
	$('.exam-sub').on('click',function () {
		var bFalse = false;
		$('.xuanze div').each(function () {
			if ($(this).find('li.active').length==0) {
				uselayer(3,'你还有选择题未完成');
				bFalse = true;
				$(this).focus();
				return false;
			}
		});
		if (bFalse) {return false;}
		$('.duoxuan div').each(function () {
			if ($(this).find('li.active').length==0) {
				uselayer(3,'你还有多选题未完成');
				bFalse = true;
				$(this).focus();
				return false;
			}
		});
		if (bFalse) {return false;}
		$('.panduan div').each(function () {
			if ($(this).find('li.active').length==0) {
				uselayer(3,'你还有判断题未完成');
				bFalse = true;
				$(this).focus();
				return false;
			}
		});
		if (bFalse) {return false;}
		
//		$('.tiankong div li input').each(function () {
//			if ($.trim($(this).val())=='') {
//				uselayer(3,'你还有填空题未完成');
//				bFalse = true;
//				return false;
//			}
//		});
//		if (bFalse) {return false;}
		
		var aId = [];
		var aAnswer = [];
		var aType = [];
		$('.xuanze div').each(function () {
			aId.push($(this).find('p').attr('_id'));
			aType.push($(this).find('p').attr('_type'));
			aAnswer.push($(this).find('li.active span').html());
		});		
		$('.duoxuan div').each(function () {
			var _arr = [];
			$(this).find('li.active span').each(function () {
				_arr.push($(this).html());
			});
			aId.push($(this).find('p').attr('_id'));
			aType.push($(this).find('p').attr('_type'));
			aAnswer.push(_arr.join(' '));
		});		
		$('.panduan div').each(function () {
			aId.push($(this).find('p').attr('_id'));
			aType.push($(this).find('p').attr('_type'));
			aAnswer.push($(this).find('li.active').html());
		});		
		$('.tiankong div').each(function () {
			var _arr = [];
			$(this).find('input').each(function () {
				_arr.push($.trim($(this).val()));
			});
			aId.push($(this).find('p').attr('_id'));
			aType.push($(this).find('p').attr('_type'));
			aAnswer.push(_arr.join(' '));
		});		
		$('.mingci div,.lunshu div,.jianda div').each(function () {
			aId.push($(this).find('p').attr('_id'));
			aType.push($(this).find('p').attr('_type'));
			aAnswer.push($.trim($(this).find('textarea').val()));
		});		
		uselayer(2,'请确认已答完所有题目，你确定交卷吗？',function () {
			Api.Addstudentresult({
				isrepeat:$('.exam-sub'),
				json:{
					logid:$('.online-start').attr('_id'),
					stuid:getCookie('id'),
					time:$('.time-left').attr('nowtime'),
					cid:aId,
					answer:aAnswer,
					type:aType
				},
				fn:function (json) {
					
					uselayer(1,json.msg,function () {
						if (json.status==1) {
							window.location.reload();
						}
						
					});
					
				}
			});
			
			
		});
	});
	
	
	function countdown() {
		var end = parseInt($('.exam-time').attr('endtime'));
		var now = parseInt($('.time-left').attr('nowtime'));
		startcountdown();
		timer = setInterval(startcountdown,1000);
		function startcountdown() {
			var time = end-now;
			var m = parseInt(time/60);
			var s = parseInt(time%60);
			$('.time-left').html('剩余时间：'+addzero(m)+'分钟');	
			$('.time-count').html(addzero(m)+':'+addzero(s));
			if (m==0) {
				$('.time-left').css('color','red');
				$('.time-count').css('color','red');
				$('.time-count').css('border-color','red');
			}
			if (m==0&&s==0) {
				clearInterval(timer);
				uselayer(1,'答题时间到');
				$('.exam-sub').off('click');
				$('.exam-sub').css('background','#ccc');
			}
			now++;
		};		
	};
	
	
};
			
			