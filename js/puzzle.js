$(function(){ 
	(function() {
	    var sortQuestions = [],  sortAnswers = [], answers = [], oAnswers = [];
    	var _itens = 0, _level = '', _levelLabel = '', _maxCalcQuestions = 5; //questions amount limit set on "starting" obj;
		var _v,_t,_l,loadQuestion,rndQuestions;
    	var currQuestion = 0; currAnswer = null;
		var sqPuzzle = {
	        init: function() {
	        	this.loading();
	        	//this.starting();
	        	this.loadJson();
	        },
	        loading : function(){
	        	$(window).load(function(){
					$('.loading').removeClass('active');
	        	});
	        },
	        starting : function(json){
			    $('select[name=themeSelect]').html("<option value=''>Qual matéria você vai estudar?</option>");
		    	for (var i=0; i<json.length; i++) {
			    	$('select[name=themeSelect]').append("<option value='"+json[i].matter+"''>"+json[i].title+"</option>");
		    	}

		    	//console.log(json);
		    	$('select[name=themeSelect]').change(function(){
	        		var _v = $('select[name=themeSelect]').find(':selected').val();
		    		$('select[name=levelSlct]').attr('disabled','disabled').html("<option value=''>Nivel de Dificuldade</option>");
		    		$('select[name=qAmount]').attr('disabled','disabled').html("<option value=''>Quantas perguntas você quer responder?</option>");
		    		for (var i=0; i<json.length; i++) {
						if(json[i].matter == $(this).val()){
							var count = 2; // 0: Facil, 1: Médio e 2: Difícil
							for (var y=0; y<=count; y++) {
								if(json[i].questions[y].length > 0){
									switch(y){
										case 0:
										_oTitle = "Fácil";
										break;
										case 1:
										_oTitle = "Médio";
										break;
										case 2:
										_oTitle = "Difícil";
										break;
									}
									$('select[name=levelSlct]').removeAttr('disabled').append("<option value='"+y+"'>"+_oTitle+"</option>");
								}
							}
						}
		    		}
		    		if( $('select[name=levelSlct] option').length > 2 ){
						$('select[name=levelSlct]').append("<option value='3'>Aleatório</option>");
					}
		    	});
		    	//
		    	$('select[name=levelSlct]').change(function(){
        			_level = $(this).val();
		    		$('select[name=qAmount]').attr('disabled','disabled').html("<option value=''>Quantas perguntas você quer responder?</option>");
		    		for (var i=0; i<json.length; i++) {
						if(json[i].matter == $('select[name=themeSelect]').find(':selected').val()){
							//console.log( json[i].questions[0] )
							if($(this).val() != "" && $(this).val() != 3){
			        			for(var _i = 0; _i < json[i].questions[$(this).val()].length; _i++){
									oAnswers.push( json[i].questions[$(this).val()][_i] );
			        			}
				        		var _qntItensByLevel = json[i].questions[$(this).val()].length;
							}else{
				        		var _qntItensByLevel = 0;
				        		for(var _q = 0; _q < Object.keys(json[i].questions).length; _q++){
				        			for(var _i = 0; _i < json[i].questions[_q].length; _i++){
										oAnswers.push( json[i].questions[_q][_i] );
				        			}
				        			_qntItensByLevel+=json[i].questions[_q].length;
				        		}
				        	}
				        	// Calc Total itens and set the select[name=qAmount]'s options
			        		$('select[name=qAmount]').html('<option value="">Quantas perguntas você quer responder?</option>');
			        		if( eval(_qntItensByLevel / _maxCalcQuestions) < 1 ){
				        		$('select[name=qAmount]').removeAttr('disabled').append('<option value="'+_qntItensByLevel+'">'+_qntItensByLevel+'</option>');
			        		}else{
			        			for(var i = 1; i <= eval(_qntItensByLevel / _maxCalcQuestions); i++ ){
				        			$('select[name=qAmount]').removeAttr('disabled').append('<option value="'+(i*_maxCalcQuestions)+'">'+ (i*_maxCalcQuestions) +'</option>');
			        			}
			        			if( _qntItensByLevel % _maxCalcQuestions ){
				        			$('select[name=qAmount]').removeAttr('disabled').append('<option value="'+_qntItensByLevel+'">'+_qntItensByLevel+'</option>');
			        			}
			        		}
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
				        	console.log(sortQuestions);
						}
		    		}

		    	})

				$('.puzzle .intro select').change(function(){
					if( $('select[name=themeSelect]').find(':selected').val() != '' && $('select[name=qAmount]').find(':selected').val() != '' ){
	        			
	        			_t = $('select[name=themeSelect]').find(':selected').text(),
	        			_l = $('select[name=levelSlct]').find(':selected').text(), 
						_itens = $('select[name=qAmount]').val();

						$('.btnBegin').prop('disabled',false).removeAttr("disabled").click(function(){
							
							sqPuzzle.boxConfirm(
								"Teste: <strong>"+ _t +"</strong><br>Questões: <strong>"+ _itens +"</strong><br>Nível: <strong>"+ _l +"</strong> <br>Pronto pra começar?",
								"intro"
							);

						});
						return;
					}else{
						$('.btnBegin').prop('disabled',true).attr("disabled","disabled");
					}

					/*if( $('select[name=themeSelect]').find(':selected').val() != '' ){
	        			var _v = $('select[name=themeSelect]').find(':selected').val();
						sqPuzzle.loadJson( _v, '' );
					};*/
				})

	        },
	        loadJson : function(theme, title) {
				$.ajax({
					url : 'js/educacaoFisica.js',
					dataType : 'json',	
					success : function(json) { 
						sqPuzzle.starting(json);
					},
					error : function(r) { 
						console.log('Deu Erro');
					}
				});
	        },
	        getPuzzle: function() {
	        	loadQuestion = function(qIndex){

		        	$('.puzzle .questions p.theme').html( 'Prova de <strong>'+_t+'</strong> <br>com <strong>' + _itens + ' questões</strong>.<br> Nível: <strong>'+ _l +'</strong>');
		        	$('.puzzle .questions p.steps').html( 'Você está na questão <strong>'+(qIndex+1)+'</strong> de <span>'+_itens+'</span>.' );
		        	$('.puzzle .questions dl dt').html( (currQuestion+1) +') '+ oAnswers[ (sortQuestions[qIndex]-1) ][0] );
					$('.puzzle .questions dl dd ul').html('');
		        	
					console.log(oAnswers[ (sortQuestions[qIndex]-1) ][1]);

					if(!oAnswers[ (sortQuestions[qIndex]-1) ][1].length){
		        		$('.puzzle .questions dl dd ul').append( ' <li style="text-align:center; "><input id="ans_00" type="number" name="ans" value=""> <label for="ans_00"></label></li>' );
			        	$('.puzzle .questions dl dd ul li input').keyup(function() {
						  	currAnswer = $(this);
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
			        		currAnswer = $(this);
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
	        boxConfirm : function( _dsc, _id ){
				$('.box._alert input.btnNo').show();
				$('.box._alert input.btnYes').val('SIM');

				$('.box._alert').show().removeClass('hide').addClass('show').find('div p').html(_dsc);
				
				$('.box._alert input').unbind('click').click(function(){
					
					if( $(this).val() == "SIM" ){

						$('.box._alert').removeClass('show').addClass('hide');
						
						if( _id == "intro"){
							sqPuzzle.getPuzzle();
							$('.intro').fadeOut(500,function(){
								$('.questions').fadeIn(500);
							});
						}
						if( _id == "questions" ){
							answers.push(currAnswer.val());
							currQuestion+=1;
							if(currQuestion < _itens ){
								loadQuestion(currQuestion); 
								$('.box._alert').removeClass('show').addClass('hide');
								currAnswer = null;
							}else{
								alert ("Seu teste acabou. Clique em OK para ver seu resultado");
								$('.puzzle .questions dl dd ul').html('');
								$('.puzzle .questions .steps').hide();
								var yes=0, no=0;
								for (var a = 0; a < _itens; a++) {
									var _uAns = String(oAnswers[ (sortQuestions[a]-1) ][2]),
									_cAns = String(answers[a]);
									//console.log( 'RES: '+ typeof( _uAns ) +' - ANS:' + typeof( _cAns ) );
									if(_uAns.toLowerCase()  == _cAns.toLowerCase() ){
										yes+=1;
									}else{
										no+=1;
									}
								}
								$('.puzzle .questions dl dt').html( "Você acertou <strong>"+yes+"</strong> e errou <strong>"+no+"</strong><br> das <strong>"+_itens+"</strong> questões.");
								$('.btnConfirm, .btnCancel').hide();
								$('.btnRestart').show().click(function(){
									location.reload();
								});
							}
						}

						_reset = setTimeout(function(){ $('.box._alert').hide().removeClass('hide'); },1000);

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
	        }
    	}
		sqPuzzle.init();
	})();
});