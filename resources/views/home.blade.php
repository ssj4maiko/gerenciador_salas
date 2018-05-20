<?php
	$usuario = \App\Usuarios::currentUser();
	$alert = null;
	if(isset($request))
		$alert = $request->session()->get('alert');
?>
<!DOCTYPE html>
<html>
<head>
	<title>{{$title}}</title>
	<base href="{{ URL::to('/public') }}">
	{{ Html::script('calendarview/js/prototype.js') }}
	{{ Html::script('calendarview/js/calendarview.js') }}
	{{ Html::style('calendarview/css/calendarview.css') }}

	{{ Html::style('css/base.css') }}
	{{ Html::style('css/form.css') }}
	{{ Html::style('css/graficos.css') }}
	
</head>
<body>
	
	<div class='base_header'>
		<!-- Top header -->
		<div class="wrapper">
			<p class='user'>Olá <span>{{ $usuario->realname }}</span>!</p>
			<a href="{{ url('/logout') }}" class='aButton RightButtons'> (Sair) </a>
			<a href="{{ url('/usuario/'.$usuario->id_usuario) }}" class='aButton RightButtons'> (Editar Perfil) </a>
		</div>
	</div>
	<!-- Inner content -->
	<div class='wrapper'>
		@if($alert)
			@if($alert['type'] == 'success')
				<p class="alert_success">{{$alert['message']}}</p>
			@endif
		@endif
		<!-- Inner view -->
		@if($inner)
			@include($inner)
		@endif
	</div>

</body>
</html>