<div class="modal fade" id="modal_editar_usuario" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroUsuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
       
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro usuario</h4>
            </div>
            <div class="modal-body">
                <form id="form_cadastro_usuario" class="">
                    <input type="hidden" value="" name="IDUsuario" id="IDUsuario"/>
                    <input type="hidden" value="" name="IDPerfil" id="IDPerfil"/>
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="form-group require input-text">
                                <label for="usuario"> usuario</label>
                                <input type="text" class="form-control"  name="usuario" id ="usuario" placeholder="usuario">
                                <label class="control-label mensagem_erro" for="usuario" style="display:none">Preencha o campo!</label> 
                            </div>
                            
                            <div class="form-group require input-text">
                                <label for="nome">nome</label>
                                <input type="text" class="form-control" name="nome" placeholder="nome">
                                <label class="control-label mensagem_erro" for="nome" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                           <div class="form-group require input-text">
                                <label for="email">email</label>
                                <input type="text" class="form-control" name="email" placeholder="email">
                                <label class="control-label mensagem_erro" for="email" style="display:none">Preencha o campo!</label> 
                            </div>
                            <div class="form-group require input-text">
                                <label for="senha">senha</label>
                                <input type="password"  class="form-control" name="senha" placeholder="senha"> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group require input-radio col-md-6">
                                <div>
                                    <label for="STUsuario" control-label">Situação</label>                                
                                </div>
                                <div>
                                    <label class="radio-inline"><input type="radio" name="STUsuario" value="1"> Ativo</label>
                                    <label class="radio-inline"><input type="radio" name="STUsuario" value="0"> Desativado</label>
                                </div>
                                <label class="control-label mensagem_erro" for="STUsuario" style="display:none">Selecione uma opção!</label>
                            </div>
                        </div>
                    </div> 
                </form>
				<br>

                <div>
                    <ul class="table-cadastros table-enderecos">

                        <li><i class="fa fa-map" aria-hidden="true"></i> Perfil :</li>
                        <li>
                        <li><i class="fa fa-map" aria-hidden="true"></i> Permssões</li>
                            <table class="table table-hover">

                            </table>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="table-cadastros table-enderecos">
                        <li><i class="fa fa-map" aria-hidden="true"></i> Plataformas</li>
                        <li>
                            <table class="table table-hover">
                                <tr><td><i class="fa fa-map-o" aria-hidden="true"></i> 1 </div> <td>cadastrar usuario</td> </tr>
                                <tr><td><i class="fa fa-map-o" aria-hidden="true"></i> 2</td> <td>cadastrar contrato</td> </tr>
                                <tr><td><i class="fa fa-map-o" aria-hidden="true"></i> 4</td> <td>cadastrar perfis</td></tr>
                            </table>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="table-cadastros table-enderecos">
                        <li><i class="fa fa-map" aria-hidden="true"></i> Locais Atuantes </li>
                        
                        <li>
                            <table class="table table-hover">
                                <tr><td><i class="fa fa-map-o" aria-hidden="true"></i> 1 </div> <td>cadastrar usuario</td> </tr>
                                <tr><td><i class="fa fa-map-o" aria-hidden="true"></i> 2</td> <td>cadastrar contrato</td> </tr>
                                <tr><td><i class="fa fa-map-o" aria-hidden="true"></i> 4</td> <td>cadastrar perfis</td></tr>
                            </table>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary bt_salvar" id="bt_salvar"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>

