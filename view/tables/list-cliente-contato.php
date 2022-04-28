<ul class="table-cadastros table-contatos">
    <li><i class="fa fa-map" aria-hidden="true"></i> Contatos</li>
    <li>
        <table class="table table-hover">
            <tr>
                <td>Tipo</td>
                <td>Nome</td>
                <td>Setor</td>
                <td>Cargo</td>
                <td>E-mail</td>
                <td>Telefone</td>
                <td>Ações</td>
            </tr>
        <?php
            foreach($contatos as $contato){
                //Empresas::printa($CDTipoEnderecoClienteJSON);
                $iconesHTML = '';
                foreach($contato->tipo as $CDtipo){
                    $tipo = Empresas::getGERMetadadoValue( intval($CDtipo) );
                    $tipoI = preg_replace(array("/(A|ã)/","/(E|ê)/","/(F)/","/(G)/","/(I)/","/(O|ó)/","/(P)/","/(T)/","/(U)/","/(C)/","/ /"),explode(" ","a e f g i o p t u c -"),$tipo);
                    //$class .= ' '.$tipo;
                    $iconesHTML .= '<div class="icone '. $tipoI .'"><i class="fa fa-map-o" aria-hidden="true"></i> '.$tipo.'</div>';
                }
                ?>    
                <tr>
                    <td><?=$iconesHTML?></div>
                    <td><?=$contato->nome?></div>
                    <td><?=$contato->setor?></div>
                    <td><?=$contato->cargo?></div>
                    <td><?=$contato->email?></div>
                    <td>
                        <?php
                            if($contato->telefone){
                                //Clientes::printa($endereco->end_telefone);
                                foreach($contato->telefone as $telefone){
                                    echo '<div>'.$telefone->numero.'-'.$telefone->tipo.'</div>';
                                    //Empresas::printa($telefone);
                                }
                            }
                        ?>
                    </td>
                    <td>
                        <a 
                        href="javascript:void(0)"
                        data-id="<?=$contato->con_id?>"
                        class="editarContato"
                        >
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <a 
                        href="javascript:void(0)"
                        data-id="<?=$contato->con_id?>"
                        data-tipo="contato"
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