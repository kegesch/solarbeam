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

function makeYearTable(json) {
  var tablediv = document.getElementById("yeartable");
  tablediv.className += " table-responsive";
  
  var content = "<table class=\"table\"><tr><th>Tag</th>";
  for(month in json.labels) {
    content += "<th>"+json.labels[month]+"</th>";
  }
  content += "</tr>";
  console.log(json);
  var i,j;
  for(i = 1; i <= 31; i++) {
    content += "<tr><th>"+i+"</th>";
    
    for(j = 1; j <= json.labels.length; j++) {
      //console.log(json.series[""+j]);
      jsonmonth = json.series[""+j];
      value = jsonmonth[""+(i-1)];
      content += "<td>"+(value != null ? Math.round(value*100)/100 : "")+"</td>";
    }
    content += "</tr>";
  }

  content += "</table>";

  tablediv.innerHTML = content;

}

getJSONFromUrl("api.php?q=yearall&y="+ new Date().getFullYear(), makeYearTable);
