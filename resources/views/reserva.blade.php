<?php
	$DB_salas = array();
	foreach($salas as $v) $DB_salas[$v->id_sala] = $v->descricao;

	$help = 'Clique para criar uma reserva / Selecione para editar ou ver detalhes';
?>

<div>
	<h3 style='text-align:center'>Selecione a data pelo calendário: {{ Form::hidden('quick-filter', '', array('id' => 'quick-filter')) }} </h3>
	<div id="calendario" style="margin-left: auto; margin-right: auto">
	</div>
</div>
{{ Html::script('js/reservas.js') }}



<fieldset>
	<legend>Reservas pessoais</legend>
	<fieldset title='Clique para criar uma reserva' class='grafico_campo grafico_pessoa' name='grafico_pessoa' id='grafico_pessoa'>
		<p class='background_text'>{{ $help }}</p>
		<div class='grafico_pessoa_base'></div>
	</fieldset>
</fieldset>


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
			<fieldset title='Clique para criar uma reserva' name='grafico_sala' class='grafico_campo grafico_sala' id='grafico_sala_{{$v->id_sala}}'>
				<p class='background_text'>{{ $help }}</p>
				<div class='grafico_sala_base'></div>
			</fieldset>
		<?php endforeach; ?>

	@else
		<p>Não existe nenhuma sala cadastrada</p>
	@endif

</fieldset>

<script type="text/javascript">
	Reserva.id_usuario = {{$usuario->id_usuario}};
	Reserva.DB = {
		salas : {!! json_encode($DB_salas) !!}
	};
	Calendar.setup({
	    dateField: 'quick-filter',
	    parentElement: 'calendario'

	    //,dateFieldOnChange: teste
	});
	document.getElementById('quick-filter').onchange();
	document.getElementById('grafico_pessoa').addEventListener('click',Reserva.reservaForm);
	var blocks = document.getElementsByName('grafico_sala');
	for(var i=0;i<blocks.length;++i){
		blocks[i].addEventListener('click',Reserva.reservaForm)
	}
</script>