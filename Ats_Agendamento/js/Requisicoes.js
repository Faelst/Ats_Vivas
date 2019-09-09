
   var PopularTecnico=[];

   $.ajax({
    type: 'GET',		    //Definimos o método HTTP usado
    dataType: 'json',	            //Definimos o tipo de retorno
    url: "./php/PopularTecnico.php",    //Definindo o arquivo onde serão buscados os dados
    success: function (dados) {
        for (var i = 0; dados.length > i; i++) {
            //Adicionando registros retornados na tabela
           PopularTecnico[i] = dados[i].nome_tecnico;
            
          }
    },
    error: function (request, status, error) {
        alert(request.responseText);
    }
});


    $('#nomeTenico').empty();
    $('#nomeTenico').autocomplete({
      source: PopularTecnico,
      minLength: 3
    });

