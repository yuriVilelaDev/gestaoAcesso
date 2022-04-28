<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-composicao-funcional.php' );
?>
<table class="table table-bordered table-hover table-condensed">
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
            <tr id="ID<?=$composicao->IDCompFuncional?>"> 
                <td><?=$composicao->IDCompFuncional?></td>
                <td class="nome">
                    <a
                    href="<?=$url_plugin.'&ID='.$composicao->IDCompFuncional?>"
                    data-id="<?=$composicao->IDCompFuncional?>"
                    class="editarComposicaoFuncional">
                        <?=$composicao->NMSiglaCompFuncional?>
                    </a>
                </td>
                <td class="nome"><?=$composicao->NMCompFuncional?></td>
                <td>
                    <a 
                    href="<?=$url_plugin.'&ID='.$composicao->IDCompFuncional?>"
                    data-id="<?=$composicao->IDCompFuncional?>"
                    class="editarComposicaoFuncional"
                    >
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <a 
                    href="<?=$url_plugin.'&ID='.$composicao->IDCompFuncional?>"
                    data-id="<?=$composicao->IDCompFuncional?>"
                    data-descricao="<?=$composicao->DSProjeto?>"
                    class="excluirComposicaoFuncional"
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