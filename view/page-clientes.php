<?php
wp_enqueue_style(sigsaClass::get_prefix().'_style');
wp_enqueue_script(sigsaClass::get_prefix().'_script');
wp_enqueue_script(sigsaClass::get_prefix().'_Clientes');
wp_enqueue_media ();

require_once SIGSA_PATH . 'inc/class_Clientes.php';
require_once SIGSA_PATH . 'inc/class_Empresas.php';

/**
 * 1 Verifica se a página é de listagem ou de cadastro
 * Para isso verifica-se se na URL existe o IDCliente expecifico de um cliente
 * Se existir ele exibe a página de cadastro do cliente. 
 * Caso contrário ele exibe a lista de clientes.
 */
if( isset($_REQUEST['IDCliente']) ){

    $dados = array(
        'IDCliente' => $_REQUEST['IDCliente'] 
    );
    $cliente = json_decode(Clientes::getCliente($dados));
    $cliente = $cliente->Cliente;
    //Clientes::printa($cliente);
    
    // PÁGINA DE CADASTRO DO CLIENTE
    ?>
    <div class="plugin-content" style="opacity: 0;">

        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <input type="hidden" name="page" id="page" value="<?=$_REQUEST['page']?>"/>
        <div class="page-header">
            <h3>Cadastro do cliente <small><?=(isset($cliente->razao_social))?$cliente->razao_social:'';?></small></h3>
        </div>
        <div class="page-content cadastro_cliente">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#cliente" aria-controls="cliente" role="tab" data-toggle="tab">Dados do Cliente</a></li>
                <li role="presentation" class=""><a href="#aba2" aria-controls="cliente" role="tab" data-toggle="tab">Contratos</a></li>
                <li role="presentation" class=""><a href="#aba7" aria-controls="aba2" role="tab" data-toggle="tab">Hierarquia de contratos</a></li>
                
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- TAB Cliente -->
                <div role="tabpanel" class="tab-pane fade in active" id="cliente">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

                        <?php
                            //Clientes::printa($cliente);
                            include SIGSA_PATH.'view/forms/cadastro-cliente.php';
                        ?>
                        <hr>
                        <div>
                            <button type="button" class="btn btn-default bt_adicionar_endereco">Adicionar endereço</button>
                            <button type="button" class="btn btn-default bt_adicionar_contato">Adicionar Contato</button>
                            <?php 
                                /** ENDEREÇOS CADASTRADOS DO SISTEMA PARA O CLIENTE*/
                                if(isset($cliente->enderecos)){ 
                                    $enderecos = $cliente->enderecos;
                                    $enderecos = strval($cliente->enderecos);
                                    $enderecos = json_decode($enderecos);
                                    //Clientes::printa($enderecos);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php include SIGSA_PATH.'view/tables/list-cliente-endereco.php';?>
                                        </div>
                                    </div>
                                <?php }
                            ?>
                        </div>
                        <div>
                            <?php 
                                /** CONTATOS CADASTRADOS DO SISTEMA PARA O CLIENTE*/
                                if(isset($cliente->contatos)){ 
                                    $contatos = $cliente->contatos;
                                    $contatos = strval($cliente->contatos);
                                    $contatos = json_decode($contatos);
                                    //Clientes::printa(count($contatos));
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php include SIGSA_PATH.'view/tables/list-cliente-contato.php';?>
                                        </div>
                                    </div>
                                <?php }
                            ?>
                        </div>
                    </div>

                </div>
                <!-- TAB Contratos -->
                <div role="tabpanel" class="tab-pane fade" id="aba2">
                    <div class="conteudo_aba">
                        <?php if( VerificacaoAcesso::userCan(30) ):?>
                        <div><button type="button" class="btn btn-default bt_adicionar_contrato">Adicionar contrato</button></div>
                        <?php endif;?>
                        <br>
                        <?php include SIGSA_PATH.'view/tables/list-cliente-contratos.php';?>
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

            <div class="linha_fundo">
                <button type="button" class="btn btn-primary bt_salvar_cliente"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="display: none;"></i>Salvar</button>
                <button type="button" class="btn btn-default bt_voltar">Voltar</button>
            </div>

        </div>
    </div>
    <?php include SIGSA_PATH.'view/modals/modal_cadastro_cliente_endereco.php'; ?>
    <?php include SIGSA_PATH.'view/modals/modal_cadastro_cliente_contato.php'; ?>
    <?php include SIGSA_PATH.'view/modals/modal_geral.php'; ?>
    <?php
    // Fim CADASTRO DO CLIENTE
    

}else{


    //PÁGINA DE LISTAGEM DE CLIENTES
    
    require_once SIGSA_PATH . 'inc/class_Empresas.php';
    require_once SIGSA_PATH . 'inc/class_Validador.php';

    $Clientes = Clientes::get_instance();
    ?>
    <div class="plugin-content" style="opacity: 0;">
        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <div class="page-header">
            <h3>Clientes <small>Lista de clientes </small></h3>
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
            'quantidade_registros'  => $Clientes->getQuantidadeClientes()
        );
        ob_start();
        ?>
            <div class="form-group form-group-sm empresas">
                <div class="input-group">
                    <div class="input-group-addon">Empresa</div>
                    <select class="form-control select" name="IDEmpresa">
                        <option value="" <?=(!isset($_REQUEST['IDEmpresa']))?'selected="selected"':''?>>Todas</option>
                        <?php
                            if( isset($_REQUEST['IDEmpresa']) )$id_empresa_selecionada = $_REQUEST['IDEmpresa'];
                            else $id_empresa_selecionada = null;
                            $Empresas =  Empresas::getEmpresas();
                            Empresas::printa($Empresas);
                            foreach ($Empresas as $empresa){ ?>
                                <option value="<?=$empresa->IDEmpresa?>" 
                                    <?=($id_empresa_selecionada == $empresa->IDEmpresa)?'selected="selected"':''?>
                                ><?=$empresa->NMRazaoEmpresa?></option>
                            <?php } 
                        ?>
                    </select>   
                </div>
            </div>
            <div class="form-group form-group-sm projeto">
                <button type="button" class="btn btn-primary btn-sm adicionarCliente">Adicionar</button>
            </div>
        <?php
        $conteudo_personalizado = ob_get_clean();
        include SIGSA_PATH.'view/component-list-header.php';
        ?>

        <?php 
            $filtro['q_registros'] = $q_registros;
            echo $Clientes->getListaClientesHTML($filtro); 
        ?>
        <?php include SIGSA_PATH.'view/modals/modal_cadastro_cliente.php'; ?>

    </div>
    <?php
    // FIM LISTAGEM DE CLIENTES
}