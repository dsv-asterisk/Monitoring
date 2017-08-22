$(document).ready(function(){
    $("#results").load("devices.php", function(response, status, xhr) {
        if (status== "error") {          
            $("#results").html( '<div class="container  alerta-noscript"><div class="alert alert-success" role="alert"><h4 class="alert-heading">Atenção!</h4><hr>' + xhr.status + '<p class="mb-0">'+ xhr.statusText +'.</p></div></div>');
        }
    })
    // Configurando para refresh:
    setInterval(function(){
        $("#results").load("devices.php", function(response, status, xhr) {
            if (status== "error") {          
                $("#results").html( '<div class="container  alerta-noscript"><div class="alert alert-success" role="alert"><h4 class="alert-heading">Atenção!</h4><hr>' + xhr.status + '<p class="mb-0">'+ xhr.statusText +'.</p></div></div>');
            }
        })
    }, 10000);
})

function executar_funcao(modem,comando){
    var modem_nome = modem;
    var modem_comando = comando;
    $.post("funcoes.php",{ modem: modem_nome, comando: modem_comando});

    switch (comando) {
        case "reiniciar_modem":
            swal(modem_nome, "Modem Reiniciado com sucesso.", "success")        
            break;

        case "operar_gsm":
            swal(modem_nome, "Aplicado em 2G com sucesso.", "success")        
            break;    

        case "operar_cdma":
            swal(modem_nome, "Aplicado em 3G com sucesso.", "success")        
            break;            

        case "reset_modem":
            swal(modem_nome, "Modem Resetado com sucesso.", "success")
            break;

        case "reiniciar_tempo":
            swal(modem_nome, "Tempo reiniciado com sucesso.", "success")        
            break;            
    }

    
}