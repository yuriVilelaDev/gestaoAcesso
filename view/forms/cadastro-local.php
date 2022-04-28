<?php
    /* CODIGOS PHP PARA PREENCHIMENTO PRÉVIO DO PROJETO */
    $local_id   = ( isset($local->IDLocal) )? $local->IDLocal : '';
    $nome               = ( isset($local->NMLocal) )? $local->NMLocal : '';
    $cdregioal         = ( isset($local->CDRegional) )? $local->CDRegional : '';
    $empresa_id         = ( isset($local->IDEmpresa) )? $local->IDEmpresa : '';
    $status             = ( isset($local->st_delete) )? $local->st_delete : '';
    $urlRetorno = '';

    $CDTipoLocal        = ( isset($local->CDTipoLocal) )? $local->CDTipoLocal : '';
    $CDTipoLocal_lista = Local::getGERMetadadoOpcoes('CDTipoLocal');

    // CONSTRUINDO AS VARIAVEIS DO ENDERECO
    if ( isset($local->EDEnderecoLocalJSON) ){
        $enderecoJSON = $local->EDEnderecoLocalJSON;   
    }else{
        $enderecoJSON = array(
            'logradouro'=>'',
            'numero'=>'',
            'complemento'=>'',
            'bairro'=>'',
            'localidade'=>'',
            'uf' =>'',
            'cep'=>''
        );
        $enderecoJSON = json_encode($enderecoJSON);
    }
    $enderecoJSON = json_decode($enderecoJSON);
   
    //Local::printa($enderecoJSON);
?>

<form id="form_cadastro_local" class="">
    
    <input type="hidden" value="<?=$local_id;?>" name="ID" id="ID"/>
    <input type="hidden" value="<?=$empresa_id;?>" name="empresa_id"/>
    <input type="hidden" value="<?=$urlRetorno;?>" name="urlRetorno" id="urlRetorno"/>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group require input-text">
                <label for="nome">Nome do local</label>
                <input type="text" class="form-control" name="nome" placeholder="Nome do projeto" value="<?=$nome?>"/>
                <label class="control-label mensagem_erro" for="nome" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group require select">
                <label for="tipolocal" class="control-label">Tipo do local</label>
                <select class="form-control" name="tipolocal">
                    <option value="">Selecione</option>
                    <?php
                    foreach($CDTipoLocal_lista as $item){
                        $data_class = $item->DSOpcaoMetadado;
                        $data_class = preg_replace(array("/(A|ã)/","/(E)/","/(I)/","/(O)/","/(U)/","/(C)/","/ /"),explode(" ","a e i o u c -"),$data_class);
                        if($item->IDMetadado == $CDTipoLocal)$selected = 'selected';
                        else $selected = '';
                        echo '<option data-class="'.$data_class.'" value="'. $item->IDMetadado .'" '.$selected.'>'. $item->DSOpcaoMetadado .'</option>';
                    }
                    ?>
                </select>
                <label class="control-label mensagem_erro" for="tipolocal" style="display:none">Selecione uma opção!</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group require input-text">
                <label for="cdregioal">Identificador da regional</label>
                <input type="text" class="form-control" name="cdregioal" placeholder="Código Regional" value="<?=$cdregioal?>"/>
                <label class="control-label mensagem_erro" for="cdregioal" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group require input-text">
                <label for="cep">Informe o CEP</label>
                <input type="text" class="form-control" name="cep" placeholder="CEP" value="<?=$enderecoJSON->cep?>"/>
                <label class="control-label mensagem_erro" for="cep" style="display:none">Preencha o campo!</label> 
            </div>
            <div>
            <button type="button" class="btn btn-warning bt_procurarCEP">
                <i class="fa fa-spinner fa-pulse fa-fw" style="display: none;"></i>
                Buscar cep <i class="fa fa-address-card" aria-hidden="true"></i>
            </button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group require input-text">
                <label for="logradouro">Logradouro</label>
                <input type="text" class="form-control" name="logradouro" placeholder="Rua etc" value="<?=$enderecoJSON->logradouro?>"/>
                <label class="control-label mensagem_erro" for="logradouro" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group require input-text">
                <label for="numero">Número</label>
                <input type="text" class="form-control" name="numero" placeholder="00" value="<?=$enderecoJSON->numero?>"/>
                <label class="control-label mensagem_erro" for="numero" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group input-text">
                <label for="complemento">Complemento</label>
                <input type="text" class="form-control" name="complemento" placeholder="Complemento" value="<?=$enderecoJSON->complemento?>"/>
                <label class="control-label mensagem_erro" for="complemento" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-2 col-md-offset-2">
            <div class="form-group require input-text">
                <label for="bairro">Bairro</label>
                <input type="text" class="form-control" name="bairro" placeholder="Bairro" value="<?=$enderecoJSON->bairro?>"/>
                <label class="control-label mensagem_erro" for="bairro" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group require input-text">
                <label for="localidade">Cidade(localidade)</label>
                <input type="text" class="form-control" name="localidade" placeholder="Localidade" value="<?=$enderecoJSON->localidade?>"/>
                <label class="control-label mensagem_erro" for="localidade" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group require input-text">
                <label for="uf">UF</label>
                <input type="text" class="form-control" name="uf" placeholder="ZZ" value="<?=$enderecoJSON->uf?>"/>
                <label class="control-label mensagem_erro" for="uf" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        
    </div>

    <br><br><br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary bt_salvar_cadastro">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="display: none;"></i>Salvar
            </button>
            <button type="button" class="btn btn-danger bt_excluir_cadastro" style="display:none">
                <i class="fa fa-trash-o"></i>Excluir
            </button>
        </div>
    </div>

    
</form>