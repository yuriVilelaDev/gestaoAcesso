<form id="form_cadastro_cliente" class="">

    <?php
    
        echo wp_get_attachment_image_url(6,'');
    
    ?>

    <input type="hidden" value="<?=$cliente->id_empresa;?>" name="IDEmpresa" id="IDEmpresa"/>
    <input type="hidden" value="<?=$cliente->id_cliente;?>" name="IDCliente" id="IDCliente"/>
    <div class="row">

        <div class="col-md-2">
            <div class="form-group">
                <div><label for="IMLogoCliente">Logo cliente</label></div>
                <div class="logo_IMLogoCliente">
                    <img src="<?=$cliente->IMLogoCliente_src;?>"/>
                </div>
                <div>
                    <input type="hidden" value="<?=($cliente->logo)?$cliente->logo:'';?>" id="IMLogoCliente" name="" max="" min="1" step="1">
                    <button class="set_IMLogoCliente button">Alterar</button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group require input-text">
                <label for="NMRazaoCliente">Razão social</label>
                <input type="text" class="form-control" name="NMRazaoCliente" placeholder="NMRazaoCliente" value="<?=(isset($cliente->razao_social))?$cliente->razao_social:'';?>">
                <label class="control-label mensagem_erro" for="NMRazaoCliente" style="display:none">Preencha o campo!</label> 
            </div>
            
            <div class="form-group require input-text">
                <label for="EDWebsiteCliente">Web site</label>
                <input type="text" class="form-control" name="EDWebsiteCliente" placeholder="EDWebsiteCliente" value="<?=(isset($cliente->site))?$cliente->site:'';?>">
                <label class="control-label mensagem_erro" for="EDWebsiteCliente" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        
        <div class="col-md-3">
            
            <div class="form-group require input-text">
                <label for="NMFantasiaCliente">Nome fantasia</label>
                <input type="text" class="form-control" name="NMFantasiaCliente" placeholder="NMFantasiaCliente" value="<?=$cliente->nome_fantasia;?>">
                <label class="control-label mensagem_erro" for="NMFantasiaCliente" style="display:none">Preencha o campo!</label> 
            </div>

            <div class="form-group require input-radio">
                <div>
                    <label for="STCliente" control-label">Situação</label>                                
                </div>
                <div>
                    <label class="radio-inline"><input type="radio" name="STCliente" value="1" <?=($cliente->status_cliente == '1')?'checked':'';?>> Ativo</label>
                    <label class="radio-inline"><input type="radio" name="STCliente" value="0" <?=($cliente->status_cliente == '0')?'checked':'';?>> Inativo</label>
                </div>
                <label class="control-label mensagem_erro" for="STCliente" style="display:none">Selecione uma opção!</label>
            </div>

        </div>

        <div class="col-md-3">
            <div class="form-group require input-text cpfCnpj">
                <label for="NUCnpjCliente">N CNPJ</label>
                <input type="text" class="form-control" name="NUCnpjCliente" placeholder="NUCnpjCliente" value="<?=$cliente->cnpj;?>">
                <label class="control-label mensagem_erro" for="NUCnpjCliente" style="display:none">Preencha o CNPJ corretamente!</label> 
            </div>
        </div>
    </div> 
</form>