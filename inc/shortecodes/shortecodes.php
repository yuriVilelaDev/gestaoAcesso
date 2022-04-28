<?php

function shortcodes_init(){
    add_shortcode( 'solicitacaoAcessoForm', 'shortcode_solicitacaoAcessoForm_function' );
}
add_action('init', 'shortcodes_init');


function shortcode_solicitacaoAcessoForm_function( $atts, $content, $tag ){
    wp_enqueue_style(sigsaClass::get_prefix().'_style');
    wp_enqueue_script(sigsaClass::get_prefix().'_script');
    wp_enqueue_script(sigsaClass::get_prefix().'_Shortecode');
    wp_enqueue_script(sigsaClass::get_prefix().'_SolicitacaoAcesso');


    $a = shortcode_atts( array(
        'attr1' => 'atributo1',
        'atrr2' => 'atributo2'
    ), $atts );
    ob_start();
    ?>

    <div class="shortecode">
        <header>Solicitação de acesso</header>
        
        <div class="content">

            <!-- formulario de entrada e verificação ajax do e-mail-->
            <div>
                <p>Amigo(a), para fazer uma solicitação de acesso é necessário logar-se em nosso sistema. Escolha uma das opções abaixo</p>
                
                <div class="identificador">
                    <ul class="nav nav-pills" role="tablist">
                        <li role="presentation" class=""><a href="#tab_identificador1" aria-controls="tab_identificador1" role="tab" data-toggle="tab">Já tenho cadastro</a></li>
                        <li role="presentation" class="active"><a href="#tab_identificador2" aria-controls="tab_identificador2" role="tab" data-toggle="tab">Quero me cadastrar</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- TAB Login -->
                        <div role="tabpanel" class="tab-pane fade" id="tab_identificador1">
                            <div class="row">
                                <!-- FORM fazer login -->
                                <form 
                                    id="form_shortecode_login"
                                    class="form-horizontal col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Email</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <div class="checkbox">
                                                <a href="/esqueciminhasenha/" id='bt_esqueceuasenha'>Esqueceu a senha?</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <button  class="btn "id="validaAcesso">Entrar</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- FORM esqueceu a senha -->
                                <form 
                                    id="form_shortecode_esqueceuasenha"
                                    class="col-md-10 col-md-offset-1" style="display:none;">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                                    </div>
                                    <button type="button" class="btn btn-default bt_cancelar">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </form>
                            </div>
                        </div>
                        <!-- TAB Cadastre-se -->
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_identificador2">
                            <div class="row">
                                <!-- FORM Novo cadastro -->
                                <form 
                                    id="form_shortecode_validaChave"
                                    class="form-horizontal col-md-10 col-md-offset-1">
                                    
                                    <div class="form-group">
                                        <br/>
                                        <label for="chave_autenticacao" class="col-sm-4 control-label">Chave Autenticação </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="chave_autenticacao" placeholder="Chave Autenticação">
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-8">
                                    <button class="btn btn-default" id="validaChave">Enviar</button>
                                      
                                    </div>
                                </form>
                                <form 
                                    id="form_shortecode_cadastro"
                                    class="form-horizontal col-md-10 col-md-offset-1">
                                    <div class="form-group require input-text email">
                                        <label for="inputEmail3" class="col-sm-4 control-label">E-mail</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="Email" value="">
                                            <label class="control-label mensagem_erro" id="email" for="email" style="display:none">Preencha o campo!</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nome" class="col-sm-4 control-label">Nome</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="nome" class="form-control" placeholder="Nome completo" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nome" class="col-sm-4 control-label">Data de Nascimento</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="dataNascimento" class="form-control" placeholder="01/01/1999">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nome" class="col-sm-4 control-label">Sexo</label>
                                        <div class="col-sm-8">
                                        <select class="form-control" id="sexo" name="sexo">
                                                <option data-class="" value=""></option>
                                                <option data-class="" value="M">Masculino</option>
                                                <option data-class="" value="F">Feminino</option>
                                                <option data-class="" value="O">outro</option>
                                                
                                            </select>
                                        </div>
                                        </div>
                                        <div class="form-group">
                                        <label for="telefone" class="col-sm-4 control-label">Telefone <span>1</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="telefone" placeholder="(27)99999-8888" value="(xx)">
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="tipo_telefone">
                                                <option data-class="" value="">Tipo</option>
                                                <option data-class="" value="Celular">Celular</option>
                                                <option data-class="" value="Fixo">Fixo</option>
                                                <option data-class="" value="Whatsapp">Whatsapp</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-5 col-sm-offset-4">
                                            <a>adicionar contato</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">Senha</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <button class="btn btn-default" id="CadastraAcesso">Enviar</button>
                                        </div>
                                    </div>
                                    </div>
                                  
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
        
        <footer>Grupo Actcon</footer>
    </div>
    <?php
    return ob_get_clean();
}
?>

