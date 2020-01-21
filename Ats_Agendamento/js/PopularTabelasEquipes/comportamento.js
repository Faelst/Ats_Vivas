var fk_usuario;
var nome_agente;

$(document).ready(function () {

    tabelasAgentes();

    var table = $('#TabelaAgentes').DataTable();

    $('#TabelaAgentes tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('#TabelaTecnico').DataTable().clear().draw();
            $("#addEquipe").attr("disabled", true);
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            fk_usuario = $(this).find("td:first").text()
            nome_agente = $(this).find("td:nth-child(2)").text()
            poputarTecnico(fk_usuario);
            $("#addEquipe").attr("disabled", false);
        }
    });
})

$("#btnAdd").click(function () {

    verificarTecnico($('#nomeTenicoAdicional').val())
})

var result;
function verificarTecnico(nomeTecnico) {

    $.get('./php/PopularTecnico.php?flag=3&nomeTecnico=' + nomeTecnico)
        .done(function (data) {
            parseInt(data)
            if (parseInt(data) >= 1) {
                adicionarTecnico();
            } else {
                alert('Nenhum tecnico foi encontrado')
            }
        });

}

function adicionarTecnico() {
    $.get('./php/PopularTecnico.php?flag=4&nomeTecnico=' + $('#nomeTenicoAdicional').val() + '&nomeAgente=' + $('#nomeAgente').text())
        .done(function (data) {
            alert(data);
            poputarTecnico(fk_usuario);
            $('#exampleModalCenter').modal('hide');
        });

}



// JAVASCRIPT PARA CADASTRAR NOVO USUARIO APENAS NO SISTEMA AT'S
$('#btncadastrarUsuario').click(function () {

    if (verifiqueFormulario() == true) {
        cadastrarNovoUsuario();
        alert('engtrei no verifica')
    }
})

function verifiqueFormulario() {
    var flag = 0;

    if ($('#nomeComletoUsuario').val().length <= 4) {
        $('#nomeComletoUsuario').addClass('border-danger')
    } else {
        $('#nomeComletoUsuario').removeClass('border-danger')
        flag += 1;
    }

    if ($('#primeiroLogin').val().length <= 4) {
        $('#primeiroLogin').addClass('border-danger')
    } else {
        $('#primeiroLogin').removeClass('border-danger')
        flag += 1;
    }

    if ($('#segundoLogin').val().length <= 4) {
        $('#segundoLogin').addClass('border-danger')
    } else {
        $('#segundoLogin').removeClass('border-danger')
        flag += 1;
    }

    if ($('#senhaCadastro').val().length <= 4) {
        $('#senhaCadastro').addClass('border-danger')
    } else {
        $('#senhaCadastro').removeClass('border-danger')
        flag += 1;
    }
    

    if (flag == 4) {
        return true;
    } else {
        return false;
    }

}

function cadastrarNovoUsuario() {

    var nomeCompleto = $('#nomeComletoUsuario').val()
    var segundoNomeLogin = $('#primeiroLogin').val()
    var primeiroNomeLogin = $('#segundoLogin').val()
    var senha = $('#senhaCadastro').val()

    if ($('#checkboxAdmin').is(":checked")) {
        var checkboxAdmin = true;
    }else { 
        var checkboxAdmin = false;
    }

    $.get('./php/CadastrarUsuario.php?flag=1&nomeCompleto=' + nomeCompleto + '&segundoNomeLogin=' + segundoNomeLogin + '&primeiroNomeLogin=' + primeiroNomeLogin + '&senha=' + senha + '&checkboxAdmin=' + checkboxAdmin)
        .done(function (resp) {
            if((resp == 'true')||(resp == true)){
                alert("Usuario cadastrado com sucesso")
                limparCadastroUsuario();
            }else {
                alert(resp)
            }
        })
}

function limparCadastroUsuario() {
    $('#nomeComletoUsuario').val('')
    $('#primeiroLogin').val('')
    $('#segundoLogin').val('')
    $('#senhaCadastro').val('')
    $('#checkboxAdmin').prop("checked", false);
}

// JAVASCRIPT  PARA O MODAL DE CADASTRAR TECNICO

$('#linkCadastrarTecnico').click(function (e) {
    $('#exampleModalCenter').modal('hide');
    popularCidades();
})

$('#btnVoltar').mouseover(function (e) {
    $(this).removeClass('border-dark');
    $(this).removeClass('text-dark');
    $(this).addClass('border-warning');
    $(this).addClass('text-warning');
})
    .mouseout(function (e) {
        $(this).addClass('border-dark');
        $(this).addClass('text-dark');
        $(this).removeClass('border-warning');
        $(this).removeClass('text-warning');
    });

