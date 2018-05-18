<?php
	$alert = null;
	if(isset($request))
		$alert = $request->session()->get('alert');
?>
<!DOCTYPE html>
<html>
<head>
	<title>{{$title}}</title>
	{{ Html::style('css/base.css') }}
	{{ Html::style('css/form.css') }}
	{{ Html::script('js/js.js') }}
</head>
<body>
	<div class='base_header'>
		<div class="wrapper">
			<p class='user'>Olá <span>visitante</span>!</p>
		</div>
	</div>
	<div class='wrapper'>
		@if($alert)
			@if($alert['type'] == 'success')
				<p class="alert_success">{{$alert['message']}}</p>
			@endif
		@endif
		
		@include($inner)
	</div>
</body>
</html>