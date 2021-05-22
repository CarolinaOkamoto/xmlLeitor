<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    /*** Script para atualizar a tabela conforme pesquisa no input ***/
    $(document).ready(function() {
        $("#inputSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#resultado tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

<!-- Formulário para upload do arquivo -->
<form class="lista-xml mt-5" name="lista-xml" id="formGravar" method="post" ENCTYPE="multipart/form-data">
    <div class='form-group col-sm-12 px-0'>
        <label class="w-100">Upload de Arquivo</label>
        <div class="custom-file">
            <input type="file" class="custom-file-input custom-file-input-arquivo" id="arquivo" name="arquivo">
            <label class="custom-file-label custom-file-label-arquivo" for="arquivo" data-browse="Procurar">Selecione o arquivo <small>(.xml)</small></label>
        </div>
    </div>
    <!-- Ícone de carregar -->
    <div id="resposta"></div>
    <button id='btnSubmit' class='btn btn-primary mt-2'>ENVIAR</button>
</form>

<!-- Input para pesquisar, aparece quando finaliza a requisição -->
<div class='form-group col-sm-12 px-0'>
    <div class="custom-file">
        <label id="labelSearch" style="display:none; left:right;"></label>
        <input type="text" id="inputSearch" class="custom-file-label input-search" style="display:none; left:auto;" alt="lista-xml" placeholder="Buscar nesta lista" />
    </div>
</div>

<!-- Tabela será carregada aqui -->
<div id="tabela"></div>
