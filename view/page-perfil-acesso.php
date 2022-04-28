<?php
// Arquivos requeridos para o funcionamento da página
require_once SIGSA_PATH.'inc/class_Empresas.php';

wp_register_script(
    sigsaClass::get_prefix().'_PerfilAcesso', 
    SIGSA_URL . 'js/PerfilAcesso.js?op='.rand(1, 1000),
    array('jquery','bootstrapjs','jquery-ui-js',sigsaClass::get_prefix().'_script')
);
wp_enqueue_script(sigsaClass::get_prefix().'_PerfilAcesso'); //script Geral
wp_enqueue_style(sigsaClass::get_prefix().'_style'); //folha de estilo css padrão

// Validações de Requests
if( !isset( $_REQUEST['IDEmpresa']) )   $_REQUEST['IDEmpresa'] = "";
if( !isset( $_REQUEST['page']) )        $_REQUEST['page'] = "";

$perfilUsuario = VerificacaoAcesso::getPerfil( get_current_user_id() );
//Empresas::printa($perfilUsuario);
//VerificacaoAcesso::setSessionPermissoes();
//Empresas::printa($_SESSION);
?>

<div class="plugin-content" style="opacity: 0;">
    <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
    <input type="hidden" id="page" name="page" value="<?=$_REQUEST['page']?>"/>
    <div class="page-header">
        <h3>Perfis de acesso</h3>
        <div class="acoes">
            <?php if( VerificacaoAcesso::userCan(6) ):?>
            <button type="button" class="btn btn-primary bt-adicionar-peril_SIGSA">Adicionar Perfil</button>
            <?php endif;?>
        </div>
    </div>

    <div class="container-flui">
        <div class="row">
            <div class="col-md-12">
                <?php 
                    $perfis = VerificacaoAcesso::getAllPerfis();
                    $i = 0;
                    $nivel = -1;
                    $edicao = true;
                    $disabled = '';
                ?>
                <?php if( isset($perfis) ):?>
                    
                    <div class="panel-group" id="permissoes" role="tablist" aria-multiselectable="true">
                        <?php foreach($perfis as $perfil): ?>
                            <?php 
                                //Empresas::printa($perfil);
                                $i++;
                                if( $perfil['NUNivel'] != $nivel){
                                    $nivel = $perfil['NUNivel'];
                                    switch ($nivel){
                                        case 0:echo '<br><br>Nível sistema';break;
                                        case 1:echo '<br><br>Nível empresa';break;
                                        case 2:echo '<br><br>Nível cliente';break;
                                        case 3:echo '<br><br>Perfis do nível mínimo';break;
                                    }
                                }
                                /** 
                                 * Verificacao se o usuario atual guardado em $perfilUsuario
                                 * pode ou não editar este perfil.
                                 * O teste é feito seguindo 2 regras de negócio
                                 *      1 - o usuario só pode editar se tiver permissao de num 6
                                 *      2 - o usuário nunca poderá editar seu próprio perfil.
                                 */
                                if(!VerificacaoAcesso::userCan(6) || $perfilUsuario->IDPerfil == $perfil['IDPerfil']){
                                    $edicao = false;
                                    $disabled = 'disabled';
                                }
                                else{
                                    $edicao = true;
                                    $disabled = '';
                                }
                            ?>

                            <div class="panel panel-default perfil">
                                <div class="panel-heading" role="tab" id="head_perfil_<?=$perfil['IDPerfil']?>">
                                    <h4 class="panel-title">
                                        <a 
                                            role="button" data-toggle="collapse" data-parent="#accordion" 
                                            href="#perfil_<?=$perfil['IDPerfil']?>" 
                                            aria-expanded="true" 
                                            aria-controls="collapse<?=$perfil['IDPerfil']?>"><?=$perfil['DSPerfil']?></a>
                                    </h4>
                                    <input type="text" class="form-control" name="DSPerfilAlterado" value="<?=$perfil['DSPerfil']?>" style="display:none;"/>
                                    <?php if( $edicao ):?>
                                    <div class="opcoes">
                                        <a 
                                            class="btn btn-primary salvar" 
                                            href="javascript:void(0)" 
                                            role="button" 
                                            style="display:none;"
                                            data-id="<?=$perfil['IDPerfil']?>"
                                            >
                                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> salvar
                                        </a>
                                        <a class="btn btn-default excluir" href="#" role="button"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <div id="perfil_<?=$perfil['IDPerfil']?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="perfil_<?=$perfil['IDPerfil']?>">
                                    <div class="panel-body">
                                        <div>
                                            <?php if( $edicao ):?>
                                                <p><a class='editarNomePerfilSGSA' href="javascript:void(0);">Editar nome <span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></p>
                                            <?php endif;?>
                                            <h5>Grupos e permissões</h5>
                                            <input name="permissoes" type="hidden" value="<?=str_replace('"','',json_encode($perfil['permissoes']))?>"/>
                                        </div>
                                        <div class="grupos">
                                            <div class="grupo">
                                                <div>Solic. de acesso</div>
                                                <span><input <?=$disabled?> type="checkbox" value="60" <?=in_array(60,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="61" <?=in_array(61,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Chaves de aut.</div>
                                                <span><input <?=$disabled?> type="checkbox" value="55" <?=in_array(55,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="56" <?=in_array(56,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Plataformas</div>
                                                <span><input <?=$disabled?> type="checkbox" value="50" <?=in_array(50,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="51" <?=in_array(51,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                                <span><input <?=$disabled?> type="checkbox" value="52" <?=in_array(52,$perfil['permissoes'])?'checked':''?>> Perf. Plata.</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Compo Funcionais</div>
                                                <span><input <?=$disabled?> type="checkbox" value="45" <?=in_array(45,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="46" <?=in_array(46,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Locais</div>
                                                <span><input <?=$disabled?> type="checkbox" value="40" <?=in_array(40,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="41" <?=in_array(41,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Serviços</div>
                                                <span><input <?=$disabled?> type="checkbox" value="35" <?=in_array(35,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="36" <?=in_array(36,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Projetos</div>
                                                <span><input <?=$disabled?> type="checkbox" value="30" <?=in_array(30,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="31" <?=in_array(31,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                                <span><input <?=$disabled?> type="checkbox" value="32" <?=in_array(32,$perfil['permissoes'])?'checked':''?>> Excluir</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Contratos</div>
                                                <span><input <?=$disabled?> type="checkbox" value="25" <?=in_array(25,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="26" <?=in_array(26,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                                <span><input <?=$disabled?> type="checkbox" value="27" <?=in_array(27,$perfil['permissoes'])?'checked':''?>> Excluir</span>
                                            </div>
                                            
                                            <div class="grupo">
                                                <div>Clientes</div>
                                                <span><input <?=$disabled?> type="checkbox" value="20" <?=in_array(20,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="21" <?=in_array(21,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                                <span><input <?=$disabled?> type="checkbox" value="22" <?=in_array(22,$perfil['permissoes'])?'checked':''?>> Excluir</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Empresas</div>
                                                <span><input <?=$disabled?> type="checkbox" value="15" <?=in_array(15,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="16" <?=in_array(16,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                                <span><input <?=$disabled?> type="checkbox" value="17" <?=in_array(17,$perfil['permissoes'])?'checked':''?>> Excluir</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Usuarios</div>
                                                <span><input <?=$disabled?> type="checkbox" value="10" <?=in_array(10,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="11" <?=in_array(11,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                                <span><input <?=$disabled?> type="checkbox" value="12" <?=in_array(12,$perfil['permissoes'])?'checked':''?>> Excluir</span>
                                                <span><input <?=$disabled?> type="checkbox" value="13" <?=in_array(13,$perfil['permissoes'])?'checked':''?>> Promover</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Perfis</div>
                                                <span><input <?=$disabled?> type="checkbox" value="5" <?=in_array(5,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="6" <?=in_array(6,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                            </div>
                                            <div class="grupo">
                                                <div>Configurações</div>
                                                <span><input <?=$disabled?> type="checkbox" value="1" <?=in_array(1,$perfil['permissoes'])?'checked':''?>> Ler</span>
                                                <span><input <?=$disabled?> type="checkbox" value="2" <?=in_array(2,$perfil['permissoes'])?'checked':''?>> Alterar</span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif;?>

            </div>
        </div>
    </div>

    <?php include SIGSA_PATH.'view/modals/modal_cadastro_perfil_SIGSA.php'; ?>

</div>