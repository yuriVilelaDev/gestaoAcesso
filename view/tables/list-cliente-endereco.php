<ul class="table-cadastros table-enderecos">
    <li><i class="fa fa-map" aria-hidden="true"></i> Endereços</li>
    <li>
        <table class="table table-hover">
            <tr><td>Tipo</td><td>Endereço</td><td>Telefones</td><td>Ações</td></tr>
        <?php
            foreach($enderecos as $endereco){
                //Empresas::printa($CDTipoEnderecoClienteJSON);
                $iconesHTML = '';
                foreach($endereco->end_tipo as $CDtipo){
                    $tipo = Empresas::getGERMetadadoValue( intval($CDtipo) );
                    $tipoI = preg_replace(array("/(A|ã)/","/(E|ê)/","/(F)/","/(G)/","/(I)/","/(O|ó)/","/(P)/","/(T)/","/(U)/","/(C)/","/ /"),explode(" ","a e f g i o p t u c -"),$tipo);
                    //$class .= ' '.$tipo;
                    $iconesHTML .= '<div class="icone '. $tipoI .'"><i class="fa fa-map-o" aria-hidden="true"></i> '.$tipo.'</div>';
                }
                ?>    
                <tr>
                    <td><?=$iconesHTML?></div> 
                    <td>
                        <?=$endereco->end_end->logradouro?>,
                        <?=$endereco->end_logradouro?>, 
                        <?=$endereco->end_end->localidade?>-
                        <?=$endereco->end_end->uf?>
                    </td>
                    <td>
                        <?php
                            if($endereco->end_telefone){
                                //Clientes::printa($endereco->end_telefone);
                                foreach($endereco->end_telefone as $telefone){
                                    echo '<div>'.$telefone->numero.'-'.$telefone->tipo.'</div>';
                                    //Empresas::printa($telefone);
                                }
                            }
                            
                        ?>
                    </td>
                    <td>
                        <a 
                        href="javascript:void(0)"
                        data-id="<?=$endereco->end_id?>"
                        class="editarEndereco"
                        >
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <a 
                        href="javascript:void(0)"
                        data-id="<?=$endereco->end_id?>"
                        data-tipo="endereco"
                        class="excluirDadosCliente"
                        >
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        ?>
        </table>
    </li>
</ul>