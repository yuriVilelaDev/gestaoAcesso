<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-contratos.php' );
?>
<table class="table table-bordered table-hover table-condensed table-fundo-branco">
    <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Descrição</th>
            <th>Data início</th>
            <th>Data término</th>
            <th>Situação</th>
            <th>Aditivo</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach($cliente->contratos as $contrato){
            if( isset($contrato->DTInicioContrato ) ){
                $data = date_create($contrato->DTInicioContrato);
                $datainicio =  date_format($data, 'd/m/Y');
            }else $datainicio = '';
            if( isset($contrato->DTTerminoContrato ) ){
                $data = date_create($contrato->DTTerminoContrato);
                $datatermino =  date_format($data, 'd/m/Y');
            }else $datatermino = '';
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
                <td class="datainicio"><?=$datainicio?></td>
                <td class="datatermino"><?=$datatermino?></td>
                <td class="situacao"><?=Empresas::getGERMetadadoValue($contrato->CDSituacaoContrato)?></td>
                <td class="aditivo"><?=$contrato->NUAditivoContrato?></td>
            </tr>
            <?php
        }
    ?>
    </tbody>
</table>