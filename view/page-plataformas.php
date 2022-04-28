<?php
    wp_enqueue_style(sigsaClass::get_prefix().'_style');
    wp_enqueue_script(sigsaClass::get_prefix().'_script');
    wp_enqueue_script(sigsaClass::get_prefix().'_Plataforma');
    wp_enqueue_media();

?>
<div class="plugin-content" style="opacity: 0;">
    <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
    <div class="page-header">
        <h3>Plataformas</h3>
    </div>
    <div>
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Cadastrar Plataforma
  </button>
      
    </div>    
    <br>
    <div id="conteudo"><!-- espaço para conteudo --></div>
</div>



<div class="collapse" id="collapseExample">
  
  <div class="modal-header">
    <h4 class="modal-title">Cadastro Plataforma</h4>
</div>
<form  method="" class="ls-form row">
  <fieldset>
  <input type="hidden" value="" name="IDEmpresa" id="IDEmpresa"/>
        <input type="hidden" value="" name="IDCliente" id="IDCliente"/>
        <div class="row">                       
            <!-- <div class="col-md-2">
                <div class="form-group">
                    <div><label for="logo_plataforma">Logo plataforma</label></div>
                    <div class="logo_plataforma">
                        <img src=""/>
                    </div>
                    <div class="logo_plataforma">
                                    <img src=""/>
                                </div>
                    <div>
                        <input type="hidden" value="" id="logo_plataforma" name="logo_plataforma" max="" min="1" step="1">
                        <button class="set_LogoPlataforma button">Alterar</button>
                    </div>
                </div>
            </div> -->
            <div class="col-md-4">                           
                <div class="form-group require input-text">
                    <label for="NMPlataforma">Nome da plataforma</label>
                    <input type="text" class="form-control" name="NMPlataforma" id ="NMPlataforma"placeholder="NMPlataforma">
                    <label class="control-label mensagem_erro" for="NMPlataforma" style="display:none">Preencha o campo!</label> 
                </div>        
                <div class="form-group require input-text">
                    <label for="DSPlataforma">Descrição</label>
                    <textarea class="form-control" id="DSPlataforma" rows="3"></textarea>
                    <label class="control-label mensagem_erro" for="DSPlataforma" style="display:none">Preencha o campo!</label> 
                </div>                           
            </div>                       
            <div class="col-md-3">              
                <div class="form-group require input-text">
                    <label for="DSVersaoPlataforma">Versão</label>
                    <input type="text" class="form-control" name="DSVersaoPlataforma" placeholder="DSVersaoPlataforma">
                    <label class="control-label mensagem_erro" for="DSVersaoPlataforma" style="display:none">Preencha o campo!</label> 
                </div>
                <div class="form-group require input-text">
                    <label for="DSReleasePlataforma">Release</label>
                    <input type="text" class="form-control" name="DSReleasePlataforma" placeholder="DSReleasePlataforma">
                    <label class="control-label mensagem_erro" for="DSReleasePlataforma" style="display:none">Preencha o campo!</label> 
                </div>
            </div>
            <div class="col-md-3">
            <div class="form-group require input-text">
                <label for="NMFantasiaCliente"> Tipo de operação</label><br>
                <select class="form-select" aria-label="Default select example">
                <option selected>Selecione uma Opção</option>
                    <option value="1">Online</option>
                    <option value="2">Offline</option>
                    
                </select>
            </div>
            <div class="form-group require input-text">
                <label for="NMFantasiaCliente">Tipo de licença</label><br>
                <select class="form-select" aria-label="Default select example">
                
                <option selected>Selecione uma Opção</option>
                <option value="1">Dispositivo de acesso</option>
                <option value="2">Acesso simultâneo</option>
                </select>
            </div>
            
           
               <div class="form-group require input-radio">
                    <div>
                        <label for="STPlataforma" control-label">Situação</label>                                
                                
                        <div>
                            <label class="radio-inline"><input type="radio" name="STPlataforma" value="1"> Ativo</label>
                            <label class="radio-inline"><input type="radio" name="STPlataforma" value="0"> Desativado</label>
                        </div>
                        <label class="control-label mensagem_erro" for="STPlataforma" style="display:none">Selecione uma opção!</label>
                    </div>               
            </div>                 
        </div> 
  </fieldset>
  <hr>
  <div class="ls-actions-btn">
    <button class="ls-btn"id="salvarPlataformas">Salvar</button>
    <button class="ls-btn-danger">Excluir</button>
  </div>
</form>


  </div>
