<script>
function importaUsuario(){
    var xhttp = new XMLHttpRequest(); 
    }
</script>   

<div class="modal fade" id="modal_importa_usuario" tabindex="-1" role="dialog" aria-labelledby="ModalImportarUsuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Importar usuario

                </h4>
            </div>
            <form method="POST" action="processa.php" enctype="multipart/form-data">
                <div class="modal-body">    	
                    <label>Planilha com cabeçalho</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                           Sim
                        </label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                           Não
                        </label>

                    </div>
                </div>
                <div class="modal-body">    	
                    <label>Arquivo</label>
                    <input type="file" name="arquivo"><br><br>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary bt_enviar" id="butinvia">Enviar</button>
                </div>
            </form> 
        </div>
    </div>
</div>
