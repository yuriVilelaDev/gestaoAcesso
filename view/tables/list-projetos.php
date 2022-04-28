<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-projetos.php' );
?>
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>#</th>
            <th>Logo</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Situação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach($projetos as $projeto){
            //$logo_src = wp_get_attachment_image_url($empresa->IMLogoEmpresa,'thumbnail');
            ?>
            <tr id="ID<?=$projeto->IDProjeto?>"> 
                <td><?=$projeto->IDProjeto?></td>
                <td class="logo">
                    <?php 
                        $logo_src = wp_get_attachment_image_url($projeto->IMLogoProjeto,'thumbnail');
                        if($logo_src){
                            echo '<img src="'.$logo_src.'"/ width="60">';
                        }
                    ?>
                </td>
                <td class="nome">
                    <a
                    href="<?=$url_plugin.'&ID='.$projeto->IDProjeto?>"
                    data-id="<?=$projeto->IDProjeto?>"
                    class="editarProjeto">
                        <?=$projeto->NMProjeto?>
                    </a>
                </td>
                <td class="descricao"><?=$projeto->DSProjeto?></td>
                <td class="situacao">
                <i class="fa fa-toggle-<?=$projeto->STProjeto?'on':'off';?>" aria-hidden="true"></i>
                </td>
                <td>
                    <a 
                    href="<?=$url_plugin.'&ID='.$projeto->IDProjeto?>"
                    data-id="<?=$projeto->IDProjeto?>"
                    class="editarProjeto"
                    >
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <a 
                    href="<?=$url_plugin.'&ID='.$projeto->IDProjeto?>"
                    data-id="<?=$projeto->IDProjeto?>"
                    data-descricao="<?=$projeto->DSProjeto?>"
                    class="excluirProjeto"
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