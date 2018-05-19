<?php
	$action = $form['params']['action']. (!$form['params']['id_update'] ? '' : '/'.$form['params']['id_update']);
	$name = $form['params']['name'];
?>
<div class="generic_form">
	<h1>{{$title}}</h1>
	{{ Form::open(array('url' => $action, 'name' => $name)) }}

	<p>
		{{ Form::submit($form['params']['id_update'] ? 'Atualizar Registro' : 'Inserir') }}
		@isset($form['params']['back'])
		<a href='{{$form['params']['back']}}' class="aButton">Voltar</a>
		@endisset
	</p>
	<?php

	foreach($form['fields'] as $id => $field):
		$type = $field['type'];
		//Input::get($id)
		?>
		<p class='form_field'>

			{{ Form::label($id, $field['label']) }}
			@if($type == 'password')
				{{ Form::$type($id, $field['attrb']) }}
			@else
				{{ Form::$type($id, $field['value'], $field['attrb']) }}
			@endif

			<?php $error = $errors->$name->first($id);?>
			@if($error)
				<span class="form_error">{{ $error }}</span>
			@endif
		
		</p>

	<?php endforeach; ?>

	<p>
		{{ Form::submit($form['params']['id_update'] ? 'Atualizar Registro' : 'Inserir') }}
		@isset($form['params']['back'])
		<a href='{{$form['params']['back']}}' class="aButton">Voltar</a>
		@endisset
	</p>

	{{ Form::close() }}
</div>