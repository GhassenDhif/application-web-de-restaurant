
document.getElementById("recherche-produit").addEventListener("keyup", function (){
    $.ajax({

        url:
            'http://127.0.0.1:8000/produit/produit-search/'+$("#recherche-produit").val(),
        type: "GET",
        success: function (data) {
            $("#produit-div").empty();
            $("#produit-div").append(data);
        },

        // Error handling
        error: function(xhr, textStatus, error) {
            console.log(xhr.responseText);
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        }
    });
});