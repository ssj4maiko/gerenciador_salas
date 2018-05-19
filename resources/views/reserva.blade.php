<?php
	$DB_salas = array();
	foreach($salas as $v) $DB_salas[$v->id_sala] = $v->descricao;
?>


<fieldset>
	<legend>Reservas por sala</legend>

	<a href="{{ url('/sala') }}" class="aButton RightButtons" style='padding:0'>Criar nova sala</a>
	<!--
	@if(count($salas) > 1)
		{{ Form::label('quick-filter', 'Filtro de salas') }}
		{{ Form::input('quick-filter', '') }}
	@endif
	-->

	@if(count($salas) > 0)

		<?php foreach($salas as $v): ?>
			<legend>
				Sala {{$v->descricao}}
				<a href="{{url('/sala/'.$v->id_sala)}}">(Edt)</a>
				<a name='button_del[{{$v->id_sala}}]' href="{{url('/sala/del/'.$v->id_sala)}}">(Del)</a>
			</legend>
			<fieldset name='grafico_sala' class='grafico_sala' id='grafico_sala_{{$v->id_sala}}'>
				<div class='grafico_sala_base'></div>
			</fieldset>
		<?php endforeach; ?>

	@else
		<p>Não existe nenhuma sala cadastrada</p>
	@endif

</fieldset>