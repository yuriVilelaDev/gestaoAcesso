<?php
    /* CODIGOS PHP PARA PREENCHIMENTO PRÉVIO DO PROJETO */
    $projetoid  = ( isset($projeto->IDProjeto) )? $projeto->IDProjeto : '';
    $nome       = ( isset($projeto->NMProjeto) )? $projeto->NMProjeto : '';
    $descricao  = ( isset($projeto->DSProjeto) )? $projeto->DSProjeto : '';
    $status     = ( isset($projeto->STProjeto) )? $projeto->STProjeto : '';
    if( isset($projeto->IMLogoProjeto) ){
        $logoSrc = wp_get_attachment_image_url($projeto->IMLogoProjeto,'thumbnail');
        $logoID = $projeto->IMLogoProjeto;
    }else{
        $logoSrc = '';
        $logoID = '';
    }
    // DADOS DO CONTRATO
    $contratoID =  ( isset($projeto->IDContrato) )? $projeto->IDContrato : '';
    $contratoCodigo =  ( isset($projeto->CDContrato) )? $projeto->CDContrato : '';
    $contratoDescricao = ( isset($projeto->DSContrato) )? $projeto->DSContrato : '';
    $DTInicioContrato = ( isset($projeto->DTInicioContrato) )? 
    date_format(date_create($projeto->DTInicioContrato), 'Y-m-d'): '';
    $DTTerminoContrato = ( isset($projeto->DTTerminoContrato) )? 
    date_format(date_create($projeto->DTTerminoContrato), 'Y-m-d'): '';
    // DADOS DO PROJETO NO CONTRATO
    $inicioProjeto = ( isset($projeto->DTInicioContratoProjeto) )? 
        date_format(date_create($projeto->DTInicioContratoProjeto), 'Y-m-d'): '';
    $terminoProjeto = ( isset($projeto->DTTerminoContratoProjeto) )? 
        date_format(date_create($projeto->DTTerminoContratoProjeto), 'Y-m-d'): '';
    $CDSituacaoContratoProjeto =( isset($projeto->CDSituacaoContratoProjeto) )? $projeto->CDSituacaoContratoProjeto : '';
    $CDSituacaoContratoProjeto_lista = Empresas::getGERMetadadoOpcoes('CDSituacaoContratoProjeto');
    $STUsarGestaoSolicitacao = ( isset($projeto->STUsarGestaoSolicitacao) )? $projeto->STUsarGestaoSolicitacao : '';
    $STUsarPerfilAcessoPadrao = ( isset($projeto->STUsarPerfilAcessoPadrao) )? $projeto->STUsarPerfilAcessoPadrao : '';
    $STUsarLocal= ( isset($projeto->STUsarLocal) )? $projeto->STUsarLocal : '';
    $STCadastrarEstudante= ( isset($projeto->STCadastrarEstudante) )? $projeto->STCadastrarEstudante : '';
    // CASO VENHA DE page-contratos.php
    $urlRetorno = '';
    if( $dados['ID'] == '-1'){
        $contratoID =  ( isset($_POST['contratoID']) )? $_POST['contratoID'] : '';
        $contratoCodigo =  ( isset($_POST['contratoCodigo']) )? $_POST['contratoCodigo'] : '';
        $contratoDescricao = ( isset($_POST['contratoDescricao']) )? $_POST['contratoDescricao'] : '';
        $DTInicioContrato = ( isset( $_POST['contratoInicio'] ) )? 
        date_format(date_create( $_POST['contratoInicio'] ), 'Y-m-d'): '';
        $DTTerminoContrato = ( isset( $_POST['contratoTermino'] ) )? 
        date_format(date_create( $_POST['contratoTermino'] ), 'Y-m-d'): '';
        $urlRetorno =  ( isset($_POST['urlRetorno']) )? $_POST['urlRetorno'] : '';
    }
?>


