$(function(){ 
	(function() {
	    var sortQuestions = [], sortAnswers = [], answers = [], oAnswers = [], oMatters = [];
    	var _itens = 0, _level = '', _levelLabel = '', _maxCalcQuestions = 3; //questions amount limit set on "starting" obj;
		var _v,_t,_l,_amount,loadQuestion,rndQuestions,json;
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
	        setUser : function(){
				
				$('.btnBegin').click(function(){
					sqPuzzle.boxConfirm(
						"Diga seu e-mail:<br><em class='hide'>verifique a informação novamente<br></em><input maxlength='40' type='text' name='email'>",
						"profile"
					);
				});
				return;
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
	        loadJson : function(theme, title) {
				$.ajax({
					url : 'json.php',
					dataType : 'json',	
					success : function(data) {
						json = data;
						sqPuzzle.starting();
					},
					error : function(r) { 
						console.log('Deu Erro');
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

	        	$('.btnConfirm').unbind('click').click(function(){
	        		if(!currAnswer){
						sqPuzzle.boxConfirm( "Você precisa definir uma resposta!", "affirmative" );
						return;
	        		}
	        		$('.box > strong').hide();
					sqPuzzle.boxConfirm(
						"Você tem certeza da sua resposta?", "questions"
					);

					return;
					//answer = confirm("Você tem certeza da sua resposta?");
					if (answer){

					} else {
						alert ("Você clicou no botão CANCELAR. Pense bem na resposta antes de escolher novamente.");
						$('.puzzle .questions dl dd ul li input[type=radio]').prop('checked', false).removeAttr("checked");
						$('.puzzle .questions dl dd ul li input[type=number]').val('');
						currAnswer = null;
					}
	        	});
	        	$('.btnCancel').unbind('click').click(function(){
					$('.puzzle .questions dl dd ul li input[type=radio]').prop('checked', false).removeAttr("checked");
					$('.puzzle .questions dl dd ul li input[type=number]').val('');
					currAnswer = null;
	        	});
	        },
	        usrConfigPuzzle : function(step){
				switch(step){
					case 0: //Setting Matter(s)
			        	var _ul = '<ul class="matter">';
				    	for (var i=0; i<json.length; i++) {
					    	if( json[i].questions ){
					    		_ul += '<li><a href="javascript:void(0);" id="'+json[i].matter+'"><span>'+json[i].title+'</span><img src="'+json[i].img+'"></a></li>';
					    	}
				    	}
			        	_ul += '</ul>';
						sqPuzzle.boxConfirm(
							"Escolha abaixo as matérias do teste.", "materias",_ul
						);
						$('.box._alert input.btnNo').hide();
						$('.box._alert input.btnYes').show().val("COMEÇAR");
						$('body main .puzzle .box > div > ul li').click(function(){
							//$('body main .puzzle .box > div > ul li').removeClass('active');
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
			    		console.log(oLevels);
			    		if( counter > 1 ){
							_ul += '<li><a href="javascript:void(0);" id="3"><span>Aleatório</span></a></li>';
						}
			        	_ul += '</ul>';
						sqPuzzle.boxConfirm(
							"Escolha o nível das perguntas do teste.", "materias",_ul
						);
						$('.box._alert input.btnNo').hide();
						$('.box._alert input.btnYes').show().val("COMEÇAR");
						$('body main .puzzle .box > div > ul li').click(function(){
							$('body main .puzzle .box > div > ul li').removeClass('active');
							$(this).addClass('active');
						})
					break;
					
					case 2: //Setting Question Amount divided by (_maxCalcQuestions)
				    	for (var i=0; i<json.length; i++) {
			    			for (var m=0; m<oMatters.length; m++) {
								if(json[i].matter == oMatters[m]){

								//if(json[i].matter == _v){

									//console.log( json[i].questions[0] )
									if(_level != "" && _level != 3){
					        			for(var _i = 0; _i < json[i].questions[_level].length; _i++){
											oAnswers.push( json[i].questions[_level][_i] );
					        			}
						        		var _qntItensByLevel = oAnswers.length;
									}else{
						        		var _qntItensByLevel = 0;
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
			        	//console.log(sortQuestions);
			        	//return;

						sqPuzzle.boxConfirm(
							"Escolha a quantidade de perguntas.", "materias",_ul
						);
						$('.box._alert input.btnNo').hide();
						$('.box._alert input.btnYes').show().val("COMEÇAR");
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
				}
				
	        },
	        boxConfirm : function( _dsc, _id, _html ){
				$('.box._alert input.btnNo').show();
				$('.box._alert input.btnYes').val('SIM');

				$('.box._alert').removeClass('hide').addClass('show').find('div p').html(_dsc);
				if(_html){
					$('.box._alert div p').next().remove();
					$(_html).insertAfter( '.box._alert div p' );
				}
				
				$('.box._alert input[type=button]').unbind('click').click(function(){
					
					if( $(this).val() == "SIM" || $(this).val() == "OK" || $(this).val() == "ENTRAR" || $(this).val() == "COMEÇAR" || $(this).val() == "RESULTADO" ){
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
						//1. Acesso do Usuario
						if( _id == "profile" ){
							// Tela de Digite seu EMAIL
							if( $('.box._alert input[name=email]').length ){
								if( validateEmail($('.box._alert input[name=email]').val()) ){
									$('.box._alert input[name=email]').removeClass('error').prev('em').addClass('hide').removeClass('error show');
									userEmail = $('.box._alert input[name=email]').val();
									$.ajax({
										url : 'js/user.js', dataType : 'json',	
										success : function(json) { 
									    	for (var i=0; i<json.length; i++) {
										    	if(json[i].email == userEmail){
										    		$('body main .puzzle .profile').show().find('p strong').html(json[i].name);
													sqPuzzle.boxConfirm(
														json[i].name+", digite sua senha:<br><em class='hide'>verifique a informação novamente<br></em><input maxlength='40' type='password' name='password'>",
														"profile"
													);
													$('.box._alert input.btnYes').val('ENTRAR');
										    		return;
										    		break;
										    	}
									    	}
									    	$('.box._alert input[name=email]').addClass('error').focus().prev('em').html('este e-mail não consta na base de dados.<br>').removeClass('hide').addClass('error show');
										},
										error : function(r) { console.log('Deu Erro'); }
									});
									

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
									sqPuzzle.boxConfirm(
										"aguarde...", "loading"
									);

									//$('body main .puzzle .profile').show().find('p strong').html(userEmail);
									//$('body main .puzzle .intro select').show();
									//sqPuzzle.loadJson();
									//alert('userEmail'+userEmail+' - userPswd'+userPswd);
								}else{
									$('.box._alert input[name=email]').addClass('error').focus().prev('em').removeClass('hide').addClass('error show');
									return;
								}
							}
						}
						//3. Inicia Quiz
						if( _id == "intro"){
							$('.box._alert').removeClass('show').addClass('hide');
							$('.box._alert div ul').remove();
							sqPuzzle.getPuzzle();
							$('.intro').fadeOut(500,function(){
								$('.questions').fadeIn(500);
							});
						}
						if( _id == "questions" ){
							$('.box._alert').removeClass('show').addClass('hide');
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
								location.reload();
							});
						}

						//_reset = setTimeout(function(){ $('.box._alert').removeClass('show').addClass('hide'); },1000);

					}else{
						$('.box._alert').removeClass('show');
						//$('.intro').fadeIn(500);
					}
				});

				if( _id == "affirmative" ){
					$('.box > strong').show();
					$('.box._alert input.btnNo').hide();
					$('.box._alert input.btnYes').val('OK');
				}
				if( _id == "profile" ){
					$('.box > strong').show();
					$('.box._alert input.btnNo').hide();
					$('.box._alert input.btnYes').val('OK');
				}
				if( _id == "loading" ){
					$('.box > strong').hide();
					$('.box._alert input.btnNo').hide();
					$('.box._alert input.btnYes').hide();
					sqPuzzle.loadJson();
				}
				if(_id == "final" ){
					$('.box._alert input.btnYes').val('RESULTADO');
				}
	        }
    	}
		sqPuzzle.init();
	})();
});