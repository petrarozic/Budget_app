
  // na klik botuna IkonaEdit - > pokrenuti ovu skriptu

  $( document ).ready( function()
  {
    $(".IconButtonEC").on( "click", function()
    {
        console.log("Edit");

        var name = $(this).val();
        var type = $(this).attr("name");

        $(".modal #type").val(type);
        $(".modal #name").val(name);
        $("#EditCategoryB").val(name);
      
    });
  });
