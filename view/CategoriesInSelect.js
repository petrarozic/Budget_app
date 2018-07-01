$( document ).ready( function()
    {
      $("#type").on("change", function(){
        var tip = $( this ).val();

        $.ajax(
          {
          url: window.location.pathname+"?rt=transactions/CategoryForSelect",
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
