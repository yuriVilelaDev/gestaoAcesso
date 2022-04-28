
window.onload = function plataforma(){
    jQuery("#conteudo").load("/ACTCON/wp-content/plugins/actcon-sigsa/view/tables/list-plataforma.php");
}

jQuery('#excluirPlataforma').click(function(){
    alert("oi");
    // if( $('input[name=ID]',form).length > 0 ){
    //     var $delete = confirm("Deseja excluir o projeto: " + $('#nome',form).val() + " ?");
    //     if ($delete == true)
    //     excluirPlataforma( $('input[name=ID]',form).val(), $('#nome',form).val() );
    //     return true;
    // }else{
    //     var $delete = confirm("Deseja excluir o plataforma: " + $(this).attr('data-nome') + " ?");
    //     if ($delete == true)
    //     excluirPlataforma( $(this).attr('data-id'), $(this).attr('data-nome') );
    //     return false;
    // }
});
$(document).ready(function(){

    function excluirPlataforma(paltaformaid){
        alert("to no delete");
        $.ajax({
            beforeSend: function() {/*$(modal).addClass('processando');*/},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Plataforma',
                funcao              : 'deletePlataforma',
                id                  : paltaformaid
            }
        });
    }


   
});

