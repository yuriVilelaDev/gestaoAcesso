<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-projetos.php' );
$filtro = array(
    'IDProjeto' => $_REQUEST['ID']
);
$chaveAutenticacao = ChaveAutenticacao::getListaRegistros($filtro);
//ChaveAutenticacao::printa($chaveAutenticacao);
?>

<style>
#form_cadastro_chaveAutenticacao{max-width:300px;}

</style>

<form class="" id="form_cadastro_chaveAutenticacao">
    <div class="input-group">
        <input class="form-control chaveAutenticacao" 
        aria-label="Text input with segmented button dropdown"
        value="<?=($chaveAutenticacao)?$chaveAutenticacao->NUChaveAutenticacao:''?>"
        data-id="<?=($chaveAutenticacao)?$chaveAutenticacao->IDChaveAutenticacao:''?>"
        >
        <div class="input-group-btn"> 
            <button type="button" class="btn btn-default gerarChaveAutenticacao">Gerar chave</button> 
        </div> 
    </div>
    <br>
    <div class="input-group">
        <button type="button" class="btn btn-default salvarChaveAutenticacao">Gravar</button>
    </div>
</form>
<br>
<hr>
<div>
    <p>Dados da chave de autenticao JSON</p>
    <?php
    $filtro = array(
        'chave' => $chaveAutenticacao->NUChaveAutenticacao
    );
    $dados = ChaveAutenticacao::getDados($filtro);
    $dados = json_decode($dados);
    printf("<pre>%s</pre>",print_r($dados,true));
    ?>
</div>
