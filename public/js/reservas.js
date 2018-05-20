'use strict';

var ajax = function(url,callback,parameters,json){
	var XHR = new XMLHttpRequest();

	if (!XHR) {
	  alert('Giving up :( Cannot create an XMLHTTP instance');
	  return false;
	}
	XHR.onreadystatechange = function(){
		if (XHR.status === 200 && XHR.readyState === XMLHttpRequest.DONE) {
			if(json){
				callback(JSON.parse(XHR.responseText));
			}
			else
				callback(XHR.responseText);
		}
	}
	
	if(parameters){/*
		XHR.open('POST', url);
		XHR.send(parameters);*/
		console.log(url+'?'+parameters);
		XHR.open('GET', url+'?'+parameters);
	}
	else{
		XHR.open('GET', url);
	}
	XHR.send();
}

var Reserva = {
	 current_date : null
	,id_usuario : null
	,DB : {
		 salas : {}
		,usuarios : {}
	}
	,trigger : function(){
		if(Reserva.current_date != this.value){
			Reserva.current_date = this.value.replace(' ','0');
			Reserva.loadReservas();
		}
	}
	,loadReservas :  function(){
		if(typeof Reserva.cache[Reserva.current_date] == 'undefined'){

			var url = 'reserva/check?date='+Reserva.current_date;
			var callback = Reserva.cachingItems;
			ajax(url,callback,null,true);

		} else {

			Reserva.loadContents();
		}
	}
	,cache : {}
	,cachingItems : function(json){
		var divisao = {};

		divisao.usuario = [];

		for(var i=0;i<json.length;++i){

			if(json[i].id_usuario == Reserva.id_usuario){
				divisao.usuario.push(json[i]);
			}

			if(!divisao[json[i].id_sala])
				divisao[json[i].id_sala] = [];

			divisao[json[i].id_sala].push(json[i]);
		}
		Reserva.cache[Reserva.current_date] = divisao;

		Reserva.loadContents();
	}
	,getReserva : function(id_reserva,id_usuario,id_sala){
		var resp = null,
			cache = Reserva.cache[Reserva.current_date];
		if(id_usuario){
			for(var i in cache.usuario){
				if(cache.usuario[i].id_reserva == id_reserva){
					resp = cache.usuario[i];
					break;
				}
			}
		}
		if(id_sala && !resp){
			for(var i in cache[id_sala]){
				if(cache[id_sala][i].id_reserva == id_reserva){
					resp = cache[id_sala][i];
					break;
				}
			}
		}

		return resp;
	}
	/**

	
		Desenho


	**/
	,graphUser : null
	,graphSalas : {}
	,cleanContents : function(){
		var barras = document.getElementsByClassName('barra_grafico');
		console.log(barras);
		for(var i=barras.length-1;i!= -1;--i){
			barras[i].parentNode.removeChild(barras[i]);
		}

		if(!Reserva.graphUser){
			Reserva.graphUser = document.getElementById('grafico_pessoa');
			var salas = document.getElementsByName('grafico_sala');
			var id = null;
			for(i=0;i<salas.length;++i){
				id = salas[i].id.substring(13);
				Reserva.graphSalas[id] = salas[i];
			}			
		}

		Reserva.colorCursor = 0;
		Reserva.cacheColor = {};
	}

	,loadContents : function(){
		Reserva.cleanContents();
		var cache = Reserva.cache[Reserva.current_date];

		console.log(cache);
		for(var id_sala in cache){
			if(id_sala == 'usuario'){
				for(var i=0;i<cache.usuario.length; ++i){
					Reserva.drawGraph(Reserva.graphUser,cache.usuario[i],'sala');
				}
				// Para não misturar as cores do dois tipos de gráficos id_usuario com id_sala
				// É garantido que usuário sempre será a primeira chave
				console.log(Reserva.cacheColor);
				Reserva.cacheColor = {};
			} else {
				for(var i=0;i<cache[id_sala].length; ++i){
					Reserva.drawGraph(Reserva.graphSalas[id_sala],cache[id_sala][i],'usuario');
				}
			}
			console.log(Reserva.cacheColor);
		}
	}

	//cores aleatórias
	,colorArray : [
		 '#b17fcb'
		,'#86e2d6'
		,'#5ec708'
		,'#14d831'
		,'#53dd04'
		,'#ecad6b'
		,'#3dd433'
		,'#3b1ac0'
		,'#b161cc'
		,'#af476a'
		,'#866120'
		,'#3374be'
		,'#0a22a0'
		,'#11bceb'
		,'#fb8d30'
		,'#569e32'
		,'#e76231'
		,'#d5936e'
		,'#295ed1'
		,'#5692af'
		,'#da52bc'
		,'#dd7a1f'
		,'#388f7d'
		,'#665364'
		,'#a360b3'
		,'#2aeceb'
		,'#e2e0fb'
		,'#ec3f71'
		,'#6a8d86'
		,'#ebf6dd'
		,'#edc198'
	]
	,colorCursor : 0
	,cacheColor : {}
	,drawGraph : function(graph, reserva,tipo){
		var start = convertTimeToPercentage(reserva.hr_start),
			end = convertTimeToPercentage(reserva.hr_start,'01:00');

		var block = document.createElement('div');
		if(!Reserva.cacheColor[ tipo + reserva['id_'+tipo] ]){
			++Reserva.colorCursor;
			Reserva.cacheColor[ tipo + reserva['id_'+tipo] ] = Reserva.colorArray[Reserva.colorCursor];
		}
		block.style.backgroundColor = Reserva.cacheColor[ tipo + reserva['id_'+tipo] ];
		block.dataset.color = Reserva.cacheColor[ tipo + reserva['id_'+tipo] ];
		block.className = 'barra_grafico';
		block.style.marginLeft = start+'%';
		block.style.width = (end - start)+'%';
		block.innerHTML = reserva.hr_start+'<br />'+reserva.hr_end+'<br />'+(reserva[tipo] ? reserva[tipo] : Reserva.DB.salas[reserva['id_'+tipo]]);
		block.id = 'barra_grafico_'+reserva.id_reserva;
		block.title = 'Selecione para editar ou ver detalhes';

		//block.addEventListener('click',Reserva.reservaForm);

		graph.appendChild(block);
	}
	/**

	
		Formulário para vizualização, criação ou edição de reservas


	**/
	,reservaForm : function(e){
		console.log(e);

		var base = e.target
			,id_reserva = null
			,id_usuario = Reserva.id_usuario
			,id_sala = null;
		if(base.id.indexOf('barra_grafico_') === 0){
			id_reserva = base.id.substring(14);
			base = base.parentNode;
		}

		switch(true){
			case base.id == 'grafico_pessoa':
				id_usuario = Reserva.id_usuario;
				break;
			case base.id.indexOf('grafico_sala_') === 0:
				id_sala = base.id.substring(13);
				break;
		}
		var modal = Reserva.openModal();

		var form = Reserva.drawForm(id_reserva,id_usuario,id_sala);

		modal.appendChild(form);
	}

	,drawForm : function(id_reserva, id_usuario, id_sala){

		var reserva = null;
		if(id_reserva)
			reserva = Reserva.getReserva(id_reserva, id_usuario, id_sala);
		var view_only = (reserva && reserva.id_usuario != Reserva.id_usuario);

		var form = document.createElement('form');
		form.action	= 'reserva/save'+(id_reserva ? '/'+id_reserva : '');
		form.addEventListener('submit',Reserva.enviaForm);
		form.className = 'generic_form';
		form.id = 'reserva_form';

		var titulo = document.createElement('h1');
		titulo.textContent = reserva ? 'Atualizar reserva' : 'Reservar Sala';
		form.appendChild(titulo);

		var input = document.createElement('input');
		input.name = 'id_reserva';
		input.type = 'hidden';
		if(id_reserva)
			input.value = id_reserva;
		form.appendChild(input);


			input = document.createElement('input');
		input.name = 'id_usuario';
		input.type = 'hidden';
		if(id_usuario)
			input.value = id_usuario;
		form.appendChild(input);

		if(id_sala){
			input = document.createElement('input');
			input.name = 'id_sala';
			input.type = 'hidden';
			input.value = id_sala;
			form.appendChild(input);
		}


		var p = document.createElement('p'),
			label = document.createElement('label');
		p.className = 'form_field';
		label.textContent = 'Data reserva';
			input = document.createElement('input');
		input.name		= 'dt_reserva';
		input.value		= Reserva.current_date;
		input.disabled	= 'disabled';
		p.appendChild(label);
		p.appendChild(input);
		form.append(p);

		if(reserva){
				p = document.createElement('p');
			p.className = 'form_field';
				label = document.createElement('label');
			label.textContent = 'Usuário';
				input = document.createElement('input');
			input.disabled	= 'disabled';
			input.value		= reserva.usuario;
			input.name		= 'usuario';
			p.appendChild(label);
			p.appendChild(input);
			form.append(p);			
		}

			p = document.createElement('p');
		p.className = 'form_field';
			label = document.createElement('label');
		label.textContent = 'Sala';
		if(id_sala){
			input = document.createElement('input');
			input.disabled	= 'disabled';
			input.value = Reserva.DB.salas[id_sala];
			input.name		= 'sala';
		}
		else{
			input = document.createElement('select');
			for(var i in Reserva.DB.salas){
				var option = document.createElement('option');
				option.value = i;
				if(reserva)
					option.selected = i == reserva.id_sala ? 'selected' : '';
				option.textContent = Reserva.DB.salas[i];
				input.appendChild(option);
			}
			input.name		= 'id_sala';
		}
		p.appendChild(label);
		p.appendChild(input);
		form.append(p);


		var p = document.createElement('p'),
			label = document.createElement('label');
		p.className = 'form_field';
		label.textContent = 'Hora início';
			input = document.createElement('input');
		input.type		= 'time';
		input.name		= 'hr_start';
		input.value		= reserva ? reserva.hr_start : '00:00';
		input.min		= '00:00';
		input.max		= '23:00';
		input.pattern	="[0-9]{2}:[0-9]{2}";
		input.style.width = '110px';
		if(view_only)
			input.disabled	= 'disabled';
		p.appendChild(label);
		p.appendChild(input);
		form.append(p);

		//Apenas para novas reservas, ou atualização
		if(!view_only){
			var p = document.createElement('p'),
				input = document.createElement('input');
			input.type		= 'submit';
			input.name		= 'submit';
			input.className	= 'button_green';
			input.style.width = '360px';
			input.value		= reserva ? 'Atualizar' : 'Reservar';
			input.addEventListener('click',function(e){
				console.log(this.form);
				this.form.submitted = 1;
			});
			p.appendChild(input);
			form.append(p);
		}

		if(reserva && Reserva.id_usuario == reserva.id_usuario){
			var p = document.createElement('p'),
				input = document.createElement('input');
			input.type		= 'submit';
			input.name		= 'excluir';
			input.className	= 'button_red';
			input.style.width = '360px';
			input.value 	= 'Excluir reserva';
			input.addEventListener('click',function(e){
				console.log(this.form);
				this.form.submitted = 0;
			});
			p.appendChild(input);
			form.append(p);
		}

		return form;
	}

	,openModal : function(e){
		var modal_base = document.createElement('div');
		modal_base.id			= 'modal_base';
		modal_base.className	= 'modal_fill';
		modal_base.addEventListener('click', Reserva.closeModal);

		var modal_content = document.createElement('div');
		modal_content.id		= 'modal_content';
		modal_content.className	= 'modal_content';

		document.body.appendChild(modal_base);
		document.body.appendChild(modal_content);

		return modal_content;
	}
	,closeModal : function(){
		var modais = document.querySelectorAll('.modal_fill,.modal_content');
		for(var i=modais.length-1;i!=-1;--i){
			modais[i].parentNode.removeChild(modais[i]);
		}
	}
	/**

		Submit do formulário


	**/
	,formSerialize : function(form){
		var fields = ['id_reserva','id_sala','id_usuario','dt_reserva','hr_start'],
			final = [];
		fields.forEach(function(field){
			final.push(field+'='+form[field].value);
		});
		return final.join('&');
	}
	,enviaForm : function(e){
		e.preventDefault();
		var form = e.target;

		switch(form.submitted){
			case 0:
				var url = 'reserva/del/'+parseInt(form.id_reserva.value);
				if(confirm('Deseja mesmo excluir esta reserva?')){
					ajax(url,Reserva.afterDel,null,true);
				}
				break;
			default:
				var update = form.id_reserva.value != '',
					url = 'reserva/save'+(update ? '/'+parseInt(form.id_reserva.value) : ''),
					callback = Reserva.afterUpd;

				if(Reserva.validateForm(form)){
					ajax(url,callback,Reserva.formSerialize(form),true);
				}

				break;
		}

		return false;
	}
	,validateForm : function(form){
		/**
	
		Checar dados formulário com os horários do usuário e da sala especificada

		**/
		var cache = Reserva.cache[Reserva.current_date],
			valid = {check : true, message : 'OK'};

		switch(true){
			case (!/^((2[0-3]|[01]?[0-9]):([0-5]{1}[0-9]))|23:00$/.test(form.hr_start.value)):
				valid.message = 'Horário início inválido. Por favor, escreva no formato (H)H:MM. Mínimo 0:00 / Máximo 23:00';
				valid.check = false;
				form.hr_start.focus();
				break;
		}
		var form_sta = convertTimeToPercentage(form.hr_start.value),
			extra1 = convertTimeToPercentage('01:00'),
			form_end = form_sta + extra1;

		var id_reserva = parseInt(form.id_reserva.value);
		var id_sala = form.id_sala.varlue;


		if(valid.check === true){ 
			for(var i=0;i<cache.usuario.length;++i){
				if(cache.usuario[i].id_reserva != id_reserva){

					var cach_sta = convertTimeToPercentage(cache.usuario[i].hr_start);
					var cach_end = cach_sta + extra1;
					var init = form_sta < cach_end;
					var fin = form_end > cach_sta;
					var igual = form_sta == cach_sta && form_end == cach_end;
					if(init && fin || igual){
						valid.check = false;
						valid.message = 'Você já possui uma reserva entre '+cache.usuario[i].hr_start+' e '+cache.usuario[i].hr_end;

						form.hr_start.focus();
						break;
					}
				}
			}
			if(cache[id_sala]){
				for(var i=0;i<cache[id_sala].length;++i){
					if(cache[id_sala][i].id_reserva != id_reserva){

						var cach_sta = convertTimeToPercentage(cache[id_sala][i].hr_start);
						var cach_end = cach_sta + extra1;
						var init = form_sta < cach_end;
						var fin = form_end > cach_sta;
						if(init && fin){
							valid.check = false;
							valid.message = 'A sala já possui uma reserva entre '+cache[id_sala][i].hr_start+' e '+cache[id_sala][i].hr_end+' para '+cache[id_sala][i].usuario;

							if(form_sta < cach_sta){
								form.hr_end.focus();
							} else {
								form.hr_start.focus();
							}
							break;
						}
					}
				}
			}
		}

		if(!valid.check)
			alert(valid.message);

		return valid.check;
	}
	,afterDel : function(json){
		if(json.type != 'success'){
			alert(json.message);
		} else {
			let reserva_form = document.getElementById('reserva_form');

			for(let j=0;j<Reserva.cache[Reserva.current_date].usuario.length;++j){
				if(Reserva.cache[Reserva.current_date].usuario[j].id_reserva == reserva_form.id_reserva.value){
					Reserva.cache[Reserva.current_date].usuario.splice(j,1);
					break;
				}
			}

			for(let i = 0; i < Reserva.cache[Reserva.current_date][reserva_form.id_sala.value].length; ++i){
				if(Reserva.cache[Reserva.current_date][reserva_form.id_sala.value][i].id_reserva == reserva_form.id_reserva.value){
					Reserva.cache[Reserva.current_date][reserva_form.id_sala.value].splice(i,1);
					break;
				}
			}

			Reserva.loadContents();
			Reserva.closeModal();
		}
	}
	,afterUpd : function(json){
		if(json.type != 'success'){
			alert(json.message);
		} else {
			var itens = json.update;

			// Garantir existencia de cache
			if(!Reserva.cache[Reserva.current_date])
				Reserva.cache[Reserva.current_date] = {};
			
			// Divisao
			var divisao = Reserva.cache[Reserva.current_date];

			for(var i=0;i<itens.length;++i){
				var add_on_user = false;
				var add_on_build = false;
				var id_sala_original = null;
				for(var j=0;j<Reserva.cache[Reserva.current_date].usuario.length;++j){
					//Detectar se é um update
					if(Reserva.cache[Reserva.current_date].usuario[j].id_reserva == itens[i].id_reserva){
						id_sala_original = Reserva.cache[Reserva.current_date].usuario[j].id_sala;
						Reserva.cache[Reserva.current_date].usuario[j] = itens[i];
						add_on_user = true;
						break;
					}
				}
				// Se não for update, inserir
				if(!add_on_user){
					Reserva.cache[Reserva.current_date].usuario.push(itens[i]);
				}

				if(!Reserva.cache[Reserva.current_date][itens[i].id_sala])
					Reserva.cache[Reserva.current_date][itens[i].id_sala] = [];
				// Caso tenha sido atualizado a sala, precisa-se deletar a anterior
				if(id_sala_original){
					if(Reserva.cache[Reserva.current_date][id_sala_original]){
						for(var j=0;j<Reserva.cache[Reserva.current_date][id_sala_original].length;++j){
							if(Reserva.cache[Reserva.current_date][id_sala_original][j].id_reserva == itens[i].id_reserva){
								Reserva.cache[Reserva.current_date][id_sala_original].splice(j,1);
								break;
							}
						}
					}
				}

				/*
				for(var j=0;j<Reserva.cache[Reserva.current_date][itens[i].id_sala].length;++j){
					//Detectar se é um update
					if(Reserva.cache[Reserva.current_date][itens[i].id_sala][j].id_reserva == itens[i].id_reserva){
						Reserva.cache[Reserva.current_date][itens[i].id_sala][j] = itens[i];
						add_on_build = true;
						break;
					}
					// Se não for update, inserir
					if(!add_on_build){
						Reserva.cache[Reserva.current_date][itens[i].id_sala].push(itens[i]);
					}
				}
				*/
				// Como foi garantido a exclusão do registro anteriormente, onde quer que ele esteja
				// Sempre será adicionado ao vetor aqui
				Reserva.cache[Reserva.current_date][itens[i].id_sala].push(itens[i]);

			}
			Reserva.loadContents();
			Reserva.closeModal();
		}
	}
	,afterIns : function(json){
		console.log(json);
		//Reserva.closeModal();
	}
}
var convertTimeToPercentage = function(time, sum){
	let add = 0;
	if(sum){
		add = convertTimeToPercentage(sum);
	}
	time = time.split(':');
	var seconds = (+time[0]) * 60 * 60 + (+time[1]) * 60;
	return (seconds/86400)*100 + add;	//	seconds / day
}

//document.getElementById('quick-filter').addEventListener('change',Reserva.trigger);
var QF = document.getElementById('quick-filter');
	QF.onchange = Reserva.trigger;
var Now = new Date();
	QF.value = Now.toISOString().substring(0,10);