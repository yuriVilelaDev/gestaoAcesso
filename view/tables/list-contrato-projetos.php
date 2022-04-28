<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-projetos.php' );
//Contratos::printa($projetos);
?>
<table class="table table-bordered table-hover table-condensed table-fundo-branco">
    <thead>
        <tr>
            <th>#</th>
            <th>Logo</th>
            <th>Projeto</th>
            <th>Vigência do projeto</th>
            <th>Situação</th>
            <th>Outros dados</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach($projetos  as $projet){
            // Dados do projeto
            $projeto = (object)$projet;
            if( isset($projeto->DTInicioContratoProjeto ) ){
                $data = date_create($projeto->DTInicioContratoProjeto);
                $datainicio =  date_format($data, 'd/m/Y');
            }else $datainicio = '';
            if( isset($projeto->DTTerminoContratoProjeto ) ){
                $data = date_create($projeto->DTTerminoContratoProjeto);
                $datatermino =  date_format($data, 'd/m/Y');
            }else $datatermino = '';
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
                    <div>
                        <a
                        href="<?=$url_plugin.'&ID='.$projeto->IDProjeto?>"
                        data-id="<?=$projeto->IDProjeto?>"
                        class="editarProjeto">
                            <?=$projeto->NMProjeto?>
                        </a>
                    </div>
                    <div>
                        <?=$projeto->DSProjeto?>
                    </div>
                </td>
                <td class="datas">
                    <?=$datainicio?> até <?=$datatermino?>
                </td>
                <td class="situacao">
                <i class="fa fa-toggle-<?=$projeto->STProjeto?'on':'off';?>" aria-hidden="true"></i>
                </td>
                <td>

                </td>
            </tr>
            
            <?php
        }
    ?>
    </tbody>
</table>