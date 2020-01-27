$('a').click(function(e){
    e.preventDefault(); 
    $(this).attr('href')
/*    fetch('paginas/FormularioCadastro.html')
        .then( resp => resp.text())
        .then( html => $('#conteudo').html(html));*/
        
e.preventDefault();   
$.get($(this).attr('href'), function(data, status){
    $('#conteudo').html(data);
  });

  $('#btnCadastrar').click(function(){
    $.get($(this).attr('href'), function(data, status){
      $('#conteudo').html(data);
    });
  });
        
});
