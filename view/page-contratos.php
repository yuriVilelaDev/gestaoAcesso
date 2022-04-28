<?php
wp_enqueue_style(sigsaClass::get_prefix().'_style');
wp_enqueue_script(sigsaClass::get_prefix().'_script');
wp_enqueue_script(sigsaClass::get_prefix().'_Contratos');
wp_enqueue_media ();
require_once SIGSA_PATH . 'inc/class_Contratos.php';
require_once SIGSA_PATH . 'inc/class_Empresas.php';

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
    $contrato = Contratos::getContrato($dados);
    //Contratos::printa($contrato);
    ?>
    <div class="plugin-content" style="opacity: 0;">

        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <input type="hidden" name="page" id="page" value="<?=$_REQUEST['page']?>"/>
        <div class="page-header">
            <h3>Cadastro de contrato <small><?=(isset($contrato->CDContrato))?$contrato->CDContrato:'';?></small></h3>
        </div>
        <div class="page-content cadastro">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#cadastro" aria-controls="cadastro" role="tab" data-toggle="tab">Dados do contrato</a></li>
                <li role="presentation" class=""><a href="#anexos" aria-controls="anexos" role="tab" data-toggle="tab">Anexos</a></li>
                <li role="presentation" class=""><a href="#projetos" aria-controls="projetos" role="tab" data-toggle="tab">Projetos</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- TAB Cadastro -->
                <div role="tabpanel" class="tab-pane fade in active" id="cadastro">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
                        <?php 
                            include SIGSA_PATH.'view/forms/cadastro-contrato.php';
                        ?>
                        <br><br>
                        <button type="button" class="btn btn-primary bt_salvar_cadastro"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="display: none;"></i>Salvar</button>
                        <a href="?page=<?=$_REQUEST['page']?>" type="button" class="btn btn-danger excluirContrato"><i class="fa fa-trash-o"></i>Excluir</a>
                    </div>
                </div>
                <!-- TAB Anexos -->
                <div role="tabpanel" class="tab-pane fade" id="anexos">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
                        <div>
                            <button type="button" class="btn btn-primary anexar_arquivo">Anexar arquivo</button>
                        </div>
                        <br>
                        <div id="conteudo_anexos">
                            <?php echo Contratos::getListaAnexos($contrato->DSAnexosJSON);?>
                        </div>
                    </div>
                </div>
                <!-- TAB Projetos -->
                <div role="tabpanel" class="tab-pane fade" id="projetos">
                    <div class="conteudo_aba">
                        <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>
                        <div>
                        <button type="button" class="btn btn-default bt_adicionar_projeto">Adicionar projeto</button>
                        </div>
                        <br>
                        <div class="projetos">
                            <?php
                                if( isset($contrato->projetos) ){
                                    $projetos = $contrato->projetos;
                                    include SIGSA_PATH.'view/tables/list-contrato-projetos.php';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="linha_fundo">
                <button type="button" class="btn btn-default bt_voltar">Voltar</button>
            </div>
        </div>
    </div>
    <?php include SIGSA_PATH.'view/modals/modal_geral.php'; ?>
    <?php
    // Fim CADASTRO
    
}else{

    //PÁGINA DE LISTAGEM DE CONTRATOS
    ?>
    <div class="plugin-content" style="opacity: 0;">
        <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
        <div class="page-header">
            <h3>Contratos <small>Lista de contratos </small></h3>
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
            'quantidade_registros'  => Contratos::getQuantidadeContratos()
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
                <button type="button" class="btn btn-primary btn-sm adicionarContrato">Adicionar</button>
            </div>
        <?php
        $conteudo_personalizado = ob_get_clean();
        include SIGSA_PATH.'view/component-list-header.php';
        ?>

        <?php 
            $filtro['q_registros'] = $q_registros;
            $contratos = Contratos::getListaContratos($filtro);
            //Contratos::printa($contratos);
            include SIGSA_PATH.'view/tables/list-contratos.php';
        ?>
        <?php //include SIGSA_PATH.'inc/modals/modal_cadastro_cliente.php'; ?>

    </div>
    <?php
    // FIM LISTAGEM DE CLIENTES
}
?>
