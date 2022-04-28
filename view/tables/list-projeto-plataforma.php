<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-projetos.php' );
$filtro = array(
    'IDProjeto' => $_REQUEST['ID']
);
$plataformas_cadastradas = Plataforma::getListaRegistros($filtro);
//$plataformas_disponiveis = Plataforma::getListaRegistros();
//Plataforma::printa($plataformas_disponiveis);
?>

<style>
.pesquisa_projeto_plataforma{
    max-width: 460px;
}
.plataformas-disponiveis{max-width: 600px;background-color:#fff;margin:20px 0;padding:16px;position: relative;}
.plataformas-disponiveis table{margin:0px;}
.plataformas-disponiveis a.fechar{display: block;width: 26px;height: 1.5em;line-height: 1.5em;float: right;text-align: center;position: absolute;right: 16px;top: 10px;color: #000;}
.plataformas-disponiveis tr{cursor:pointer;}
.lista_plataformas_vinculadas{max-width: 600px;background-color:#fff;margin:20px 0;padding:16px}
.lista_plataformas_vinculadas table{margin:0px;}

</style>

<div class="pesquisa_projeto_plataforma">
    <div class="input-group">
        <input class="form-control" aria-label="Text input with segmented button dropdown">
        <div class="input-group-btn"> 
            <button type="button" class="btn btn-default">Pesquisar</button> 
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> 
            </button> 
            <ul class="dropdown-menu dropdown-menu-right"> 
                <li><a href="#" id="listar_todas_plataformas">Listar Todas</a></li>
            </ul> 
        </div> 
    </div>
</div>

<div class="plataformas-disponiveis caixa" style="display:none">
    <a href="#" class="fechar"><i class="fa fa-times" aria-hidden="true"></i></a>
    <table class="table table-condensed table-hover">
        <thead>
            <tr>
                <th colspan="2">Plataformas pesquisadas</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="lista_plataformas_vinculadas caixa">

    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <th colspan="2">Plataformas cadastradas no projeto</th>
            </tr>
        </thead>
        <tbody>
            <tr id="tr_modelo_plataforma_cadastrada" style="display:none;">
                <td class="nome"></td>
                <td>
                    <a href="#" data-id="" class="desvincularPlataforma">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            <?php
            
            foreach($plataformas_cadastradas as $plataforma){
                //$logo_src = wp_get_attachment_image_url($empresa->IMLogoEmpresa,'thumbnail');
                ?>
                <tr id="PLAT_<?=$plataforma->IDPlataforma?>"> 
                    <td class="nome"><?=$plataforma->NMPlataforma?></td>
                    <td>
                        <a 
                        href="#" data-id="<?=$plataforma->IDPlataforma?>"
                        class="desvincularPlataforma"
                        >
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>

                <?php
            }
            
        ?>
        </tbody>
    </table>

</div>