<?php
wp_enqueue_style(sigsaClass::get_prefix().'_style');
wp_enqueue_script(sigsaClass::get_prefix().'_DadosAdicionais');
wp_enqueue_script(sigsaClass::get_prefix().'_Empresas');
wp_enqueue_media ();

require_once SIGSA_PATH . 'inc/class_DadosAdicionais.php';

if( ! isset( $_REQUEST['aba'] ) ) $_REQUEST['aba'] = 'dados_adicionais';

?>


<div class="plugin-content" style="opacity: 0;">
    <input type="hidden" id="SIGSA_URL" value="<?=SIGSA_URL;?>"/>
    <input type="hidden" id="page" name="page" value="<?=$_REQUEST['page']?>"/>
    <div class="page-header">
        <h3>Configurações <small>opções de configuração</small></h3>
    </div>

    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="<?=($_REQUEST['aba']=='dados_adicionais')?'active':''?>">
                <a href="#dados_adicionais" aria-controls="dados_adicionais" role="tab" data-toggle="tab">Dados Adicionais</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

            <!-- TAB dados_adicionais -->
            <div role="tabpanel" class="tab-pane fade in <?=($_REQUEST['aba']=='dados_adicionais')?'active':''?>" id="dados_adicionais">
                <div class="conteudo_aba">
                    <?php 
                        $DadosAdicionais = DadosAdicionais::get_instance();
                        echo $DadosAdicionais->getListaSelectsHTML(null);
                    ?>
                </div>
            </div>
        </div>

    </div>


</div>



























