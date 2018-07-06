
  // na klik botuna IkonaEdit - > pokrenuti ovu skriptu

  $( document ).ready( function()
  {
    $(".IconButtonE").on( "click", function()
    {
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
            console.log(data.id+", "+data.category+", "+data.user_id+", "+data.name+", "+data.value+", "+data.date+", "+data.description);

            $("#EditTransaction #name").val(data.name);
            $("#EditTransaction #amount").val(data.value);
            $("#EditTransaction #description").val(data.description);
            $("#EditTransaction #date").val(data.date);
            $("#EditTransaction #TranId").val(id);

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
                var selekt = $("#EditTransaction #category");
                selekt.html("");
                for ( var i = 0; i < data_cat.length ; ++i){
                  console.log("JKaregorij: " + data_cat[i]);
                  if ( data_cat[i] == DataCategory )
                    selekt.append('<option selected="selected">'+ data_cat[i] +'</option>');
                  else
                    selekt.append('<option>'+ data_cat[i] +'</option>');
                }
                $("#EditTransaction #type").val(type);
              }
          });
          }
        });

        //  trebam znati je li income ili expense kako bi mogla pravu tablicu updatati
    });


  });
