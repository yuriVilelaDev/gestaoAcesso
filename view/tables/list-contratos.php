<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-contratos.php' );

?>
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Descrição</th>
            <th>Cliente</th>
            <th>Data início</th>
            <th>Data término</th>
            <th>Situação</th>
            <th>Aditivo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach($contratos as $contrato){
            //$logo_src = wp_get_attachment_image_url($empresa->IMLogoEmpresa,'thumbnail');
            ?>
            <tr id="ID<?=$contrato->IDContrato?>"> 
                <td><?=$contrato->IDContrato?></td>
                <td class="codigo">
                    <a
                    href="<?=$url_plugin.'&ID='.$contrato->IDContrato?>"
                    data-id="<?=$contrato->IDContrato?>"
                    class="editarContrato">
                        <?=$contrato->CDContrato?>
                    </a>
                </td>
                <td class="descricao">
                    <a
                    href="<?=$url_plugin.'&ID='.$contrato->IDContrato?>"
                    data-id="<?=$contrato->IDContrato?>"
                    class="editarContrato">
                        <?=$contrato->DSContrato?>
                    </a>
                </td>
                <td class="cliente"><?=(isset($contrato->NMRazaoCliente))?$contrato->NMRazaoCliente:'x'?></td>
                <td class="datainicio"><?=date_format(date_create($contrato->DTInicioContrato), 'd/m/Y')?></td>
                <td class="datatermino"><?=date_format(date_create($contrato->DTTerminoContrato), 'd/m/Y')?></td>
                <td class="situacao"><?=Empresas::getGERMetadadoValue($contrato->CDSituacaoContrato)?></td>
                <td class="aditivo"><?=$contrato->NUAditivoContrato?></td>
                <td>
                    <a 
                    href="<?=$url_plugin.'&ID='.$contrato->IDContrato?>"
                    data-id="<?=$contrato->IDContrato?>"
                    class="editarContrato"
                    >
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <a 
                    href="<?=$url_plugin.'&ID='.$contrato->IDContrato?>"
                    data-id="<?=$contrato->IDContrato?>"
                    data-descricao="<?=$contrato->DSContrato?>"
                    class="excluirContrato"
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