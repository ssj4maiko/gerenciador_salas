<fieldset id='login_form'>
	<legend>Login</legend>
	{{ Form::open(array('url' => 'login')) }}
	<p>
		{{ $errors->first('username') }}
		{{ $errors->first('password') }}
	</p>
	<p>
		{{ Form::label('username', 'Nome de Usuário:') }}
		{{ Form::text('username', Input::get('username'), array('placeholder' => '')) }}
	</p>
	<p>
		{{ Form::label('password', 'Senha:') }}
		{{ Form::password('password', array('placeholder' => '', 'value' => Input::get('password'))) }}
	</p>

	{{ Form::submit('Entrar', array('class'=>'button_green')) }}
	<a href="{{ url('/registrar') }}" class='aButton button_red'>Registrar</a>
	
	{{ Form::close() }}
</fieldset>