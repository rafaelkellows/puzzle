$(function(){ 
	(function() {
	    var sortQuestions = [], sortAnswers = [], answers = [], oAnswers = [], oMatters = [];
    	var _itens = 0, _level = '', _levelLabel = '', _maxCalcQuestions = 3; //questions amount limit set on "starting" obj;
		var _v,_t,_l,_amount,loadQuestion,rndQuestions,json,control;
    	var currQuestion = 0; currAnswer = null;
		var validateEmail = function(email){
		  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		  return re.test(email);
		}
		var validateInput = function(val){
		  var re = /^[^]+$/;
		  return re.test(val);
		};
    	var userEmail, userPswd;
		var sqPuzzle = {
	        init: function() {
	        	this.loading();
	        	//this.starting();
	        	//this.loadJson();
	        	this.setUser();
	        },
	        clearForm : function(){
				$('form input[name=email]').remove();
				$('form input[name=password]').remove();
				userEmail = null;
				userPswd = null;
	        },
	        setUser : function(msg){
				$('.box._alert .btn.forward').attr('value','SIM').find('i').removeAttr('class').addClass('fa fa-forward');
				sqPuzzle.clearForm();
				$('.btn[value=BEGIN]').click(function(){
					if(userEmail&&userPswd){return;}
		        	$('.box._alert .btn').removeClass('hide').show();
					$('.box._alert').removeClass('hide').addClass('show');
					sqPuzzle.boxConfirm(
						"Diga seu e-mail:<br><em class='hide'>"+( (msg) ? msg :"verifique a informação novamente"  )+"<br></em><input maxlength='40' type='text' name='email'>", "profile"
					);
				});
	        },
	        loading : function(){
	        	$(window).load(function(){
					$('.loading').removeClass('active');
	        	});
	        },
	        starting : function(){
	        	//console.log(json);
	        	sqPuzzle.usrConfigPuzzle(0);
	        },
	        loadJson : function(userName) {
				$.ajax({
					url : 'json.php',
					dataType : 'json',	
					success : function(data) {
						json = data;
						sqPuzzle.starting();
					},
					error : function(r) { 
					    $('.box._alert .btn.forward').show().attr('value','').find('i').removeAttr('class').addClass('fa fa-check');
						$('.box._alert div p').html('Aconteceu algum erro ao conectar com o banco. Tente novamente.');
					},
					beforeSend: function(r) { 
						$('.box._alert input[name=email]').remove();
						$('.box._alert div p').html('Olá, <strong>'+userName+'</strong>.<br>O sistema está carregando a base de dados.<br> Aguarde um pouco!');
					}
				});
	        },
	        getPuzzle: function() {
	        	sqPuzzle.cronometer('start');

	        	loadQuestion = function(qIndex){
					_getMatters = function(){
						_m='';
			    		for (var i=0; i<json.length; i++) {
			    			for (var m=0; m<oMatters.length; m++) {
								if(json[i].matter == oMatters[m]){
									_m=_m+', '+json[i].title;
								}
							}
						}
						return _m.substring(1);
					}
		        	$('.puzzle .questions p.theme').html( 'Teste de <strong>'+_getMatters()+'</strong> <br>com <strong>' + _amount + ' questões</strong>.<br> Nível: <strong>'+ _levelLabel +'</strong>');
		        	$('.puzzle .questions p.steps').html( 'Você está na questão <strong>'+(qIndex+1)+'</strong> de <span>'+_itens+'</span>.' );
		        	if( oAnswers[ (sortQuestions[qIndex]-1) ][0].length > 1 ){
			        	$('.puzzle .questions dl dt').html( (currQuestion+1) +') '+ oAnswers[ (sortQuestions[qIndex]-1) ][0][0] + '<img src="' + oAnswers[ (sortQuestions[qIndex]-1) ][0][1] + '" />' );
		        	}else{
			        	$('.puzzle .questions dl dt').html( (currQuestion+1) +') '+ oAnswers[ (sortQuestions[qIndex]-1) ][0] );
		        	}

					$('.puzzle .questions dl dd ul').html('');
		        	
					console.log(oAnswers[ (sortQuestions[qIndex]-1) ][1]);

					if(!oAnswers[ (sortQuestions[qIndex]-1) ][1].length){
		        		$('.puzzle .questions dl dd ul').append( ' <li style="text-align:center; "><input id="ans_00" type="text" name="ans" value=""> <label for="ans_00"></label></li>' );
			        	$('.puzzle .questions dl dd ul li input').keyup(function() {
						  	currAnswer = $(this).val().toLowerCase();
						  	$('.puzzle .questions dl dd ul li input').val(currAnswer);
						});
					}else{
						sortAnswers.splice(0,sortAnswers.length);
			        	while (sortAnswers.length < oAnswers[ (sortQuestions[qIndex]-1) ][1].length) {
			        		_rq = rndQuestions(oAnswers[ (sortQuestions[qIndex]-1) ][1].length);

							if( sortAnswers.indexOf( _rq ) === -1 ){
								sortAnswers.push(_rq);
							}
			        	}
			        	console.log(sortAnswers);

			        	for (var i = 0; i < oAnswers[ (sortQuestions[qIndex]-1) ][1].length; i++) {
			        		$('.puzzle .questions dl dd ul').append( ' <li>'+(i+1)+'. <input id="ans_0'+(sortAnswers[i]-1)+'" type="radio" name="ans" value="'+(sortAnswers[i]-1)+'"> <label for="ans_0'+(sortAnswers[i]-1)+'">'+ oAnswers[ (sortQuestions[qIndex]-1) ][1][(sortAnswers[i]-1)] +'</label></li>' );
			        	}
			        	
			        	$('.puzzle .questions dl dd ul li input').click(function(){
						  	$(this).closest('ul').find('li').removeClass('active');
			        		currAnswer = $(this);
						  	$(this).closest('li').addClass('active');
			        	});
					}

	        	}

				loadQuestion(currQuestion);
				$('.btn').unbind('click');
	        	$('.btnConfirm').click(function(){
	        		if(!currAnswer){
						sqPuzzle.boxConfirm( "Você precisa definir uma resposta!", "affirmative" );
						return;
	        		}
	        		$('.box > strong').hide();
					sqPuzzle.boxConfirm(
						"Você tem certeza da sua resposta?", "questions"
					);
	        	});
	        	$('.btnCancel').click(function(){
					$('.puzzle .questions dl dd ul li').removeClass('active');
					$('.puzzle .questions dl dd ul li input[type=radio]').prop('checked', false).removeAttr("checked");
					$('.puzzle .questions dl dd ul li input[type=number]').val('');
					currAnswer = null;
	        	});
	        },
	        usrConfigPuzzle : function(step){
				$('.box._alert').removeClass('hide').addClass('show');
				$('.box._alert .btn').show();
				$('.box._alert .btn.cancel').hide();
				
				switch(step){
					case 0: //Setting Matter(s)
			        	var _ul = '<ul class="matter">';
				    	for (var i=0; i<json.length; i++) {
					    	if( json[i].questions ){
					    		_ul += '<li><a href="javascript:void(0);" id="'+json[i].matter+'"><span>'+json[i].title+'</span><img src="'+json[i].img+'"></a></li>';
					    	}
				    	}
			        	_ul += '</ul>';
						sqPuzzle.boxConfirm( "Escolha abaixo as matérias do teste.", "materias", _ul);
						$('body main .puzzle .box > div > ul li').click(function(){
							$(this).toggleClass('active');
						})
					break;
					
					case 1: //Setting Level(s)
			        	var _ul = '<ul class="level">',counter=0,oLevels=[];
			    		for (var i=0; i<json.length; i++) {

			    			for (var m=0; m<oMatters.length; m++) {
								if(json[i].matter == oMatters[m]){
									var count = 2; // 0: Facil, 1: Médio e 2: Difícil
									for (var y=0; y<=count; y++) {
										if(typeof(json[i].questions[y])!=="undefined"){
										
										if(json[i].questions[y].length > 0){
											switch(y){
												case 0:
												if(oLevels.indexOf('0') == -1){ 
													_oTitle = "Fácil"; 
													oLevels.push('0'); 
													_ul += '<li><a href="javascript:void(0);" id="'+y+'"><span>'+_oTitle+'</span></a></li>';
												}
												break;
												case 1:
												if(oLevels.indexOf('1') == -1){ 
													_oTitle = "Médio";
													oLevels.push('1'); 
													_ul += '<li><a href="javascript:void(0);" id="'+y+'"><span>'+_oTitle+'</span></a></li>';
												} 
												break;
												case 2:
												if(oLevels.indexOf('2') == -1){ 
													_oTitle = "Difícil";
													oLevels.push('2'); 
													_ul += '<li><a href="javascript:void(0);" id="'+y+'"><span>'+_oTitle+'</span></a></li>';
												} 
												break;
											}
											counter++;
										}}
									}
								}
			    			}

			    		}
			    		//console.log(oLevels);
			    		if( counter > 1 ){
			    			oLevels.push('3'); 
							_ul += '<li><a href="javascript:void(0);" id="3"><span>Aleatório</span></a></li>';
						}
			        	_ul += '</ul>';
						sqPuzzle.boxConfirm(
							"Escolha o nível das perguntas do teste.", "materias",_ul
						);
						$('body main .puzzle .box > div > ul li').click(function(){
							$('body main .puzzle .box > div > ul li').removeClass('active');
							$(this).addClass('active');
						})
					break;
					
					case 2: //Setting Question Amount divided by (_maxCalcQuestions)
		        		var _qntItensByLevel = 0;
				    	for (var i=0; i<json.length; i++) {
			    			for (var m=0; m<oMatters.length; m++) {
								if(json[i].matter == oMatters[m]){

								//if(json[i].matter == _v){

									//console.log( json[i].questions[0] )
									if(_level != "" && _level != 3){
					        			for(var _i = 0; _i < json[i].questions[_level].length; _i++){
											oAnswers.push( json[i].questions[_level][_i] );
					        			}
						        		_qntItensByLevel = oAnswers.length;
									}else{
						        		for(var _q = 0; _q < Object.keys(json[i].questions).length; _q++){
						        			for(var _i = 0; _i < json[i].questions[_q].length; _i++){
												oAnswers.push( json[i].questions[_q][_i] );
						        			}
						        			_qntItensByLevel+=json[i].questions[_q].length;
						        		}
						        	}
						        }
							}
			    		}
	        			var _ul = '<ul class="amount">',counter=0;
			        	// Calc Total itens and set the select[name=qAmount]'s options
		        		if( eval(_qntItensByLevel / _maxCalcQuestions) < 1 ){
							_ul += '<li><a href="javascript:void(0);" id="'+_qntItensByLevel+'"><span>'+_qntItensByLevel+'</span></a></li>';
		        		}else{
		        			for(var i = 1; i <= eval(_qntItensByLevel / _maxCalcQuestions); i++ ){
			        			_ul += '<li><a href="javascript:void(0);" id="'+(i*_maxCalcQuestions)+'"><span>'+ (i*_maxCalcQuestions) + '</span></a></li>';
		        			}
		        			if( _qntItensByLevel % _maxCalcQuestions ){
			        			_ul += '<li><a href="javascript:void(0);" id="'+_qntItensByLevel+'"><span>'+ _qntItensByLevel + '</span></a></li>';
		        			}
		        		}
			        	_ul += '</ul>';
			        	//Random Questions
			        	sortQuestions.splice(0,sortQuestions.length);
			        	rndQuestions = function (_length){
			        		return Math.floor((Math.random() * _length) + 1);
			        	}
			        	while (sortQuestions.length < _qntItensByLevel) {
			        		_rq = rndQuestions( _qntItensByLevel );

							if( sortQuestions.indexOf( _rq ) === -1 ){
								sortQuestions.push(_rq);
							}
			        	}

						sqPuzzle.boxConfirm(
							"Escolha a quantidade de perguntas.", "materias",_ul
						);
						$('body main .puzzle .box > div > ul li').click(function(){
							$('body main .puzzle .box > div > ul li').removeClass('active');
							$(this).addClass('active');
						});
					//break;
				}
	        },
	        cronometer: function(status){

				var centesimas = 0;
				var segundos = 0;
				var minutos = 0;
				var horas = 0;
				function inicio(){
					control = setInterval(cronometro,10);
				}
				function parar(){
					clearInterval(control);
				}
				function reinicio(){
					clearInterval(control);
					centesimas = 0;
					segundos = 0;
					minutos = 0;
					horas = 0;
					Centesimas.innerHTML = ":00";
					Segundos.innerHTML = ":00";
					Minutos.innerHTML = ":00";
					Horas.innerHTML = "00";
				}
				function cronometro(){
					if (centesimas < 99) {
						centesimas++;
						if (centesimas < 10) { centesimas = "0"+centesimas }
						Centesimas.innerHTML = ":"+centesimas;
					}
					if (centesimas == 99) {
						centesimas = -1;
					}
					if (centesimas == 0) {
						segundos ++;
						if (segundos < 10) { segundos = "0"+segundos }
						Segundos.innerHTML = ":"+segundos;
					}
					if (segundos == 59) {
						segundos = -1;
					}
					if ( (centesimas == 0)&&(segundos == 0) ) {
						minutos++;
						if (minutos < 10) { minutos = "0"+minutos }
						Minutos.innerHTML = ":"+minutos;
					}
					if (minutos == 59) {
						minutos = -1;
					}
					if ( (centesimas == 0)&&(segundos == 0)&&(minutos == 0) ) {
						horas ++;
						if (horas < 10) { horas = "0"+horas }
						Horas.innerHTML = horas;
					}
				}
				switch(status){
					case 'start': inicio(); break;
					case 'stop': parar(); break;
					case 'reiniciar': reinicio(); break;					
				}
	        },
	        boxConfirm : function( _dsc, _id, _html ){
				var $form = $('form.puzzle');

				if( _id == "profile" && userEmail ){
					$form.prepend( $('.box._alert input[name=email]').attr('type','hidden'));
				}
				if( _id == "loading" && userEmail && userPswd ){
					$form.prepend( $('.box._alert input[name=password]').attr('type','hidden'));
				}
				$('.box._alert').find('div p').html(_dsc);

				if(_html){
					$('.box._alert div p').next().remove();
					$(_html).insertAfter( '.box._alert div p' );
				}

				if( _id == "affirmative"){
					$('.box._alert').removeClass('hide').addClass('show');
					$('.box > strong').show();
					$('.box._alert .btn').hide();
					$('.box._alert .btn.forward').show().attr('value','').find('i').removeAttr('class').addClass('fa fa-check');
				}
				if( _id == "questions"){
					$('.box._alert').removeClass('hide').addClass('show');
					$('.box._alert .btn.cancel').show().attr('value','NÃO').find('i').removeAttr('class').addClass('fa fa-times');
					$('.box._alert .btn.forward').show().attr('value','SIM').find('i').removeAttr('class').addClass('fa fa-check');
				}

				$('.box._alert input[type=button], .box._alert .btn').unbind('click').click(function(){
					_val = ($(this).hasClass('btn')) ? $(this).attr('value') : $(this).val() ;
					if( _val == "SIM" || _val == "OK" || _val == "ENTRAR" || _val == "COMEÇAR" || _val == "RESULTADO" || _val == "CONFIRMAR" ){
						//1. Acesso do Usuario
						if( _id == "profile" ){
							// Tela de Digite seu EMAIL
							if( $('.box._alert input[name=email]').length ){
								if( validateEmail($('.box._alert input[name=email]').val()) ){
									$('.box._alert input[name=email]').removeClass('error').prev('em').addClass('hide').removeClass('error show');
									userEmail = $('.box._alert input[name=email]').val();
									sqPuzzle.boxConfirm(
										"Digite sua senha:<br><em class='hide'>verifique a informação novamente<br></em><input maxlength='40' type='password' name='password'>", "profile"
									);
								}else{
									$('.box._alert input[name=email]').addClass('error').focus().prev('em').html('verifique a informação novamente<br>').removeClass('hide').addClass('error show');
									return;
								}
							}
							// Tela de Digite sua SENHA
							if( $('.box._alert input[name=password]').length ){
								if( validateInput($('.box._alert input[name=password]').val()) ){
									$('.box._alert input[name=password]').removeClass('error').prev('em').addClass('hide').removeClass('error show');
									userPswd = $('.box._alert input[name=password]').val();
									sqPuzzle.boxConfirm( "aguarde...", "loading" );
								}else{
									$('.box._alert input[name=email]').addClass('error').focus().prev('em').removeClass('hide').addClass('error show');
									return;
								}
							}
						}
						//2. Matérias
						if( _id == "materias" ){
							$('body main .puzzle .box > div > ul li').each(function(){
								if( $(this).hasClass('active') ){
									if($(this).parent().hasClass('matter')){
										_v =  $(this).find('a').attr('id');
										oMatters.push(_v);
										_t = $(this).find('a').text();
										sqPuzzle.usrConfigPuzzle(1);
									}
									if($(this).parent().hasClass('level')){
										_level =  $(this).find('a').attr('id');
										_levelLabel =  $(this).find('a').text();
										sqPuzzle.usrConfigPuzzle(2);
									}
									if($(this).parent().hasClass('amount')){
										_amount =  $(this).find('a').text();
										_itens = $(this).find('a').attr('id');
										_usrName = $('body main .puzzle .profile p strong').text();
										$('.box._alert div ul').remove();
										_getMatters = function(){
											_m='';
								    		for (var i=0; i<json.length; i++) {
								    			for (var m=0; m<oMatters.length; m++) {
													if(json[i].matter == oMatters[m]){
														_m=_m+', '+json[i].title;
													}
												}
											}
											return _m.substring(1);
										}
										$('.box._alert .btn i').removeAttr('class').addClass('fa fa-play');
										sqPuzzle.boxConfirm(
											"Teste: <strong>"+ _getMatters() +"</strong><br>Número de questões: <strong>"+ _amount +"</strong><br>Nível: <strong>"+ _levelLabel +"</strong><br>E ai <strong>"+_usrName+"</strong>,<br>vamos começar?",
											"intro"
										);
										return;
									}
								};
							});
							//console.log(oMatters);
						}
						//3. Inicia Quiz
						if( _id == "intro"){
							$('.box._alert').removeClass('show').addClass('hide');
							$('.box._alert div ul').remove();
							sqPuzzle.cronometer('reiniciar');
							sqPuzzle.getPuzzle();
							$('.intro').fadeOut(500,function(){
								$('.questions').fadeIn(500,function(){
									$('.btnConfirm, .btnCancel, .puzzle .questions .steps').show();
								});
							});
						}
						if( _id == "questions" ){
							$('.box._alert').removeClass('hide').addClass('show');
							if( typeof(currAnswer) === 'object'){
								answers.push( currAnswer.val() );
							}else{
								answers.push( currAnswer );
							}
							currQuestion+=1;
							if(currQuestion < _itens ){
								loadQuestion(currQuestion); 
								$('.box._alert').removeClass('show').addClass('hide');
								currAnswer = null;
							}else{
								sqPuzzle.cronometer('stop');
								$('.box._alert .btn.cancel').hide();
								$('.box._alert .btn.forward i').removeAttr('class').addClass('fa fa-check');
								sqPuzzle.boxConfirm(
									"Parabéns <strong>"+_usrName+"</strong>, você concluiu as <strong>"+_amount+"</strong> questões.<br>Clique no botão abaixo para ver seu resultado.",
									"final"
								);
							}
						}
						if( _id == "final"){
							$('.box._alert').removeClass('show').addClass('hide');
							$('.puzzle .questions dl dd ul').html('');
							$('.puzzle .questions .steps').hide();
							var yes=0, no=0;
							for (var a = 0; a < _itens; a++) {
								var _uAns = String(oAnswers[ (sortQuestions[a]-1) ][2]), _cAns = String(answers[a]);
								console.log( 'RES: '+ _uAns.toLowerCase() +' - ANS:' + _cAns.toLowerCase() );
								//console.log( 'RES: '+ typeof( _uAns ) +' - ANS:' + typeof( _cAns ) );
								if(_uAns.toLowerCase()  == _cAns.toLowerCase() ){
									yes+=1;
								}else{
									no+=1;
									console.log(oAnswers);
									console.log( 'Pergunta:\n'+ String(oAnswers[ (sortQuestions[a]-1) ][0]) + '\nResposta Correta: '+ oAnswers[ (sortQuestions[a]-1) ][1][oAnswers[ (sortQuestions[a]-1) ][2]] + '\nResposta do Usuario: ' + oAnswers[ (sortQuestions[a]-1) ][1][answers[a]] );
								}
							}
							$('.puzzle .questions dl dt').html( "Seu teste durou: "+ $("#contenedor #Horas").html() + $("#contenedor #Minutos").html() + $("#contenedor #Segundos").html() + $("#contenedor #Centesimas").html() + "<br><br>Você acertou <strong>"+yes+"</strong><br>Você errou <strong>"+no+"</strong><br> das <strong>"+_itens+"</strong> questões.<br>");
							$('.btnConfirm, .btnCancel').hide();
							$('.btnRestart').show().click(function(){
								oAnswers = [];
								oMatters = [];
								answers = [];
								currQuestion = 0;
								sqPuzzle.usrConfigPuzzle(0);
								$('.btnRestart').hide();
								//location.reload();
							});
						}

						//_reset = setTimeout(function(){ $('.box._alert').removeClass('show').addClass('hide'); },1000);

					}else{
						$('.box._alert').removeClass('show');
						sqPuzzle.clearForm();
					}
				});

				if( _id == "profile" ){
					$('.box > strong').show();
					$('.box._alert input.btnNo').hide();	
					$('.box._alert input.btnYes').val('OK');
				}
				if( _id == "loading" ){
					$('.box > strong').hide();
					$('.box._alert .btn').hide();
					e = userEmail, p = userPswd;
					var serializedData = $form.serialize();
					$.ajax({
						type: "POST",
						url : 'syslogin/controle.php', 
						data: serializedData,
						success : function(response) { 
					    	if(response!='error' && response.length < 20){
					    		$('body main .puzzle .profile').show().find('p strong').html(response);
					    		sqPuzzle.loadJson(response);
					    	}else{
					    		$('.box._alert .btn.cancel').addClass('hide');
					    		$('.box._alert .btn.forward').attr('value','').find('i').removeAttr('class').addClass('fa fa-check');
					    		$('.box._alert .btn.forward').show().click(function(){
						    		sqPuzzle.setUser();
					    		});
					    		console.log(response);
					    		if(response=='error'){
					    			$('.box._alert div p').html('Usuário e/ou Senha inválido. Tente novamente.');
					    		}else{
					    			$('.box._alert div p').html('Falha ao conectar no banco de dados. Tente novamente.');
					    		}
					    	}
						},
						error : function(r) { 
					    	$('body main .puzzle .profile').show().find('p').html('Aconteceu algum erro ao conectar com o banco. Tente novamente.');
						}
					});
					//sqPuzzle.loadJson();
				}
				if(_id == "final" ){
					$('.box._alert input.btnYes').val('RESULTADO');
				}
	        }
    	}
		sqPuzzle.init();
	})();
});