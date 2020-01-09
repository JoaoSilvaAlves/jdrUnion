<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<!-- SCRIPTS -->
	<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui.js" type="text/javascript"></script>

	<!-- STYLES -->
	<style>
		*{
			overflow: hidden;
		}
		body{
			padding: 0;
			margin: 0;
			font-family: "Century Gothic",CenturyGothic,AppleGothic,sans-serif;
			width: 100%;
			height: 100%;
		}
		#screen{
			height: 100%;
			width: 100%;
		}
		img{
			border-image-repeat: stretch;
			min-width: 20px;
			min-height: 20px;
		}

	</style>
</head>
<body>
<div id="screen"></div>


<script>
	var data;
	$(document).ready(function(){
		$('html').height($(window).height());
		setInterval(function(){
			$.ajax({
				url: "scene.php",
				context: document.body
			}).done(function(_data){
				if (data != _data) {
					$("#screen").html(_data);
					data = _data;
				}
			});
	    }, 2000);

	    function download(filename, text) {
		  var element = document.createElement('a');
		  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
		  element.setAttribute('download', filename);

		  element.style.display = 'none';
		  document.body.appendChild(element);

		  element.click();

		  document.body.removeChild(element);
		}
	});
</script>
</body>
</html>


