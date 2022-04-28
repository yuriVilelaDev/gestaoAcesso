<?php
    require_once SIGSA_PATH . 'inc/class_Usuario.php';
    require_once SIGSA_PATH . 'inc/class_Validador.php';

    wp_enqueue_style(sigsaClass::get_prefix().'_style');
    wp_enqueue_script(sigsaClass::get_prefix().'_script');
    wp_enqueue_script(sigsaClass::get_prefix().'_Usuario');
    wp_enqueue_media ();
?>

    <div class="plugin-content" style="opacity: 0;">
        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <div class="page-header">
            <h3>Gestão de Usuario <small></small></h3>
        </div>
        <div class="page-content cadastro_cliente">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class=""><a href="#aba1" aria-controls="cliente" role="tab" data-toggle="tab">Listas de Usuarios</a></li>
                <li role="presentation" class=""><a href="#aba2" aria-controls="cliente" role="tab" data-toggle="tab">Gestão Perfis SIGSA</a></li>
                <li role="presentation" class=""><a href="#aba3" aria-controls="cliente" role="tab" data-toggle="tab">Gestão Perfis Plataformas</a></li>
                <li role="presentation" class=""><a href="#aba4" aria-controls="cliente" role="tab" data-toggle="tab">Gestão Acesso</a></li>                 
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- TAB Contratos -->
                <div role="tabpanel" class="tab-pane fade" id="aba2">
                    <div class="conteudo_aba">
                       
                        <form class="row gy-2 gx-3 align-items-center">
    
                            <div class="input-group-addon">
                                <label>Empresa</label>
                                <?php echo Usuario::selectEmpresas();?>
                            </div>
                            <div class="input-group-addon">
                                <label>Perfil</label>
                                <?php echo Usuario::selectPerfil();?>
                            </div>
                            <div class="input-group-addon">
                                <label>Usuario</label>
                                <?php echo Usuario::selectUsuarios(); ?>
                            </div>
                            <br>
                           
                            <input type="button" name="save" class="btn btn-primary" value="Salvar" id="atribuiPerfil">
                        </form>
                    </div>
                </div>
                <!-- TAB Projetos -->
                <div role="tabpanel" class="tab-pane fade" id="aba3">
                    <div class="conteudo_aba">
                    
                    </div>
                </div>
                <!-- TAB Plataformas -->
                <div role="tabpanel" class="tab-pane fade" id="aba1">
                    <div class="conteudo_aba">
                        <p>

                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                Adicionar Usuario
                            </button>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
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
                                <input type="text" class="form-control" name="nome" placeholder="nome"  id="nome">
                                <label class="control-label mensagem_erro" for="nome" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                           <div class="form-group require input-text">
                                <label for="email">email</label>
                                <input type="text" class="form-control" name="email" placeholder="email" id="email">
                                <label class="control-label mensagem_erro" for="email" style="display:none">Preencha o campo!</label> 
                            </div>
                            <div class="form-group require input-text">
                                <label for="senha">senha</label>
                                <input type="password"  class="form-control" name="senha" placeholder="senha" id="senha" > 
                            </div>
                        </div>                        
                    </div> 
                    <input type="button" name="save" class="btn btn-primary" value="Salvar" id="salvarUsuario">
                </form>
                            </div>
                        </div>
                        <?php echo Usuario::listarUsuario(null);?>
                    </div>
                </div>  
                <div role="tabpanel" class="tab-pane fade" id="aba4">
                    <div class="conteudo_aba">
                   
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php //include SIGSA_PATH.'inc/modals/modal_editar_usuario.php'; ?>