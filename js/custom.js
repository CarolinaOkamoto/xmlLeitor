/*** Função para enviar o arquivo via ajax para gravar.php ***/
$('#formGravar').submit(function (event) {
    event.preventDefault();
    var formData = new FormData($('#formGravar')[0]);

    $.ajax({
        url: 'inc/gravar.php',
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,

        //Ícone de carregar antes de enviar a requisição
        beforeSend: function () {
            $('#resposta').html('<div class="spinner-border text-primary mx-auto mb-3" role="status"><span class="sr-only">Carregando...</span></div>');
        }
    })
    .done(function (resp) {
        //Limpar ícone de carregar
        $('#resposta').empty();
        var obj = JSON.parse(resp);

        //Caso arquivo não seja .xml
        if(obj.mensagem){
            alert(obj.mensagem);
        }else{
            //Cria tabela
            $('#tabela').html('<div class="table-responsive"><table name="resultado" id="resultado" class="table table-striped table-bordered"><thead><tr><th scope="col" style="width:50%">Caminho<i class="bi bi-arrow-down-up pl-1"></i></th><th scope="col" style="width:50%">Valor<i class="bi bi-arrow-down-up pl-1"></i></th></tr></thead><tbody><tr></tr></tbody></table></div>');
            var table = document.getElementById("resultado");
            //i=1 para posicionar embaixo do cabeçalho da tabela
            i=1;
            //Enquanto houver objetos a serem inseridos na tabela
            while(i<Object.keys(obj).length+1){
                $('#labelSearch').html('<h4 class="mt-4">'+Object.keys(obj).length+' linhas</h4>');
                row = table.insertRow(i);
                cell1 = row.insertCell(0);
                cell2 = row.insertCell(1);
                cell1.innerHTML = obj[i-1].caminho;
                cell2.innerHTML = obj[i-1].valor;
                i++;
            }
            //Mostrar input de busca e contagem de linhas
            document.getElementById("labelSearch").style.display = "block"; 
            document.getElementById("inputSearch").style.display = "block";   
        }     
    })
    .fail(function (jqXHR, textStatus) {
        $('#resposta').html('<p class="alert alert-danger text-danger">Ocorreu um erro, tente novamente mais tarde</p>');
    });
});

/*** Função para colocar o nome do arquivo na label ***/
$('.custom-file-input-arquivo').change(function(e) {
    var fileName = e.target.files[0].name;
    $('.custom-file-label-arquivo').html(fileName);
});
