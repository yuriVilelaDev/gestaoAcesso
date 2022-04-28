<?php
    /* CODIGOS PHP PARA PREENCHIMENTO PRÉVIO DO PROJETO */
    $compfuncional_id   = ( isset($composicaoFuncional->IDCompFuncional) )? $composicaoFuncional->IDCompFuncional : '';
    $nome               = ( isset($composicaoFuncional->NMCompFuncional) )? $composicaoFuncional->NMCompFuncional : '';
    $sigla              = ( isset($composicaoFuncional->NMSiglaCompFuncional) )? $composicaoFuncional->NMSiglaCompFuncional : '';
    $empresa_id         = ( isset($composicaoFuncional->IDEmpresa) )? $composicaoFuncional->IDEmpresa : '';
    $status             = ( isset($composicaoFuncional->st_delete) )? $composicaoFuncional->st_delete : '';
    $urlRetorno = '';
?>

<form id="form_cadastro_composicaofuncional" class="">
    
    <input type="hidden" value="<?=$compfuncional_id;?>" name="ID" id="ID"/>
    <input type="hidden" value="<?=$empresa_id;?>" name="empresa_id"/>
    <input type="hidden" value="<?=$urlRetorno;?>" name="urlRetorno" id="urlRetorno"/>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group require input-text">
                <label for="sigla">Sigla</label>
                <input type="text" class="form-control" name="sigla" placeholder="SIGLA" value="<?=$sigla?>"/>
                <label class="control-label mensagem_erro" for="sigla" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group require input-text">
                <label for="nome">Nome do projeto</label>
                <input type="text" class="form-control" name="nome" placeholder="Nome do projeto" value="<?=$nome?>"/>
                <label class="control-label mensagem_erro" for="nome" style="display:none">Preencha o campo!</label> 
            </div>
        </div>
    </div>

    <br><br><br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary bt_salvar_cadastro">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="display: none;"></i>Salvar
            </button>
            <button type="button" class="btn btn-danger bt_excluir_cadastro" style="display:none">
                <i class="fa fa-trash-o"></i>Excluir
            </button>
        </div>
    </div>

    
</form>