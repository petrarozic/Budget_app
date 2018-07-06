$( document ).ready( function()
  {
      $("th").on('click', sortTable );
  } );

function sortTable(event) {
  //console.log("Sortiraj prema: " , event.currentTarget.innerText);
  var sortIndex = event.currentTarget.cellIndex;
  //console.log("Celija = " , sortIndex);

  var brRedaka = $('table tbody').children().length;
  //console.log("Br. podataka = ", brRedaka);

  //IDEJA --> izvucem svu dijecu u listu -- sortiram tu listu
  var listChildren = [];
  for (var i = 0; i < brRedaka; i++) {
    listChildren.push($('table tbody').children().eq(i));
  }

  //SORT ALGORITHM
  var swap = false;
  for (var i = 0; i < Number(brRedaka)-1; i++) {
    for (var j = 0; j < Number(brRedaka)-1 - i; j++) {
      var dijete_j = listChildren[j];
      var value_j = dijete_j.children().eq(sortIndex).html();
      var dijete_next_j = listChildren[j+1];
      var value_next_j = dijete_next_j.children().eq(Number(sortIndex)).html();
      //console.log("VRIJEDNOSTI: j = ", value_j, "   next_j = ", value_next_j);

      //ako sortiramo po AMOUNT onda sortiramo Number (ne String)
      if (event.currentTarget.innerText == "AMOUNT (HRK)") {
        value_j= Number(value_j);
        value_next_j = Number (value_next_j);
      }

      if (value_j > value_next_j ) {
        //ZAMJENA
        swap = true;
        var temp = listChildren[j];
        listChildren[j] = listChildren[j+1];
        listChildren[j+1] = temp;
      }
    }
  }
  //end SORT ALGORITHM

  //isprazni tijelo tablice
  $('table tbody').children().remove();

  //dodaj dijecu u tijelo tablice
  //ako nije bilo promjene(swap==false) onda vjv trazi suprotan sort (ASC/DECS)
  if (swap){
    for (var i = 0; i < brRedaka; i++)
      $('table tbody').append(listChildren[i])
  } else {
    for (var i = brRedaka -1 ; i >= 0; i--)
      $('table tbody').append(listChildren[i])
  }
  //dijeca dodana u tijelo tablice
}
