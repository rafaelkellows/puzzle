$(function(){ 
	(function() {
		var validateEmail = function(email){
		  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		  return re.test(email);
		}
		var validateInput = function(val){
		  var re = /^[^]+$/;
		  return re.test(val);
		};
    	var userEmail, userPswd,$form,e,p;
		var sqPuzzleAdm = {
	        init: function() {
	        	this.getLogin();
	        	this.setChaordic();
	        	this.buttonsAction();
	        },
	        getLogin : function(){
				$form = $('form.login'), e, p;
				$('.btn.signin').click(function(){
	        		e = $('form.login input[name=email]'), p = $('form.login input[name=password]');
					
					if( !validateInput(e.val()) ){
						sqPuzzleAdm.boxConfirm(
							"Informe seu e-mail de acesso.", "affirmative",'', e
						);
						return;
					}
					if( !validateEmail(e.val()) ){
						sqPuzzleAdm.boxConfirm(
							e.val()+ "<br>parece não ser um e-mail válido.", "affirmative",'', e
						);
						return;
					}
					if( !validateInput(p.val()) ){
						sqPuzzleAdm.boxConfirm(
							"Informe sua senha de acesso.", "affirmative",'', p
						);
						return;
					}

					sqPuzzleAdm.boxConfirm(
						"...aguarde...", "loading"
					);
					var serializedData = $form.serialize();
					$.ajax({
						type: "POST",
						url : 'syslogin/controle.php', 
						data: serializedData,
						success : function(response) { 
					    	if(response=='logado');{
					    		location.href = "home.php";
					    	}
						},
						error : function(r) { 
							console.log('Deu Erro'); 
						}
					});

				});
	        },
	        setChaordic : function(){
	        	var l = $('.list');
	        	if(l.length){
	        		l.find('ul li').click(function(){
	        			$(this).next('ul').toggleClass('hide');
	        		});
	        	}
	        },
	        buttonsAction : function(){
	        	var b = $('.btn');

	        	b.each(function(){
	        		if($(this).hasClass('add')){
		        		$(this).click(function(){
		        			totalItens = $('.edit .answers input[type=text]').length;
		        			$('.edit .answers')
		        			.append('<label>Resposta '+eval(totalItens+1)+'</label>')
		        			.append('<input type="text" name="answer[]" value="" /><input type="hidden" name="answerID[]" value="" />');
		        			$('select[name=correctans]')
		        			.append('<option value="'+totalItens+'">'+eval(totalItens+1)+'</option>');
		        		});
	        		}
	        		if($(this).hasClass('remove')){
		        		$(this).click(function(){
		        			totalItens = $('.edit .answers input[type=text]').length;
		        			if(totalItens<=1){ return; }
		        			$('.edit .answers').find('label').last().remove();
		        			$('.edit .answers').find('input[type=hidden]').last().remove();
		        			$('.edit .answers').find('input[type=text]').last().remove();
		        			$('select[name=correctans] option').last().remove();
		        		});
	        		}
	        		if($(this).hasClass('back')){
		        		$(this).click(function(){
		        			window.history.back();
		        		});
	        		}
	        		if($(this).hasClass('save')){
		        		$(this).click(function(e){
		        			e.preventDefault();
		        			if( sqPuzzleAdm.validInfos() ){
		        				$("form").submit();
		        			}
		        		});
	        		}
	        		if($(this).hasClass('upload')){
		        		$(this).click(function(e){
		        			e.preventDefault();
		        			$('input[type=file]').click().change(function(){
							   if (window.File && window.FileReader && window.FileList && window.Blob){
									
									if( !$('#file').val() ){
										sqPuzzleAdm.boxConfirm(
											"Você precisa definir a imagem", "affirmative",'', q
										);
										return false;
									}
									
									var fsize = $('#file')[0].files[0].size; //get file size
									var ftype = $('#file')[0].files[0].type; // get file type
									
									console.log(fsize);
									//allow file types 
									switch(ftype){
										case 'image/jpg': 
							                break;
										case 'image/jpeg': 
							                break;
										case 'image/png': 
							                break;
										case 'image/gif': 
							                break;
							            default:
											sqPuzzleAdm.boxConfirm(
												"Tipo de arquivo não suportado.<br>Selecione arquivos com a extensão .jpg/.jpeg, .png ou .gif", "affirmative",''
											);
											return false;
							        }
									
									//Allowed file size is less than ... |  1 MB = (1048576)
									if(fsize>104857){
										sqPuzzleAdm.boxConfirm(
											"O arquivo selecionado ultrapassa o tamanho permitido.<br>Selecione um arquivo de até 100MB.", "affirmative",''
										);
										return false;
									}
								}
								else{
									sqPuzzleAdm.boxConfirm(
										"Seu navegador não dá suporte para carregar a imagem. Utilize outro navegador.", "affirmative",''
									);
									return false;
								}

								sqPuzzleAdm.boxConfirm(
									"...aguarde...", "loading"
								);
								$form
									.attr("enctype","multipart/form-data").attr('action','ajax_php_file.php')
									.on('submit',(function(e) {
							        e.preventDefault();
							        var file_data = $('#file').prop('files')[0];   
 									var form_data = new FormData();
							        form_data.append('file', file_data);
    								//alert(form_data);                        
									console.log(form_data);
									//return;
							        $.ajax({
							            type:'POST',
							            url: $(this).attr('action'),
							            dataType: 'text',
							            data:form_data,
							            cache:false,
							            contentType: false,
							            processData: false,
							            success:function(data){
							                console.log("success");
							                $('.box._alert').removeClass('show').addClass('hide');
							                $('body main .login.edit .file figure img').attr('src',data);
							                $('input[name=filePath]').val( data );
							                $('body main .login.edit .file figure figcaption').addClass('hide');
							                $form.unbind('submit').removeAttr("enctype").attr('action','item_action.php')
							                //console.log(data);
							            },
							            error: function(data){
							                console.log("error");
							                console.log(data);
							            }
							        });

							    }));

							    $form.submit();

		        			})
		        		});
	        		}
	        	});
	        },
	        validInfos : function(){
	        	var a = $('.edit .answers input[type=text]'),
	        		s = $('.edit select[name=correctans]'),
	        		q = $('.edit textarea[name=question]'),
	        		t = $('.edit input[name=txtcorrectans]');
	        	
	        	if( !validateInput(q.val()) ){
					sqPuzzleAdm.boxConfirm(
						"Você precisa definir uma pergunta", "affirmative",'', q
					);
					return false;
	        	}
	        	if( !validateInput(t.val()) ){
		        	for(var i=0; i < a.length; i++){
						if( !validateInput(a.eq(i).val()) ){
							sqPuzzleAdm.boxConfirm(
								"Você precisa definir pelo menos 2 opções de respostas ou<br>escreva no campo para a resposta escrita", "affirmative",'', a.eq(i)
							);
							return false;
							break;
						}
		        	}
		        	if( !validateInput(s.find(':selected').val()) ){
						sqPuzzleAdm.boxConfirm(
							"Você precisa definir a resposta correta ou<br>escreva no campo para a resposta escrita", "affirmative",'', s
						);
						return false;
		        	}
		        	return true;
		        }else{
		        	return true;
		        }
	        	//$("form").submit();
	        },
	        boxConfirm : function( _dsc, _id, _html, _e){
				$('.box._alert').removeClass('hide').addClass('show').find('div p').html(_dsc);
				if(_html){
					$('.box._alert div p').next().remove();
					$(_html).insertAfter( '.box._alert div p' );
				}

				if( _id == "affirmative" ){
					$('.box > strong').show();
					$('.box._alert input.btnNo').hide();
					$('.box._alert input.btnYes').val('OK');
				}
				if( _id == "loading" ){
					$('.box > strong').html('...AGUARDE...');
					$('.box._alert input.btnNo').hide();
					$('.box._alert input.btnYes').hide();
					//sqPuzzleAdm.loadJson();
				}

				$('.box._alert input[type=button]').unbind('click').click(function(){
					$('.box._alert').removeClass('show');
					if(_e) { _e.focus() };
				})
	        }
	}
		sqPuzzleAdm.init();
	})();
});