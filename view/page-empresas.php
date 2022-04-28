<?php
wp_enqueue_style(sigsaClass::get_prefix().'_style');
wp_enqueue_script(sigsaClass::get_prefix().'_script');
wp_enqueue_script(sigsaClass::get_prefix().'_Empresas');
wp_enqueue_media ();

require_once SIGSA_PATH . 'inc/class_Empresas.php';
require_once SIGSA_PATH . 'inc/class_Validador.php';

/**
 * 1 Verifica se a página é de listagem ou de cadastro
 * Para isso verifica-se se na URL existe o IDEmpresa expecifico de uma empresa
 * Se existir ele exibe a página de cadastro da empresa. 
 * Caso contrário ele exibe a lista de empresas.
 */
if( isset($_REQUEST['IEempresa']) ){


    // PÁGINA DE CADASTRO DO EMPRESA
    ?>
    <div class="plugin-content" style="opacity: 0;">
        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <input type="hidden" id="page" name="page" value="<?=$_REQUEST['page']?>"/>
        <div class="page-header">
            <h3>Cadastro do cliente <small>Nome do cliente</small></h3>
        </div>
        <div class="page-content cadastro_cliente">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#cliente" aria-controls="cliente" role="tab" data-toggle="tab">Dados do Cliente</a></li>
                <li role="presentation" class=""><a href="#aba2" aria-controls="cliente" role="tab" data-toggle="tab">Contratos</a></li>
                <li role="presentation" class=""><a href="#aba3" aria-controls="cliente" role="tab" data-toggle="tab">Projetos</a></li>
                <li role="presentation" class=""><a href="#aba4" aria-controls="cliente" role="tab" data-toggle="tab">Plataformas</a></li>
                <li role="presentation" class=""><a href="#aba5" aria-controls="cliente" role="tab" data-toggle="tab">Serviços</a></li>
                <li role="presentation" class=""><a href="#aba6" aria-controls="cliente" role="tab" data-toggle="tab">Composição funcional</a></li>
                <li role="presentation" class=""><a href="#aba7" aria-controls="aba2" role="tab" data-toggle="tab">Hierarquia de contratos</a></li>
                
                
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- TAB Cliente -->
                <div role="tabpanel" class="tab-pane fade in active" id="cliente">
                    <div class="conteudo_aba">
                        <?php
                            $dados = array();
                            //$cliente = Clientes::getCliente($dados);
                        ?>
                        
                        <form id="form_cadastro_cliente" class="">
                            <input type="hidden" value="" name="IDEmpresa" id="IDEmpresa"/>
                            <input type="hidden" value="" name="IDCliente" id="IDCliente"/>
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div><label for="IMLogoCliente">Logo cliente</label></div>
                                        <div class="logo_IMLogoCliente">
                                            <img src=""/>
                                        </div>
                                        <div>
                                            <input type="hidden" value="" id="IMLogoCliente" name="" max="" min="1" step="1">
                                            <button class="set_IMLogoCliente button">Alterar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group require input-text">
                                        <label for="NMRazaoCliente">Razão social</label>
                                        <input type="text" class="form-control" name="NMRazaoCliente" placeholder="NMRazaoCliente">
                                        <label class="control-label mensagem_erro" for="NMRazaoCliente" style="display:none">Preencha o campo!</label> 
                                    </div>
                                    
                                    <div class="form-group require input-text">
                                        <label for="EDWebsiteCliente">Web site</label>
                                        <input type="text" class="form-control" name="EDWebsiteCliente" placeholder="EDWebsiteCliente">
                                        <label class="control-label mensagem_erro" for="EDWebsiteCliente" style="display:none">Preencha o campo!</label> 
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    
                                    <div class="form-group require input-text">
                                        <label for="NMFantasiaCliente">Nome fantasia</label>
                                        <input type="text" class="form-control" name="NMFantasiaCliente" placeholder="NMFantasiaCliente">
                                        <label class="control-label mensagem_erro" for="NMFantasiaCliente" style="display:none">Preencha o campo!</label> 
                                    </div>

                                    <div class="form-group require input-radio">
                                        <div>
                                            <label for="STCliente" control-label">Situação</label>                                
                                        </div>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="STCliente" value="1"> Ativo</label>
                                            <label class="radio-inline"><input type="radio" name="STCliente" value="0"> Inativo</label>
                                        </div>
                                        <label class="control-label mensagem_erro" for="STCliente" style="display:none">Selecione uma opção!</label>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group require input-text cpfCnpj">
                                        <label for="NUCnpjCliente">N CNPJ</label>
                                        <input type="text" class="form-control" name="NUCnpjCliente" placeholder="NUCnpjCliente">
                                        <label class="control-label mensagem_erro" for="NUCnpjCliente" style="display:none">Preencha o CNPJ corretamente!</label> 
                                    </div>
                                </div>
                            </div> 
                        </form>
                    
                    </div>
                </div>
                <!-- TAB Contratos -->
                <div role="tabpanel" class="tab-pane fade" id="aba2">
                    <div class="conteudo_aba">
                        
                    </div>
                </div>
                <!-- TAB Projetos -->
                <div role="tabpanel" class="tab-pane fade" id="aba3">
                    <div class="conteudo_aba">
                        
                    </div>
                </div>
                <!-- TAB Plataformas -->
                <div role="tabpanel" class="tab-pane fade" id="aba4">
                    <div class="conteudo_aba">
                        
                    </div>
                </div>
                <!-- TAB Serviços -->
                <div role="tabpanel" class="tab-pane fade" id="aba5">
                    <div class="conteudo_aba">
                        
                    </div>
                </div>
                <!-- TAB Composição funcional -->
                <div role="tabpanel" class="tab-pane fade" id="aba6">
                    <div class="conteudo_aba">
                        
                    </div>
                </div>
                <!-- TAB Hierarquia de contratos -->
                <div role="tabpanel" class="tab-pane fade" id="aba7">
                    <div class="conteudo_aba">
                        <div class="contrato">
                            <div class="title">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                Contrato 1
                            </div>
                            <ul class="projetos pasta">
                                <li>
                                    <div class=title>
                                    <i class="fa fa-folder-o" aria-hidden="true"></i> Projeto 1
                                    </div>
                                    <ul class="plataformas pasta">
                                        <h6>Plataformas</h6>
                                        <li>Plataforma 1</li>
                                        <li>Plataforma 2</li>
                                    </ul>
                                    <ul class="plataformas pasta">
                                        <h6>Serviços</h6>
                                        <li>Serviço 1</li>
                                        <li>Serviço 2</li>
                                    </ul>
                                    <ul class="plataformas pasta">
                                        <h6>Composições funcionais</h6>
                                        <li>Composicao funcional 1</li>
                                        <li>Composicao funcional 2</li>
                                        <li>Composicao funcional 3</li>
                                    </ul>
                                </li>
                                <li><i class="fa fa-folder-o" aria-hidden="true"></i> Projeto 2</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Fim CADASTRO DO CLIENTE


}else{


    //PÁGINA DE LISTAGEM DAS EMPRESAS

    
    /* -- quantidade de páginas necessárias para listar todos os registros */
    ?>
    <div class="plugin-content" style="opacity: 0;">
        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <div class="page-header">
            <h3>Empresas <small>Lista de empresas </small></h3>
        </div>

        <?php
        /** HEADER DE LISTAGEM 
         * passando a variavel $component-list-header como parametro para listagem do conteúdo do cabeçalho
         * A variável $conteudo_personalizado contem todo o conteudo a ser exibido a esquerda da linha.
         * 
        */
        $component_list_header = array(
            'search'                => true,
            'paginacao'             => true,
            'controle_registros'    => true,
            'quantidade_registros'  => Empresas::getQuantidadeEmpresas()
        );
        ob_start();
        ?>
            <button type="button" class="btn btn-primary btn-sm bt_adicionar_empresa">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Adicionar empresa
            </button>
        <?php
        $conteudo_personalizado = ob_get_clean();
        include SIGSA_PATH.'view/component-list-header.php';
        ?>

        <?php 
            $Empresas = Empresas::get_instance();
            $filtro['q_registros'] = $q_registros;
            echo $Empresas->getListaEmpresasHTML($filtro); 
        ?>
        <?php include SIGSA_PATH.'view/modals/modal_cadastro_empresa.php'; ?>
        <?php include SIGSA_PATH.'view/modals/modal_cadastro_empresa_contrato.php'; ?>
        <?php include SIGSA_PATH.'view/modals/modal_cadastro_empresa_endereco.php'; ?>
        <?php include SIGSA_PATH.'view/modals/modal_cadastro_empresa_contato.php'; ?>
        
    </div>
    <?php
    // FIM LISTAGEM DE CLIENTES
}