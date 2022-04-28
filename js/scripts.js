jQuery(function($) {
	
	$(document).ready(function(){
		$('.plugin-content').hide();
        $('.plugin-content').css('opacity','1');
        $('.plugin-content').show('fast');
		//console.log('montato script global.js');


		$( ".datepicker" ).datepicker({
			dateFormat: "yy-mm-dd",
			altFormat: "yymmdd",
			monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril",
                   "Maio", "Junho", "Julho", "Agosto", "Setembro",
                   "Outubro", "Novembro", "Dezembro" ],
			dayNamesMin: ['Do', 'Se', 'Tr', 'Qa', 'Qi', 'Se', 'Sa']
		});
        // LINHA OPCOES SELECT
        $( ".linha_opcoes .select" ).change(function() {
            $( ".linha_opcoes form .pagina" ).val(1);
            $( ".linha_opcoes form" ).submit();
        });
        // PAGINAÇÃO
        $('.paginacao a').click(function(){
            $pagina_atual 	= $(this).parents('.linha_opcoes').find('.pagina').val();
            $q_paginas 		= $(this).parents('.linha_opcoes').find('.q_paginas').val();
            switch($(this).attr("aria-label")){
                case 'Next':
                    if($pagina_atual < $q_paginas){
                        $pagina_atual++;
                    }else{return false;}
                    break;
                case 'Previous':
                    if($pagina_atual > 1){
                        $pagina_atual--;
                    }else{return false;}
                    break;
                default:
                    $pagina_atual = $(this).attr("aria-label");
            }
            $(this).parents('.linha_opcoes').find('.pagina').val($pagina_atual);
            $(this).parent().parent().find('a').removeClass('select');
            $(this).parent().parent().find('a[aria-label='+$pagina_atual+']').addClass('select');
            $(this).parents('.linha_opcoes').find('form').submit();
            //alert($(this).parents('.tab-pane').attr('id'));
        });


	});
	
	//alert('fui cahamdo')
	// obtem o endereco para o ajax do arquivo handler
	$.fn.getUrlAjax = function(){
		url = $(location).attr('hostname')+$(location).attr('pathname');
		//return 'https://'+url.replace('.php','-ajax.php'); // para hosts de servidores on-line
        return 'http://'+url.replace('.php','-ajax.php'); // para hosts de servidores locais
	}

	$.fn.validaForm = function(elemento){
        $erro = 0;
		$campo = 0;
		$(elemento + ' .require').each(function(){
            if($(this).hasClass('input-text')){
				$campo = $('input[type=text]',this).val();
				if($(this).hasClass('email')){
					$campo = validaEmail($campo);
				}
                if($(this).hasClass('cpfCnpj')){
					$campo = validaCpfCnpj($campo);
				}
			}
            if($(this).hasClass('input-radio'))
                $campo = $('input[type=radio]:checked',this).val();
            if($(this).hasClass('select'))
                $campo = $('select',this).val();
			if($(this).hasClass('check')){
				$campo = $(modal).find('input:checkbox:checked').length;
			}
			if($(this).hasClass('textarea')){
				$campo = $(this).find('textarea').val();
			}
            if(!$campo) {
                $(this).addClass('has-error');
                $('.mensagem_erro',this).show();
                $erro++;
            }
            else{
                $(this).removeClass('has-error');
                $('.mensagem_erro',this).hide();
            }
        });
        if($erro)return false;
		else return true;
	}

	$.fn.formataTelefone = function(num){
		var str = "";
		num = num.replace(/[^0-9]+/g,'');
		num = num.substring(0,11);
		for(i=0;i < num.length; i++){
			if(i==0){str = str +'('};
			if(i==2){str = str +') '};
			if(num.length == 10)
				if(i==6){str = str +'-'};
			if(num.length == 11)
				if(i==7){str = str +'-'};
			str = str+ (num[i].toString());
		}
		return str;
	}

	/* FORMATA CNPJ */
	$.fn.formataCNPJ = function(num){
		var str = "";
		num = num.replace(/[^0-9]+/g,'');
		num = num.substring(0,14);
		for(i=0;i < num.length; i++){
			if(i==2 || i==5){str = str +'.'};
			if(i==8){str = str +'/'};
			if(i==12){str = str +'-'};
			str = str+ (num[i].toString());
		}
		return str;
	}

	/* FORMATA CEP */
	$.fn.formataCEP = function(num){
		var str = "";
		num = num.replace(/[^0-9]+/g,'');
		num = num.substring(0,8);
		for(i=0;i < num.length; i++){
			if(i==5){str = str +'-'};
			str = str+ (num[i].toString());
		}
		return str;
	}


	function validaEmail(email){
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  		return regex.test(email);
	}

    function validaCpfCnpj(num){
		num = num.replace(/[^0-9]+/g,'');
        if( num.length == 14 )
            return 1;
        else 
            return 0;
	}

    // executa quando input clicado com enter
    $.fn.enterKey = function (fnc, mod) {
		return this.each(function () {
			$(this).keypress(function (ev) {
				var keycode = (ev.keyCode ? ev.keyCode : ev.which);
				if ((keycode == '13' || keycode == '10') && (!mod || ev[mod + 'Key'])) {
					fnc.call(this, ev);
				}
			})
		})
	}


});