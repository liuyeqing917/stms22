
window.onload = function () {
	'use strict';
	common();
	var oForm = findurl('from');
	var aZimu = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N'];
	Api.ExamDetail({
		json:{
			logid:findurl('logid'),
			userid:findurl('stuid')
		},
		fn:function (json) {
			console.log(json);
			
			$('.exam-title').html(json[0].examname);
			$('.exam-msg span').eq(0).html('考题数量：'+json.length+'题');
			$('.exam-msg span').eq(1).html('考生姓名：'+json[0].stuname);	
			$('.exam-sub').attr('_id',json[0].logid);
			$('.exam-sub').attr('_stuid',json[0].stuid);
			
			var _json = {};
			for (var i = 0 ; i < json.length; i++) {
				if (!_json[json[i].type]) {
					_json[json[i].type]=[];
				}
				_json[json[i].type].push(json[i]);
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
								+'<p _id="'+arr[i].cid+'" _type="'+arr[i].type+'"><span>'+(i+1)+'、'+arr[i].question+'</span><strong>（'+arr[i].score+'分）</strong></p>'
								+'<ul>'
									+'<li class="rightanswer">正确答案：'+arr[i].rightanswer+'</li>'
									+'<li class="answer break">学生答案：'+arr[i].answer+'</li>'
									+_str2
								+'</ul>'
//								+'<div class="score">得分：<input type="text" class="form-control" />分</div>'
							+'</div>';								
					}						
				}
				if (name=='3') {
					for (var i = 0 ; i < arr.length; i++) {
						str+=
							'<div>'
								+'<p _id="'+arr[i].cid+'" _type="'+arr[i].type+'"><span>'+(i+1)+'、'+arr[i].question+'</span><strong>（'+arr[i].score+'分）</strong></p>'
								+'<ul>'
									+'<li class="rightanswer">正确答案：'+arr[i].rightanswer+'</li>'
									+'<li class="answer break">学生答案：'+arr[i].answer+'</li>'
									+'<li>正确</li>'
									+'<li>错误</li>'
								+'</ul>'
//								+'<div class="score">得分：<input type="text" class="form-control" />分</div>'
							+'</div>';								
					}							
				}
				
				if (name=='4'||name=='5'||name=='6'||name=='7') {
					for (var i = 0 ; i < arr.length; i++) {
						str+=
							'<div>'
								+'<p _id="'+arr[i].cid+'" _type="'+arr[i].type+'"><span>'+(i+1)+'、'+arr[i].question+'</span><strong>（<i>'+arr[i].score+'</i>分）</strong></p>'
								+'<ul>'
									+'<li class="rightanswer">正确答案：'+arr[i].rightanswer+'</li>'
									+'<li class="answer break">学生答案：'+arr[i].answer+'</li>'
								+'</ul>'
								+'<h6 class="score">得分：<input type="text" class="form-control" disabled="disabled" value="'+(oForm=='edit'?'':arr[i].poins)+'" />分</h6>'
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
			
			
//			$('.xuanze div,.panduan div').click(function (e) {
//				if (e.target.tagName.toLowerCase()=='li') {
//					$(e.target).parent().find('li').removeClass('active');
//					$(e.target).addClass('active');
//				}			
//			});
//			$('.duoxuan div').click(function (e) {
//				if (e.target.tagName.toLowerCase()=='li') {
//					if ($(e.target).hasClass('active')) {
//						$(e.target).removeClass('active');
//					} else {
//						$(e.target).addClass('active');
//					}
//				}			
//			});
			
			if (oForm=='edit') {
				$('.exam-con .score input').removeAttr('disabled');
				$('.exam-sub').css('display','block');
			} else {
				$('.exam-cancel').css('display','block');
			}
			
            $(".exam-con .score input").keyup(function () {
                //如果输入非数字，则替换为''
                this.value = this.value.replace(/[^\d]/g, '');
            });	
			
		}
		
		
	});
	
	$('.exam-cancel').click(function () {
		history.go(-1);
	});

	$('.exam-sub').on('click',function () {
		var bFalse = false;

		$('.tiankong div,.mingci div,.lunshu div,.jianda div').each(function () {
			if ($.trim($(this).find('input').val())=='') {
				uselayer(3,'你还有题目未打分');
				$(this).find('input').focus();
				bFalse = true;
				return false;
			}
			if ($.trim($(this).find('input').val())>parseInt($(this).find('p i').html())) {
				uselayer(3,'打分超过了该题的最大分');
				$(this).find('input').focus();
				bFalse = true;
				return false;
			}
		});
		if (bFalse) {return false;}
		
		var aId = [];
		var aPoins = [];
		var aRight = [];
		
		$('.tiankong div,.mingci div,.lunshu div,.jianda div').each(function () {
			aId.push($(this).find('p').attr('_id'));
			aPoins.push($.trim($(this).find('input').val()));
			aRight.push('0');
		});		
		uselayer(2,'你确定批改吗？',function () {
			Api.ModifyResult({
				isrepeat:$('.exam-sub'),
				json:{
					logid:$('.exam-sub').attr('_id'),
					stuid:$('.exam-sub').attr('_stuid'),
					id:aId,
					poins:aPoins,
					isright:aRight
				},
				fn:function (json) {
					uselayer(1,json.msg,function () {
						if (json.status==1) {
							history.go(-1);
						}
						
					});
				}
			});
			
			
		});
	});

};

			
