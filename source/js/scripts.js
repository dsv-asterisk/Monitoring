$(document).ready(function(){
    $("#results").load("devices.php", function(response, status, xhr) {
        if (status== "error") {          
            $("#results").html( '<div class="container  alerta-noscript"><div class="alert alert-success" role="alert"><h4 class="alert-heading">Atenção!</h4><hr>' + xhr.status + '<p class="mb-0">'+ xhr.statusText +'.</p></div></div>');
        }
    });

setInterval(function(){
    $("#results").load("devices.php", function(response, status, xhr) {
        if (status== "error") {          
            $("#results").html( '<div class="container  alerta-noscript"><div class="alert alert-success" role="alert"><h4 class="alert-heading">Atenção!</h4><hr>' + xhr.status + '<p class="mb-0">'+ xhr.statusText +'.</p></div></div>');
        }
    })
}, 10000);
});