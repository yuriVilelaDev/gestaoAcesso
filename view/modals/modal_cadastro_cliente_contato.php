<div class="modal fade modal_cadastro_contato" id="modal_cadastro_cliente_contato" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroClienteContato">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            
            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro de contato do cliente</h4>
            </div>
            <div class="modal-body">
                <form id="form_cadastro_cliente_contato" class="">
                    <input type="hidden" name="IDCliente"/>
                    <input type="hidden" name="IDContatoCliente" value=""/>
                    <?php
                        $lista_DSReferenciaContatoClienteJSON = Clientes::getGERMetadadoOpcoes('DSReferenciaContatoClienteJSON');
                        //Clientes::printa($lista_DSReferenciaClienteJSON);
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group require check">
                                <label for="DSReferenciaClienteJSON" class="control-label">Referencia do contato</label><br>
                                <div class="checkbox">
                                <?php   
                                foreach($lista_DSReferenciaContatoClienteJSON as $item){
                                    $data_class = $item->DSOpcaoMetadado;
                                    $data_class = preg_replace(array("/(A|ã)/","/(E|ê)/","/(I)/","/(O|ó)/","/(U)/","/(C)/","/(F)/","/(G)/","/(P)/","/(T)/","/(I)/","/ /"),explode(" ","a e i o u c f g p t i-"),$data_class);
                                    echo '<label> <input type="checkbox" name="DSReferenciaClienteJSON" data-class="'.$data_class.'" value="'. $item->IDMetadado .'"/> '. $item->DSOpcaoMetadado .' </label>';
                                } 
                                ?>
                                </div>
                                <label class="control-label mensagem_erro" for="DSReferenciaClienteJSON" style="display:none">Selecione ao menos uma opção!</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group input-text require">
                                <label for="nome" class="control-label">Nome do contato:</label>
                                <input type="text" class="form-control" name="nome" value="" placeholder="Nome do contato">
                                <label class="control-label mensagem_erro" for="nome" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-text require">
                                <label for="setor" class="control-label">Setor:</label>
                                <input type="text" class="form-control" name="setor" value="" placeholder="Setor:">
                                <label class="control-label mensagem_erro" for="setor" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-text require">
                                <label for="cargo" class="control-label">Cargo:</label>
                                <input type="text" class="form-control" name="cargo" value="" placeholder="Cargo:">
                                <label class="control-label mensagem_erro" for="cargo" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-text require">
                                <label for="email" class="control-label">E-mail:</label>
                                <input type="text" class="form-control" name="email" value="" placeholder="E-mail:">
                                <label class="control-label mensagem_erro" for="email" style="display:none">Preencha o campo!</label> 
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

