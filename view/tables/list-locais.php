<?php
$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-locais.php' );
?>
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Endereço</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach($registros as $local){
            //$logo_src = wp_get_attachment_image_url($empresa->IMLogoEmpresa,'thumbnail');
            $endereco = json_decode($local->EDEnderecoLocalJSON);
            ?>
            <tr id="ID<?=$local->IDLocal?>"> 
                <td><?=$local->IDLocal?></td>
                <td class="id">
                    <a
                    href="<?=$url_plugin.'&ID='.$local->IDLocal?>"
                    data-id="<?=$local->IDLocal?>"
                    class="editarLocal">
                        <?=$local->NMLocal?>
                    </a>
                </td>
                <td class="endereco">
                    <?=(isset($endereco->logradouro))?$endereco->logradouro:''?>
                    <?=(isset($endereco->numero))?','.$endereco->numero:''?>
                    <?=(isset($endereco->bairro))?','.$endereco->bairro:''?>
                    <?=(isset($endereco->localidade))?','.$endereco->localidade:''?>
                    <?=(isset($endereco->uf))?'-'.$endereco->uf:''?>
                </td>
                <td>
                    <a 
                    href="<?=$url_plugin.'&ID='.$local->IDLocal?>"
                    data-id="<?=$local->IDLocal?>"
                    class="editarLocal"
                    >
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <a 
                    href="<?=$url_plugin.'&ID='.$local->IDLocal?>"
                    data-id="<?=$local->IDLocal?>"
                    data-descricao="<?=$local->NMLocal?>"
                    class="excluirLocal"
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