$('#btnVoltar').click(function (e) {
    e.preventDefault();
    $('#exampleModalCenter').modal('show');
    popularModalAddTecnico();
});

function popularCidades() {
    $.get('./php/PopularTecnico.php?flag=5')
        .done(function (resp) {
            resp = JSON.parse(resp);
            $('#selecionarCidade').empty();
            $('#selecionarCidade').append('<option selected>Selecionar Cidade</option>');
            $.each(resp, function (i, nomeCidade) {
                $('#selecionarCidade').append('<option value=' + resp[i].idCidade + '>' + nomeCidade.nomeCidade + '</option>');
            });
        })
}

$('#cadastrarTecnico').click(function (e) {
    cadastrarTecnico();
})

var cidade

function cadastrarTecnico() {
    var nomeTenico = $('#nomeTecnico').val();
    var cidade = $('#selecionarCidade').val()
    var desc = $('#texteDescricao').val()
    var flag = false;

    if (!nomeTenico.length) {
        $('#nomeTecnico').addClass('border-danger');
        flag = true;
    } else {
        $('#nomeTecnico').removeClass('border-danger');
        flag = false;
    }

    if (cidade == "Selecionar Cidade") {
        $('#selecionarCidade').addClass('border-danger');
        flag = true;
    } else {
        $('#selecionarCidade').removeClass('border-danger');
        flag = false;
    }


    $.get('./php/PopularTecnico.php?flag=6&nomeTecnico=' + nomeTenico + '&cidade=' + cidade + '&desc=' + desc)
        .done(function (resp) {
            if (resp == 1) {
                alert('Cadastrado com Sucesso');
                limparcampos();
            } else {
                alert(resp);
            }
        })
}



function limparcampos() {
    $('#nomeTecnico').val('');
    $('#selecionarCidade').val('Selecionar Cidade');
    $('#texteDescricao').val('');
}


//////////////////////////////////////////////////


$('#addEquipe').click(function () {

    $('#nomeAgente').text(nome_agente);

    popularModalAddTecnico();

})

function popularModalAddTecnico() {

    var PopularTecnico = [];

    $.ajax({
        type: 'GET',		    //Definimos o método HTTP usado
        dataType: 'json',	            //Definimos o tipo de retorno
        url: "./php/PopularTecnico.php?flag=2",    //Definindo o arquivo onde serão buscados os dados
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

    $('#nomeTenicoAdicional').empty();
    $('#nomeTenicoAdicional').autocomplete({
        source: PopularTecnico,
        minLength: 3
    });

}

var TabelaTecnico = $('#TabelaTecnico').DataTable({
    retrieve: true,
    "searching": false,
    "ordering": true,
    "paging": false,
    "ordering": false,
    "info": true,
    "language": {
        "url": "JsonTabela/Portuguese-Brasil.json"
    },
    ajax: {
        url: './php/PopularTables.php?flag=5&id_usuario=100',
        dataSrc: ''
    },
    columns: [
        { 'data': 'id_tecnico' },
        { 'data': 'nome_tecnico' },
        { "defaultContent": "<div class='d-flex justify-content-end'><button class='btn-danger rounded'>Remover</button></div>" }
    ]
});

$('#TabelaTecnico tbody').on('click', 'button', function () {

    var data = TabelaTecnico
        .column(0)
        .row($(this).parents('tr'))
        .data();

    if (confirm('Deseja remover da Equipe ?')) {
        TabelaTecnico
            .row($(this).parents('tr'))
            .remove()
            .draw();

        btnRemover(data.id_tecnico);

    }


    // quando clicar ele executa uma função enviando como parametro o ID do tecnico para remover  

})

function btnRemover(data) {

    $.get('./php/PopularTables.php?flag=6&fk_tecnico=' + data + '&fk_usuario=' + fk_usuario)
        .done(function (data) {
            alert(data);
        });
}

function poputarTecnico(data) {
    TabelaTecnico.ajax.url('./php/PopularTables.php?flag=5&id_usuario=' + data).load();
}




function tabelasAgentes() {

    var table = $('#TabelaAgentes').DataTable({
        retrieve: true,
        "ordering": true,
        "language": {
            "url": "JsonTabela/Portuguese-Brasil.json",
        },
        ajax: {
            url: './php/PopularTables.php?flag=4',
            dataSrc: ''
        },
        columns: [
            { 'data': 'id_usuario' },
            { 'data': 'nome_usuario' }
        ]
    });




}