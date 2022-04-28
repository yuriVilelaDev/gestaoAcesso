<?php
wp_enqueue_style(sigsaClass::get_prefix().'_style');
wp_enqueue_script(sigsaClass::get_prefix().'_script');
wp_enqueue_script(sigsaClass::get_prefix().'_Projetos');
wp_enqueue_media ();
require_once SIGSA_PATH . 'inc/class_Projetos.php';
require_once SIGSA_PATH . 'inc/class_Empresas.php';
require_once SIGSA_PATH . 'inc/class_ComposicaoFuncional.php';
require_once SIGSA_PATH . 'inc/class_Plataforma.php';
require_once SIGSA_PATH . 'inc/class_Local.php';
require_once SIGSA_PATH . 'inc/class_ChaveAutenticacao.php';

/**
 * 1 Verifica se a página é de listagem ou de cadastro
 * Para isso verifica-se se na URL existe o ID expecifico
 * Se existir ele exibe a página de cadastro. 
 * Caso contrário ele exibe a lista de registros.
 */
if( isset($_REQUEST['ID']) ){

    // PÁGINA DE CADASTRO
    
    $dados = array(
        'ID' => $_REQUEST['ID'] 
    );
    $projeto = Projetos::getProjeto($dados);
    //Projetos::printa($projeto);
    ?>
    <div class="plugin-content" style="opacity: 0;">
        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <input type="hidden" name="page" id="page" value="<?=$_REQUEST['page']?>"/>
        <div class="page-header">
            <h3>Cadastro de projeto <small><?=(isset($projeto->NMProjeto))?$projeto->NMProjeto:'';?></small></h3>
        </div>
        <div class="page-content cadastro">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class=""><a href="#cadastro" aria-controls="cadastro" role="tab" data-toggle="tab">Dados do projeto</a></li>
                <li role="presentation" class=""><a href="#projeto_composicaofuncional" aria-controls="projeto_composicaofuncional" role="tab" data-toggle="tab">Composições funcionais</a></li>
                <li role="presentation" class=""><a href="#projeto_plataforma" aria-controls="projeto_plataforma" role="tab" data-toggle="tab">Plataformas</a></li>
                <li role="presentation" class=""><a href="#projeto_local" aria-controls="projeto_local" role="tab" data-toggle="tab">Locais</a></li>
                <li role="presentation" class="active"><a href="#projeto_chave" aria-controls="projeto_chave" role="tab" data-toggle="tab">Chave de autenficação</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- TAB Cadastro -->
                <div role="tabpanel" class="tab-pane fade in" id="cadastro">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
                        <?php
                            include SIGSA_PATH.'view/forms/cadastro-projeto.php';
                        ?>
                    </div>
                </div>

                <!-- TAB Composicoes Funcionais -->
                <div role="tabpanel" class="tab-pane fade in" id="projeto_composicaofuncional">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
                        <?php
                            include SIGSA_PATH.'view/tables/list-projeto-composicaoFuncional.php';
                        ?>
                    </div>
                </div>

                <!-- TAB Plataformas -->
                <div role="tabpanel" class="tab-pane fade in" id="projeto_plataforma">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
                        <?php
                            include SIGSA_PATH.'view/tables/list-projeto-plataforma.php';
                        ?>
                    </div>
                </div>

                <!-- TAB Locais -->
                <div role="tabpanel" class="tab-pane fade in" id="projeto_local">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
                        <?php
                            include SIGSA_PATH.'view/tables/list-projeto-local.php';
                        ?>
                    </div>
                </div>

                <!-- TAB Chave de autenticacao -->
                <div role="tabpanel" class="tab-pane fade in active" id="projeto_chave">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
                        <?php
                            include SIGSA_PATH.'view/tables/list-projeto-chave.php';
                        ?>
                    </div>
                </div>

            </div>

            <div class="linha_fundo">
                <button type="button" class="btn btn-default bt_voltar">Voltar</button>
            </div>
        </div>
    </div>
    <?php //include SIGSA_PATH.'view/modals/modal_cadastro_cliente_endereco.php'; ?>
    <?php //include SIGSA_PATH.'view/modals/modal_cadastro_cliente_contato.php'; ?>
    <?php
    // Fim CADASTRO
    
}else{

    //PÁGINA DE LISTAGEM DE PROJETOS
    ?>
    <div class="plugin-content" style="opacity: 0;">
        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <div class="page-header">
            <h3>Projetos <small>Lista de projetos </small></h3>
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
            'quantidade_registros'  => Projetos::getQuantidadeProjetos()
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
                            //Empresas::printa($Empresas);
                            foreach ($Empresas as $empresa){ ?>
                                <option value="<?=$empresa->IDEmpresa?>" 
                                    <?=($id_empresa_selecionada == $empresa->IDEmpresa)?'selected="selected"':''?>
                                ><?=$empresa->NMRazaoEmpresa?></option>
                            <?php } 
                        ?>
                    </select>   
                </div>
            </div>
            <div class="form-group form-group-sm">
                <button type="button" class="btn btn-primary btn-sm adicionarProjeto">Adicionar</button>
            </div>
        <?php
        $conteudo_personalizado = ob_get_clean();
        include SIGSA_PATH.'view/component-list-header.php';
        ?>

        <?php 
            $filtro['q_registros'] = $q_registros;
            $projetos = Projetos::getListaProjetos($filtro);
            //Projetos::printa($projetos);
            include SIGSA_PATH.'view/tables/list-projetos.php';
        ?>

    </div>
    <?php
    // FIM LISTAGEM DE CLIENTES
}
?>
