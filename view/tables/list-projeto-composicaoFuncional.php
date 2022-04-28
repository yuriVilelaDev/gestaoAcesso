<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-composicao-funcional.php' );
$filtro = array(
    'IDProjeto' => $_REQUEST['ID']
);
$composicaoFuncional = ComposicaoFuncional::getListaRegistros($filtro);
//Projetos::printa($composicaoFuncional);

$composicoesDisponiveis = ComposicaoFuncional::getListaRegistros();
?>

<style>
.lista_composicoes_disponiveis{
    max-width: 300px;
    border: 1px solid #e9e9e9;
    padding: 10px;
    margin: 0 0 10px 0;
    border-radius: 4px;
}
.lista_composicoes_disponiveis .composicaofuncional{
    padding: 10px;
    justify-content: space-around;
    display: flex;
    margin-bottom: 6px;
    cursor: pointer;
}
</style>
<button class="abrir_lista_composicoes_disponiveis">Adicionar</button>
<button class="fechar_lista_composicoes_disponiveis" style="display:none;">fechar</button>
<div class="lista_composicoes_disponiveis" style="display:none;">
    <?php
    foreach($composicoesDisponiveis as $comp){
        ?>
        <div id="ACF_<?=$comp->IDCompFuncional?>" class='composicaofuncional caixa' data-id="<?=$comp->IDCompFuncional?>" data-nome="<?=$comp->NMCompFuncional?>" data-sigla="<?=$comp->NMSiglaCompFuncional?>">
            <div class="sigla"><?=$comp->NMSiglaCompFuncional?></div>
            <div class="nome"><?=$comp->NMCompFuncional?></div>
        </div>
        <?php
    }
    ?>

</div>


<table class="table table-bordered table-hover table-condensed lista_composicoes_vinculadas">
    <thead>
        <tr>
            <th>#</th>
            <th>Sigla</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach($composicaoFuncional as $composicao){
            //$logo_src = wp_get_attachment_image_url($empresa->IMLogoEmpresa,'thumbnail');
            ?>
            <tr id="CF_<?=$composicao->IDCompFuncional?>"> 
                <td><?=$composicao->IDCompFuncional?></td>
                <td class="sigla">
                    <?=$composicao->NMSiglaCompFuncional?>
                </td>
                <td class="nome"><?=$composicao->NMCompFuncional?></td>
                <td>
                    <a 
                    href="#" data-id="<?=$composicao->IDCompFuncional?>"
                    class="desvincularComposicaoFuncional"
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