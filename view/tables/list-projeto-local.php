<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-projetos.php' );
$filtro = array(
    'IDProjeto' => $_REQUEST['ID']
);
$locais_cadastrados = Local::getListaRegistros($filtro);
//Local::printa($locais_cadastrados);
?>

<style>
.pesquisa_projeto_local{max-width: 800px;position: relative;}
.pesquisa_projeto_local .lista_suspensa{background-color:#fff;padding: 4px;border: solid 1px #d9d9d9;width: 100%;z-index: 10;}
.pesquisa_projeto_local .lista_suspensa li{list-style: none;cursor:pointer;}
.lista_locais_vinculados{max-width: 800px;background-color:#fff;margin:20px 0;padding:16px}
.lista_locais_vinculados table{margin:0px;}

</style>

<div class="pesquisa_projeto_local">
    <div class="input-group">
        <input class="form-control pesquisa_local" aria-label="Text input with segmented button dropdown">
        <div class="input-group-btn"> 
            <button type="button" class="btn btn-default">Pesquisar</button> 
        </div> 
    </div>
    <div class="lista_suspensa" style="display:none"></div>
</div>

<?php
            
    if($locais_cadastrados){ 
    ?>
        <div class="lista_locais_vinculados caixa">
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="3">Locais cadastrados no projeto</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($locais_cadastrados as $local){
                        $enderecoArray = json_decode($local->EDEnderecoLocalJSON);
                        $endereco = '';
                        //Local::printa($enderecoArray);
                        if($enderecoArray->logradouro)$endereco .= $enderecoArray->logradouro;
                        if($enderecoArray->numero)$endereco .= ','.$enderecoArray->numero;
                        if($enderecoArray->complemento)$endereco .= ' ('.$enderecoArray->complemento.')';
                        if($enderecoArray->bairro)$endereco .= ', '.$enderecoArray->bairro;
                        if($enderecoArray->localidade)$endereco .= ', '.$enderecoArray->localidade;
                        if($enderecoArray->uf)$endereco .= '-'.$enderecoArray->uf;
                        ?>
                        <tr id="LOCAL_<?=$local->IDLocal?>"> 
                            <td class="nome"><?=$local->NMLocal?></td>
                            <td class="endereco"><?=$endereco?></td>
                            <td>
                                <a 
                                href="#" data-id="<?=$local->IDLocal?>"
                                class="desvincularLocal"
                                >
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>

                        <?php
                    }
                ?>
                </tbody>
            </table>    
        </div>
    <?php
    }
?>