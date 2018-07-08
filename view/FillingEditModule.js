  // na klik botuna IkonaEdit - > pokrenuti ovu skriptu
  //prilikom uredivanja odredene transakcije
    //ova skripta dohvaća potrebne vrijednosti za ispunjavanje inputa
  $( document ).ready( function(){
    $(".IconButtonE").on( "click", function(){
        var id = $(this).val();
        var type = $(this).attr("name");

        // dohvatiti podatke preko ajaxa iz baze - na osnovu id-a + flag?
        $.ajax(
          {
          url: window.location.pathname+"?rt=transactions/getTransactionById",
          data:
          {
            id: id,
            type : type
          },
          dataType: "json",
          error: function( xhr, status )
          {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
          },
          success: function( data )
          {
            $(" #name").val(data.name);
            $(" #amount").val(data.value);
            $(" #description").val(data.description);
            $(" #date").val(data.date);
            $(" #TranId").val(id);

            if( type == "e" || type == "expense" ){
              type = "Expense";
            }
            else if( type == "i" || type == "income" ){
              type = "Income";
            }
            var DataCategory = data.category;

            $.ajax(
              {
              url: window.location.pathname+"?rt=category/CategoryForSelect",
              data:
              {
                tip : type
              },
              dataType: "json",
              error: function( xhr, status )
              {
                if( status !== null )
                    console.log( "Greška prilikom Ajax poziva: " + status );
              },
              success: function( data_cat )
              {
                var selekt = $(" #category");
                selekt.html("");
                for ( var i = 0; i < data_cat.length ; ++i){
                  if ( data_cat[i] == DataCategory )
                    selekt.append('<option selected="selected">'+ data_cat[i] +'</option>');
                  else
                    selekt.append('<option>'+ data_cat[i] +'</option>');
                }
                $(" #type").val(type);
              }
          });
          }
        });
    });
  });
