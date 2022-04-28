<?php
$lista_CDTipoContrato = Empresas::getGERMetadadoOpcoes('CDTipoContrato');
$lista_CDSituacaoContrato = Empresas::getGERMetadadoOpcoes('CDSituacaoContrato');

$teste = Empresas::getGERMetadadoValue(63);
Empresas::printa($teste);

if( isset($contrato->DTInicioContrato ) ){
    $data = date_create($contrato->DTInicioContrato);
    $datainicio =  date_format($data, 'Y-m-d');
}else $datainicio = '';
if( isset($contrato->DTTerminoContrato ) ){
    $data = date_create($contrato->DTTerminoContrato);
    $datatermino =  date_format($data, 'Y-m-d');
}else $datatermino = '';
if( isset($_REQUEST['ID']) ){
    if( $_REQUEST['ID'] <= 0 ) $idcontrato = '';
    else $idcontrato = $_REQUEST['ID'];
}else{
    if( isset($contrato->IDContrato) ) $idcontrato = $contrato->IDContrato;
    else $idcontrato = '';
}
//dados do cliente
if( isset($_REQUEST['clienteID']) )$clienteID = $_REQUEST['clienteID'];
else
    $clienteID = (isset($contrato->IDCliente))?$contrato->IDCliente:'';
    
if( isset($_REQUEST['clienteName']) )$clienteName = $_REQUEST['clienteName'];
else
    $clienteName = (isset($contrato->clientename))?$contrato->clientename:'';
?>

<form id="form_cadastro_contrato" class="">
    
    <input type="hidden" value="<?=$idcontrato?>" name="ID" id="ID"/>
    <?php
        // empresa do contrato
        if( isset($contrato->IDEmpresa) ){
            // editando um contrato que tem empresa vinculada = coloca um imput IDEmpresa
            echo '<input type="hidden" value="'.$contrato->IDEmpresa.'" name="IDEmpresa" id="IDEmpresa"/>';
        }else{?>
            <select class="form-control select" name="IDEmpresa" id="IDEmpresa">
                <option value="" selected="selected">Selecione</option>
                <?php
                    $id_empresa_selecionada = null;
                    $Empresas =  Empresas::getEmpresas();
                    foreach ($Empresas as $empresa){ ?>
                        <option value="<?=$empresa->IDEmpresa?>" 
                            <?=($id_empresa_selecionada == $empresa->IDEmpresa)?'selected="selected"':''?>
                        ><?=$empresa->NMRazaoEmpresa?></option>
                    <?php }
                ?>
            </select>   
        <?php }
    ?>
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group require select">
                <label for="tipocontrato" class="control-label">Tipo do contrato</label>
                <select class="form-control" name="tipocontrato">
                    <option value="">Selecione</option>
                    <?php
                    foreach($lista_CDTipoContrato as $item){
                        $data_class = $item->DSOpcaoMetadado;
                        $data_class = preg_replace(array("/(A|ã)/","/(E)/","/(I)/","/(O)/","/(U)/","/(C)/","/ /"),explode(" ","a e i o u c -"),$data_class);
                        if($item->IDMetadado == $contrato->CDTipoContrato)$selected = 'selected';
                        else $selected = '';
                        echo '<option data-class="'.$data_class.'" value="'. $item->IDMetadado .'" '.$selected.'>'. $item->DSOpcaoMetadado .'</option>';
                    }
                    ?>
                </select>
                <label class="control-label mensagem_erro" for="CDTipoContratoEmpresa" style="display:none">Selecione uma opção!</label>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group require select">
                <label for="situacaocontrato" class="control-label">Situação do contrato</label>
                <select class="form-control" name="situacaocontrato" data-original="<?=$contrato->CDSituacaoContrato?>">
                    <option value="">Selecione</option>
                    <?php
                    foreach($lista_CDSituacaoContrato as $item){
                        if($item->IDMetadado == $contrato->CDSituacaoContrato)$selected = 'selected';
                        else $selected = '';
                        echo '<option value="'. $item->IDMetadado .'" '.$selected.'>'. $item->DSOpcaoMetadado .'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 motivo" style="display:none;">
            <div class="form-group textarea">
                <label for="motivomudancastatus">Motivo da troca de status</label>
                <textarea class="form-control" rows="2" placeholder="Informe o motivo" id="motivomudancastatus"></textarea>
                <label class="control-label mensagem_erro" for="motivomudancastatus" style="display:none">Preencha o campo!</label> 
            </div>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group require input-text">
                <label for="codigo">Código do contrato</label>
                <input type="text" class="form-control" name="codigo" placeholder="Código do contrato" value="<?=(isset($contrato->CDContrato))?$contrato->CDContrato:'';?>">
                <label class="control-label mensagem_erro" for="codigo" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-3" style="display:none;">
            <div class="form-group input-text">
                <label for="codigoaditivo">Código do aditivo contratual</label>
                <input type="text" class="form-control" name="codigoaditivo" placeholder="Código do contrato" value="<?=(isset($contrato->NUAditivoContrato))?$contrato->NUAditivoContrato:'';?>">
                <label class="control-label mensagem_erro" for="codigoaditivo" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group require input-text">
                <label for="datainicio">Data de início</label>
                <input type="text" class="form-control datepicker" name="datainicio" placeholder="01/01/2022" value="<?=$datainicio?>">
                <label class="control-label mensagem_erro" for="datainicio" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group require input-text">
                <label for="datatermino">Data de término</label>
                <input type="text" class="form-control datepicker" name="datatermino" placeholder="01/01/2022"" value="<?=$datatermino?>">
                <label class="control-label mensagem_erro" for="datatermino" style="display:none">Preencha o campo!</label> 
            </div>
        </div>

    </div>
    
    <div class="row">
        
        <div class="col-md-6">
            <div class="form-group require textarea">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" rows="2" placeholder="Descrição" id="descricao"><?=(isset($contrato->DSContrato))?$contrato->DSContrato:'';?></textarea>
                <label class="control-label mensagem_erro" for="descricao" style="display:none">Preencha o campo!</label> 
            </div>
        </div>

    </div>
    <hr>
    <div class="row">

        <div class="col-md-6">
            <h5><strong>Cliente</strong></h5>
            <div><a href="javascript:void(0);" id="buscaCliente">Buscar cliente</a></div>
            
            <div class="buscaajax" style="display:none;">
                <div class="input-group">
                    <input type="text" name="busca" class="form-control" placeholder="Busca cliente">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    </span>
                </div>
                <div class="lista_suspensa" style="display:none;"></div>
            </div>
            <br>
            <div class="form-group require input-text">
                <input type="text" class="form-control" 
                    name="cliente" placeholder="" 
                    value="<?=(isset($clienteName))?$clienteName:'';?>"
                    data-id="<?=(isset($clienteID))?$clienteID:'';?>" disabled>
                <label class="control-label mensagem_erro" for="cliente" style="display:none">Preencha o campo!</label> 
            </div>
            

            <div id="contatos-contrato">
                <br>
                <h5>
                    <strong>Indique o(s) contato(s) para este contrato</strong>
                    <a href="javascript:void(0);" class="editarContatos">Editar</a>
                    <input type="hidden" name="contatosAtivos" value=""/>
                </h5>
                
                <div class="content">
                    <div class="contatos"></div>
                    <div class="editarContatos"><i class="fa fa-floppy-o" aria-hidden="true"></i></div>
                </div>

            </div>

        </div>

    </div>
</form>