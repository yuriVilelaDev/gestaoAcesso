<div class="modal fade modal_cadastro_endereco" id="modal_cadastro_cliente_endereco" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroClienteEndereco">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            
            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro Endereco</h4>
            </div>
            <div class="modal-body">

                <?php //include SIGSA_PATH.'view/forms/cadastro-cliente.php';?>

                <form id="form_cadastro_cliente_endereco" class="">
                    <input type="hidden" name="IDCliente"/>
                    <input type="hidden" name="IDEnderecoCliente" value=""/>
                    <?php
                        $lista_CDTipoEndCliente = Clientes::getGERMetadadoOpcoes('CDTipoEndCliente');
                        //Clientes::printa($lista_CDTipoEndCliente);
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group require check">
                                <label for="CDTipoEndCliente" class="control-label">Tipo do Endereço</label><br>
                                <div class="checkbox">
                                <?php   
                                foreach($lista_CDTipoEndCliente as $item){
                                    $data_class = $item->DSOpcaoMetadado;
                                    $data_class = preg_replace(array("/(A|ã)/","/(E|ê)/","/(I)/","/(O|ó)/","/(U)/","/(C)/","/(F)/","/(G)/","/(P)/","/(T)/","/(I)/","/ /"),explode(" ","a e i o u c f g p t i-"),$data_class);
                                    echo '<label> <input type="checkbox" name="CDTipoEndCliente" data-class="'.$data_class.'" value="'. $item->IDMetadado .'"/> '. $item->DSOpcaoMetadado .' </label>';
                                } 
                                ?>
                                </div>
                                <label class="control-label mensagem_erro" for="CDTipoEndCliente" style="display:none">Selecione ao menos uma opção!</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group input-text require">
                                <label for="cep" class="control-label">Cep:</label>
                                <input type="text" class="form-control" name="cep" value="" placeholder="CEP">
                                <label class="control-label mensagem_erro" for="cep" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-9">
                            <div class="form-group input-text require">
                                <label for="logradouro" class="control-label">Logradouro:</label>
                                <input type="text" class="form-control" name="logradouro" value="" placeholder="Logradouro:">
                                <label class="control-label mensagem_erro" for="logradouro" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group input-text require">
                                <label for="numero" class="control-label">Número:</label>
                                <input type="text" class="form-control" name="numero" value="" placeholder="Número:">
                                <label class="control-label mensagem_erro" for="numero" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-text require">
                                <label for="bairro" class="control-label">Bairro:</label>
                                <input type="text" class="form-control" name="bairro" value="" placeholder="Bairro:">
                                <label class="control-label mensagem_erro" for="bairro" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-text require">
                                <label for="localidade" class="control-label">Cidade:</label>
                                <input type="text" class="form-control" name="localidade" value="" placeholder="Cidade:">
                                <label class="control-label mensagem_erro" for="localidade" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-text require">
                                <label for="uf" class="control-label">Estado:</label>
                                <input type="text" class="form-control" name="uf" value="" placeholder="Estado:">
                                <label class="control-label mensagem_erro" for="uf" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group input-text">
                                <label for="complemento" class="control-label">Complemento:</label>
                                <input type="text" class="form-control" name="complemento" value="" placeholder="Complemento:">
                                <label class="control-label mensagem_erro" for="complemento" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Telefones:</h4>
                            <div class="telefones cadastro_telefones_1">
                                <div class="telefone new">
                                    <div class="numero_telefone"><input name="numero_telefone" type="text" placeholder="(xx)xxxxx-xxxx"></div>
                                    <div class="tipo_telefone">
                                        <select class="form-control" name="tipo_telefone">
                                            <option data-class="" value="">Selecione</option>
                                            <option data-class="" value="Celular">Celular</option>
                                            <option data-class="" value="Fixo">Fixo</option>
                                            <option data-class="" value="Whatsapp">Whatsapp</option>
                                        </select>
                                    </div>
                                    <div class="actions"><button class="btn btn-default btn-xs" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger bt_excluir"><i class="fa fa-trash-o"></i>Excluir</button>
                <button type="button" class="btn btn-primary bt_salvar"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="display:none;"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>

