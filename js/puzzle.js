$(function(){ 
	(function() {
	    var sortQuestions = [],  sortAnswers = [];
    	var _itens = 0, _maxCalcQuestions = 5; //questions amount limit set on "starting" obj;
		var sqPuzzle = {
	        init: function() {
	        	this.loading();
	        	this.starting();
	        	//this.loadJson('historia');
	        },
	        loading : function(){
	        	$(window).load(function(){
					$('.loading').removeClass('active');
	        	});
	        },
	        starting : function(){
				
				$('.puzzle .intro select').change(function(){
					if( $('select[name=themeSelect]').find(':selected').val() != '' && $('select[name=qAmount]').find(':selected').val() != '' ){
	        			
	        			var _v = $('select[name=themeSelect]').find(':selected').val(), _t = $('select[name=themeSelect]').find(':selected').text();
						_itens = $('select[name=qAmount]').val();
						$('.btnBegin').prop('disabled',false).removeAttr("disabled").click(function(){
							answer = confirm("Seu teste de "+ _t.toUpperCase() +" irá começar. Pronto pra começar?");
							if (answer){
								sqPuzzle.loadJson( _v, _t );
								$('.puzzle .intro').fadeOut('slow',function(){
									$('.puzzle .questions').fadeIn('fast');
								});
							}
						});
						return;
					}

					if( $('select[name=themeSelect]').find(':selected').val() != '' ){
	        			var _v = $('select[name=themeSelect]').find(':selected').val();
						sqPuzzle.loadJson( _v, '' );
					};
				})

	        },
	        loadJson : function(theme, title) {
				$.ajax({
					url : 'js/sqPuzzle.js',
					dataType : 'json',	
					success : function(json) { 
						//console.log(theme);
						switch(theme) {
						    case 'historia':
								sqPuzzle.getPuzzle( json.historia, title);
						        break;
						    case 'matematica':
								sqPuzzle.getPuzzle( json.matematica, title);
						        break;
						    default:
						        return;
						}
					},
					error : function(r) { 
						console.log('Deu Erro');
					}
				});
	        },
	        getPuzzle: function(data, t) {
	        	var questions = data.questions;
	        	var answers = [], currQuestion = 0; currAnswer = null;

	        	// Calc Total itens and set the select[name=qAmount]'s options
	        	if(t.length === 0){
	        		$('select[name=qAmount]').html('<option value="">Quantas perguntas você quer responder?</option>');
	        		if( eval(data.questions.length / _maxCalcQuestions) < 1 ){
		        		$('select[name=qAmount]').append('<option value="'+data.questions.length+'">'+data.questions.length+'</option>');
	        		}else{
	        			for(var i = 1; i <= _maxCalcQuestions; i++ ){
		        			$('select[name=qAmount]').append('<option value="'+(i*Math.floor(data.questions.length / _maxCalcQuestions))+'">'+(i*Math.floor(data.questions.length / _maxCalcQuestions))+'</option>');
	        			}
	        			if( data.questions.length % _maxCalcQuestions ){
		        			$('select[name=qAmount]').append('<option value="'+data.questions.length+'">'+data.questions.length+'</option>');
	        			}
	        		}
		        	return;
	        	}

	        	//Adjust _itens variable if Questions Amout to be Higher
	        	if( _itens > data.questions.length ){
					_itens = data.questions.length;
	        	}
	        	//Random Questions
	        	var rndQuestions = function (_length){
	        		return Math.floor((Math.random() * _length) + 1);
	        	}
	        	while (sortQuestions.length < data.questions.length) {
	        		_rq = rndQuestions( data.questions.length );

					if( sortQuestions.indexOf( _rq ) === -1 ){
						sortQuestions.push(_rq);
					}
	        	}
	        	console.log(sortQuestions);
	        	
	        	var loadQuestion = function(qIndex){
		        	$('.puzzle .questions p.theme').html( 'Prova de <strong>'+t+'</strong> <br>com <strong>' + _itens + '</strong> questões.');
		        	$('.puzzle .questions p.steps').html( 'Você está na questão <strong>'+(qIndex+1)+'</strong> de <span>'+_itens+'</span>.' );
		        	$('.puzzle .questions dl dt').html( (currQuestion+1) +') '+ data.questions[ (sortQuestions[qIndex]-1) ][0] );
					$('.puzzle .questions dl dd ul').html('');

					if(!data.questions[ (sortQuestions[qIndex]-1) ][1].length){
		        		$('.puzzle .questions dl dd ul').append( ' <li style="text-align:center; "><input id="ans_00" type="text" name="ans" value=""> <label for="ans_00"></label></li>' );
			        	$('.puzzle .questions dl dd ul li input').keyup(function() {
						  	currAnswer = $(this);
						});
					}else{
						sortAnswers.splice(0,sortAnswers.length);
			        	while (sortAnswers.length < data.questions[ (sortQuestions[qIndex]-1) ][1].length) {
			        		_rq = rndQuestions(data.questions[ (sortQuestions[qIndex]-1) ][1].length);

							if( sortAnswers.indexOf( _rq ) === -1 ){
								sortAnswers.push(_rq);
							}
			        	}
			        	console.log(sortAnswers);

			        	for (var i = 0; i < data.questions[ (sortQuestions[qIndex]-1) ][1].length; i++) {
			        		$('.puzzle .questions dl dd ul').append( ' <li>'+(i+1)+'. <input id="ans_0'+(sortAnswers[i]-1)+'" type="radio" name="ans" value="'+(sortAnswers[i]-1)+'"> <label for="ans_0'+(sortAnswers[i]-1)+'">'+ data.questions[ (sortQuestions[qIndex]-1) ][1][(sortAnswers[i]-1)] +'</label></li>' );
			        	}
			        	
			        	$('.puzzle .questions dl dd ul li input').click(function(){
			        		currAnswer = $(this);
			        	});
					}
	        	}

				loadQuestion(currQuestion);

	        	$('.btnConfirm').click(function(){
	        		if(!currAnswer){
						alert ("Você precisa escolher uma resposta!"); return;
	        		}
					answer = confirm("Você tem certeza da sua resposta?");
					if (answer){
						//alert("Você escolheu a resposta: "+ currAnswer.parent().find('label').text());
						answers.push(currAnswer.val());
						currQuestion+=1;
						if(currQuestion < _itens ){
							loadQuestion(currQuestion); 
						}else{
							alert ("Seu teste acabou. Clique em OK para ver seu resultado");
							$('.puzzle .questions dl dd ul').html('');
							$('.puzzle .questions .steps').hide();
							var yes=0, no=0;
							for (var a = 0; a < _itens; a++) {
								var _uAns = String(data.questions[ (sortQuestions[a]-1) ][2]),
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
					} else {
						alert ("Você clicou no botão CANCELAR. Pense bem na resposta antes de escolher novamente.");
						$('.puzzle .questions dl dd ul li input').prop('checked', false).removeAttr("checked");
						currAnswer = null;
					}
	        	});
	        	$('.btnCancel').click(function(){
					$('.puzzle .questions dl dd ul li input').prop('checked', false).removeAttr("checked"); currAnswer = null;
	        	});
	        }
    	}
		sqPuzzle.init();
	})();
});