<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="author" content="phpartisan.cn"/>
	<title>绑定Google验证码</title>
	<script type="text/javascript">  
	    function startTime()  
	    {  
	        //获取当前系统日期  
			var myDate = new Date();
	        var y=myDate.getFullYear(); //获取当前年份(2位)
	        var m=myDate.getMonth()+1; //获取当前月份(0-11,0代表1月)
	        var d=myDate.getDate(); //获取当前日(1-31)
	        var h=myDate.getHours(); //获取当前小时数(0-23)
	        var mi=myDate.getMinutes(); //获取当前分钟数(0-59)
	        var s=myDate.getSeconds(); //获取当前秒数(0-59)
	        var hmiao=myDate.getMilliseconds(); //获取当前毫秒数(0-999)
	        //s设置层txt的内容  
	        document.getElementById('txt').innerHTML=y+"-"+m+"-"+d+" "+h+":"+mi+":"+s;  
	        //过500毫秒再调用一次  
	        t=setTimeout('startTime()',500)  
	        //小于10，加0  
	        function checkTime(i)  
	        {  
	            if(i<10)  
	               {i="0"+i}  
	               return i  
	        }  
	    }
	</script>  
	<style type="text/css">
		body{
			background-color:#2E363F;
			margin:0px;
			padding:0px;
		}
		ul,li{
			list-style:none;
			padding:0px;
			margin:0px;
		}
		.container{
			width:98%;
			max-width:1000px;
			min-width:600px;
			background-color:#FFF;
			height:1250px;
			padding: 20px 20px 100px 20px;
			margin: 30px auto;
			line-height:25px;
			font-family:微软雅黑;
			font-size:15px;
			color: #666666;
		}
		.container span{
			font-weight:bold;
			color: #666666;
			font-size:15px;
			line-height:35px;
		}
		.container h3{
			font-size:24px;
			color: #333333;
		}
		.container h4{
			font-size:18px;
			color: #333333;
		}
		.container h5{
			font-size:15px;
			color: #333333;
		}			
		.discription{
			width:100%;
			height:auto;
			border-bottom:thin dashed #CCC;
			padding-bottom: 20px;
			float: left;
		}
		.appdownloadcode{
			width:100%;
			height:230px;
			padding-top:20px;
		}	
		.appdownloadcode li{
			width:50%;
			float:left;
			text-align:center;
		}
		.appdownloadcode img{
			width:200px;
			height:200px;
			margin-left:auto;
			margin-right:auto;
		}		
		.container-form{
			width:100%;
			height:230px;
			padding-top:20px;
			text-align:center;
			margin-top: 30px;
			float: left;
		}
		.container-form img{
			width:250px;
			height:250px;
			margin: 0px auto 10px auto;
			padding:5px;
			border:thin solid #CCC;
			border-radius:10px;
		}	
		.verificationcode{
			width:300px;
			height:35px;
			outline:none;
			border:thin solid #CCC;
			font-family:微软雅黑;
			font-size:14px;
			padding-left:20px;
			margin-top:20px;
		}	
		.submit-button{
			width:150px;
			height:35px;
			border-radius: 2px;
			outline:none;
			background-color: #0C6;
			color:#FFF;
			font-family:微软雅黑;
			border:none;
			font-size:15px;
			margin-top:20px;
		}	
		a{
			color:#09C;
		}
		.notice{
			width: 100%;
			float: left;
			color: #FF6666;
			margin-top: 20px;
		}				
	</style>
</head>

<body onload="startTime()">
	<div class="container">
		<div class="discription">
		    <h3>绑定谷歌验证器</h3>
		    <h4>使用说明：</h4>
	    	<p>如果遇到问题，请参考：<a href="https://phpartisan.cn/specials/5" target="_blank">Google Authenticator帮助文档</a></p>
	    	<h5>步骤一：</h5>
	    	<p>手机下载安装Google Authenticator。</p>
	    	<div class="appdownloadcode">
				<ul>
					<li>
						<img src="/images/google/ios.png" width="280" height="280" /><br />Ios扫描下载
					</li>
					<li>
						<img src="/images/google/android.png" width="280" height="280" /><br />安卓扫描下载
					</li>
				</ul>
			</div>
			<h5>步骤二：</h5>
			<p>软件安装完成后，选择开始设置-扫描条形码，来扫描本页面的二维码，扫描成功后，您手机里的谷歌验证器会生成一个与您账户对应的六位动态密码，每30秒变化一次。</p>
			<h5>步骤三：</h5>
			<p>之后您每次登陆时都需要输入谷歌验证码，无论手机是否连接网络都可以使用。在允许时间内输入有效数字，保证了账户安全。
			</p>
		</div>

		<div class="container-form">
			<p>本页面刷新后二维码会重置，请重新扫描</p>
			{!! QrCode::encoding('UTF-8')->size(200)->margin(1)->generate($createSecret["codeurl"]); !!}
			<br />服务器当前时间为：&nbsp;&nbsp;<font id="txt"></font>
			<br />如果图片无法显示或者无法扫描，请在手机登录器中手动输入:
			<font color="#FF6666">{{ $createSecret["secret"] }}</font>
			<form action="{{ empty(Config::get('google.authenticatorurl')) ? URL::current() : Config::get('google.authenticatorurl') }}" method="POST">
				{!! csrf_field() !!}
				<input name="onecode" type="text" class="verificationcode" placeholder="请输入扫描后手机显示的6位验证码" value="{{ old('onecode') }}" />
				@foreach($parameter as $parame)
				<input type="hidden" name="{{ $parame['name'] }}" value="{{ $parame['value'] }}" />
				@endforeach
				<input type="hidden" name="google" value="{{ $createSecret['secret'] }}" />
				<br />
				<button class="submit-button">立即绑定</button>
				@if(Session::has('msg'))
				   <div class="notice">{{ Session::get('msg') }}</div>
				@endif
			</form>
		</div>
	</div>
</body>
</html>