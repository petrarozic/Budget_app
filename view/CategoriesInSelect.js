//prilikom odabira tipa transakcije mijenjaju se kategorije u select dijelu
$( document ).ready( function(){
      $("#CateType").on("change", function(){
        var tip = $(this).val();
        console.log("tip je :" +tip );
        $.ajax(
          {
          // Promjena transactions -> category
          url: window.location.pathname+"?rt=category/CategoryForSelect",
          data:
          {
            tip : tip
          },
          dataType: "json",
          error: function( xhr, status )
          {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
          },
          success: function( data )
          {
            var selekt = $("#category");

            selekt.html("");
            for ( var i = 0; i < data.length ; ++i){
              selekt.append('<option>'+ data[i] +'</option>');
            }
          }
        });
      });
    });
