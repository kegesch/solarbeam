function makeYearlyTable() {
  var jsonLieferung, jsonBezug;
  var tablediv = document.getElementById("yearlytable");
  
  getJSONFromUrl("api.php?q=yearly", function(json) {
    jsonLieferung = json;
    getJSONFromUrl("api.php?q=yearlybezug", function(json) {
      jsonBezug = json;
      tablediv.className += " table-responsive";
      var content = "<table class=\"table\"><tr><th>Jahr</th>";
      var length = 0;

      for(year in jsonBezug.labels) {
        length++;
        content += "<td>"+jsonBezug.labels[year]+"</td>";
      }
      
      content += "</tr><tr><th>Bezug</th>";

      for(v in jsonBezug.series) {
        content += "<td>"+jsonBezug.series[v]+"</td>";
      }

      content += "</tr><tr><th>Lieferung</th>";
  
      var lengthL = 0;
      for(c in jsonLieferung.series) {lengthL++;}
      for(v in jsonBezug.series) {
        if(v<(length-lengthL)) content += "<td>n/A</td>";
        else content += "<td>"+jsonLieferung.series[v-(length-lengthL)]+"</td>";
      }

      content += "</tr></table>";

      tablediv.innerHTML = content;
    });
  });
  
 }
