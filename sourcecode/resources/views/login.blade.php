<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="{{asset('resources/views/style/css/ch-ui.admin.css')}}">
	<link rel="stylesheet" href="{{asset('resources/views/style/font/css/font-awesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/css/bootstrap.min.css')}}">
	<script src="{{asset('public/js/jquery-1.12.0.min.js')}}"></script>
</head>
		<script >
			$(function(){
		// 导入项目
		function IsPC()
		{
		var userAgentInfo = navigator.userAgent;
		var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
		var flag = true;
		for (var v = 0; v < Agents.length; v++) {
		if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }
		}
		if(flag){
		$('#import-project-form').css('display', 'block');
		$(".projectOut").css("display","block");
		}else{
		$('#import-project-form').css('display', 'none');
		$(".projectOut").css("display","none");
		}
		}
		IsPC()

		$('.import-project-btn').click(function() {
		$('.import-project-btn-real').click();
		});
		$('.import-project-btn-real').change(function() {
		var filePath = $('[name=upload-file]').val();
		var fileExtArr = filePath.split('.');
		var fileExt = fileExtArr.pop();

		if (fileExt != 'xlsx' && fileExt != 'xls') {
		//layer.msg('上传文件不是excel！');
		layer.alert('上传文件不是excel！', {
		skin: 'layui-layer-molv' //样式类名
		,closeBtn: 0
		});
		return ;
		}

		$('#import-project-form').submit();
		});

			})
	</script>
<body style="background:#F3F3F4;">
	<div class="login_box">
		<h1>规培</h1>
		<h2>欢迎</h2>
		<div class="form">
			@if(session('msg'))
			<p style="color:red">{{session('msg')}}</p>
			@endif
			<form action="{{url('login')}}" method="post">
				{{csrf_field()}}
				<ul>
					<li>
					<input type="text" name="username" class="text"/>
						<span><i class="fa fa-user"></i></span>
					</li>
					<li>
						<input type="password" name="password" class="text"/>
						<span><i class="fa fa-lock"></i></span>
					</li>
					<li>
						<input type="text" class="code" name="code"/>
						<span><i class="fa fa-check-square-o"></i></span>
						<img src="{{url('code')}}" alt="" onclick="this.src='{{url('code')}}?'+Math.random()">
					</li>
					<li>
						<input type="submit" value="立即登陆"/>
					</li>
				</ul>
			</form>

		</div>


		<form action="" method="post" id="import-project-form" enctype="multipart/form-data">
			<input type="file" name="upload-file" style="display: none;" class="import-project-btn-real" />
			<a class=" import-project-btn " style="cursor: pointer;"><span  class="glyphicon glyphicon-plus"></span>&nbsp;导入项目</a>
		</form>
	</div>
</body>
</html>