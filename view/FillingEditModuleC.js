  // na klik botuna IkonaEdit - > pokrenuti ovu skriptu
  //prilikom uredivanja odredene kategorije
    //ova skripta dohvaća potrebne vrijednosti za ispunjavanje inputa
  $( document ).ready( function(){
    $(".IconButtonEC").on( "click", function(){
        var name = $(this).val();
        var type = $(this).attr("name");

        $(".modal #type").val(type);
        $(".modal #name").val(name);
        $("#EditCategoryB").val(name);
      
    });
  });
