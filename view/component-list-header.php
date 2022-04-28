<?php
if( isset( $_REQUEST['page'] ) ) $page = $_REQUEST['page']; else $page = '';
if( isset( $_REQUEST['s'] ) ) $search = $_REQUEST['s']; else $search = '';

if( !isset( $_REQUEST['IDEmpresa']) )    $_REQUEST['IDEmpresa'] = "";
if( isset( $_REQUEST['p']) )$pagina = $_REQUEST['p']; else $pagina = 1;

// quantidade de paginas

/* -- quantidade de registros desejados */
if( isset($_REQUEST['q_registros']) ){
    //caso eu altere o numero registros
    $q_registros = $_REQUEST['q_registros'];
    update_option(sigsaClass::get_prefix().'q_registros',$_REQUEST['q_registros']);
}
else{
    //caso eu puxe do sistema
    $q_registros = get_option( sigsaClass::get_prefix().'q_registros' );
    if(!$q_registros){
        // se ele nao existir no sistema
        $q_registros = 2;
        add_option( sigsaClass::get_prefix().'q_registros' , $q_registros);
    }	
}

/* -- quantidade de páginas necessárias para listar todos os registros */
$quantidadeRegistros = $component_list_header['quantidade_registros'];
$q_paginas = ceil($quantidadeRegistros / $q_registros);
?>

<div class="page_header">
    <?php if($component_list_header['search']):?>
    <div class="row">
        <div class="col-md-12">
            <div class="search-box">
                <form method="get">
                    <input type="hidden" name="page" value="<?=$page?>"/>
                    <input type="search" name="s" value="<?=$search?>">
                    <input type="submit" class="button bt_search" value="Pesquisar">
                </form>
            </div>
        </div>
    </div>
    <?php endif;?>
    <div class="row">
        <div class="col-md-12">
        
            <div class="linha_opcoes form-inline">
                <input type="hidden" name="q_paginas" class="q_paginas" value="<?=$q_paginas?>"/>
                <form method="get">
                    <input type="hidden" name="page" value="<?=$page?>"/>
                    <input type="hidden" name="p" class="pagina" value="<?=$pagina?>"/>  
                    <div class="left">
                        <?=$conteudo_personalizado;?>
                    </div>
                    <div class="right">
                        <?php
                        if($q_registros && $component_list_header['controle_registros']):
                        ?>
                            
                            <div class="form-group form-group-sm quantidade_registros">
                                <div class="input-group">
                                <select class="form-control select" name="q_registros">
                                    <option <?=($q_registros == '2')?'selected="selected"':''?>>2</option>
                                    <option <?=($q_registros == '20')?'selected="selected"':''?>>20</option>
                                    <option <?=($q_registros == '50')?'selected="selected"':''?>>50</option>
                                    <option <?=($q_registros == '100')?'selected="selected"':''?>>100</option>
                                </select>
                                <div class="input-group-addon">itens/página</div>
                                </div>
                            </div>
                        <?php 
                            endif;
                        ?>

                        <?php if( $q_paginas > 1 && $component_list_header['paginacao']):?>
                        <div class="form-group">
                            <nav aria-label="Page navigation pagination-sm" class="paginacao form-group">
                            
                            <ul class="pagination">
                                <li>
                                <a href="javascript:void(0)" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                                </li>
                                <?php
                                    for( $i = 1 ; $i<=$q_paginas ; $i++ ){?>
                                        <li><a href="javascript:void(0)" aria-label="<?=$i?>" <?=($i == $pagina)? 'class="select"':''?> > <?=$i?></a></li>		
                                    <?php }
                                ?>
                                <li>
                                <a href="javascript:void(0)" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                                </li>
                            </ul>
                            </nav>
                        </div>
                        <?php endif;?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>