<style>
    #form_cadastro_projeto .logo_projeto img{width: 80%;border-radius: 12px;max-height: 200px;border: 4px solid #cfcfcf;margin: 0 0 8px 0;}
</style>

<form id="form_cadastro_projeto" class="">
    
    <input type="hidden" value="<?=$projetoid;?>" name="ID" id="ID"/>
    <input type="hidden" value="<?=$urlRetorno;?>" name="urlRetorno" id="urlRetorno"/>
    
    <div class="row">
        
        <div class="col-md-3">
            <div class="form-group">
                <div><label for="logo_projeto">Logo do projeto</label></div>
                <div class="logo_projeto">
                    <img src="<?=$logoSrc?>"/>
                </div>
                <div>
                    <input type="hidden" value="<?=$logoID?>" id="logo_projeto" name="logo_projeto" max="" min="1" step="1"/>
                    <button class="set_logoProjeto button">Alterar</button>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group require input-text">
                        <label for="nome">Nome do projeto</label>
                        <input type="text" class="form-control" name="nome" placeholder="Nome do projeto" value="<?=$nome?>"/>
                        <label class="control-label mensagem_erro" for="nome" style="display:none">Preencha o campo!</label> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group require input-radio">
                            <div>
                                <label for="status" control-label="">Status do projeto</label>                                
                            </div>
                            <div>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="1" <?=($status==1)?'checked="checked"':''?>> 
                                    Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="0" <?=($status==0)?'checked="checked"':''?>> 
                                    Desativado
                                </label>
                            </div>
                            <label class="control-label mensagem_erro" for="status" style="display:none">Selecione uma opção!</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group require textarea">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" rows="2" placeholder="Descrição" id="descricao"><?=$descricao?></textarea>
                        <label class="control-label mensagem_erro" for="descricao" style="display:none">Preencha o campo!</label> 
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h5><strong>Este projeto pertence ao contrato:</strong></h5>
                    <div><a href="javascript:void(0);" id="buscaContrato">Buscar contrato</a></div>
                    
                    <div class="buscaajax" style="display:none;">
                        <div class="input-group">
                            <input type="text" name="busca" class="form-control" placeholder="Busca contrato"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </span>
                        </div>
                        <div class="lista_suspensa" style="display:none;"></div>
                    </div>

                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group require input-text">
                        <label for="descricao">Código do contrato</label>    
                        <input type="text" class="form-control" 
                            name="contrato" placeholder="" 
                            value="<?=$contratoCodigo?>"
                            data-id="<?=$contratoID?>"
                            data-contratoInicio="<?=$DTInicioContrato?>"
                            data-contratoTermino="<?=$DTTerminoContrato?>" 
                            disabled/>
                        <label class="control-label mensagem_erro" for="contrato" style="display:none">Preencha o campo!</label> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group require input-text">
                        <label for="contratoDescricao">Descrição do contrato</label>    
                        <input type="text" class="form-control" 
                            name="contratoDescricao" placeholder="" 
                            value="<?=$contratoDescricao?>"
                            data-id="" disabled/>
                        <label class="control-label mensagem_erro" for="contratoDescricao" style="display:none">Preencha o campo!</label> 
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group require input-text">
                        <label for="inicioProjeto">Data início do projeto</label>    
                        <input type="text" class="form-control inicioProjeto" 
                            name="inicioProjeto" placeholder="" 
                            value="<?=$inicioProjeto?>"
                            data-id=""/>
                        <label class="control-label mensagem_erro" for="inicioProjeto" style="display:none">Preencha o campo!</label> 
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group require input-text">
                        <label for="terminoProjeto">Data término do projeto</label>    
                        <input type="text" class="form-control terminoProjeto" 
                            name="terminoProjeto" placeholder="" 
                            value="<?=$terminoProjeto?>"
                            data-id=""/>
                        <label class="control-label mensagem_erro" for="terminoProjeto" style="display:none">Preencha o campo!</label> 
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group require select">
                        <label for="situacaoContratoProjeto" class="control-label">Situação do projeto no contrato</label>
                        <select class="form-control" name="situacaoContratoProjeto">
                            <option value="">Selecione</option>
                            <?php
                            foreach($CDSituacaoContratoProjeto_lista as $item){
                                $data_class = $item->DSOpcaoMetadado;
                                $data_class = preg_replace(array("/(A|ã)/","/(E)/","/(I)/","/(O)/","/(U)/","/(C)/","/ /"),explode(" ","a e i o u c -"),$data_class);
                                if($item->IDMetadado == $CDSituacaoContratoProjeto)$selected = 'selected';
                                else $selected = '';
                                echo '<option data-class="'.$data_class.'" value="'. $item->IDMetadado .'" '.$selected.'>'. $item->DSOpcaoMetadado .'</option>';
                            }
                            ?>
                        </select>
                        <label class="control-label mensagem_erro" for="situacaoContratoProjeto" style="display:none">Selecione uma opção!</label>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <div class="col-md-3">
                    <div class="form-group require select">
                        <label for="gestaoSolicitacaoAcesso" class="control-label">Gestão de solicitação de acesso</label>
                        <select class="form-control" name="gestaoSolicitacaoAcesso">
                            <option value="">Selecione</option>
                            <option value="1" <?=($STUsarGestaoSolicitacao==1)?'selected':''?>>Usar</option>
                            <option value="0" <?=($STUsarGestaoSolicitacao==0)?'selected':''?>>Não Usar</option>
                            <option value="2" <?=($STUsarGestaoSolicitacao==2)?'selected':''?>>Definir na chave de autenticação</option>
                        </select>
                        <label class="control-label mensagem_erro" for="gestaoSolicitacaoAcesso" style="display:none">Selecione uma opção!</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group require select">
                        <label for="usoPerfilPadrao" class="control-label">Perfil padrão</label>
                        <select class="form-control" name="usoPerfilPadrao">
                            <option value="">Selecione</option>
                            <option value="1" <?=($STUsarPerfilAcessoPadrao==1)?'selected':''?>>Usar</option>
                            <option value="0" <?=($STUsarPerfilAcessoPadrao==0)?'selected':''?>>Não Usar</option>
                            <option value="2" <?=($STUsarPerfilAcessoPadrao==2)?'selected':''?>>Definir na chave de autenticação</option>
                        </select>
                        <label class="control-label mensagem_erro" for="usoPerfilPadrao" style="display:none">Selecione uma opção!</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group require select">
                        <label for="usoLocalPadrao" class="control-label">Local padrão</label>
                        <select class="form-control" name="usoLocalPadrao">
                            <option value="">Selecione</option>
                            <option value="1" <?=($STUsarLocal==1)?'selected':''?>>Usar</option>
                            <option value="0" <?=($STUsarLocal==0)?'selected':''?>>Não Usar</option>
                            <option value="2" <?=($STUsarLocal==2)?'selected':''?>>Definir na chave de autenticação</option>
                        </select>
                        <label class="control-label mensagem_erro" for="usoLocalPadrao" style="display:none">Selecione uma opção!</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group require select">
                        <label for="cadastroEstudante" class="control-label">Cadastro de estudantes</label>
                        <select class="form-control" name="cadastroEstudante">
                            <option value="">Selecione</option>
                            <option value="1" <?=($STCadastrarEstudante==1)?'selected':''?>>Permitir</option>
                            <option value="0" <?=($STCadastrarEstudante==0)?'selected':''?>>Não Permitir</option>
                        </select>
                        <label class="control-label mensagem_erro" for="cadastroEstudante" style="display:none">Selecione uma opção!</label>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
    <br><br><br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary bt_salvar_cadastro"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="display: none;"></i>Salvar</button>
            <a href="?page=<?=$_REQUEST['page']?>" type="button" class="btn btn-danger excluirProjeto"><i class="fa fa-trash-o"></i>Excluir</a>
        </div>
    </div>

    
</form>