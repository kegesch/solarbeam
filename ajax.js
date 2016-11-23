function getJSONFromUrl(url, callback) {
	/**xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
				callback(JSON.parse(xmlhttp.responseText));
		}
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.send();**/
	$.ajax({
		url: url
	}).success(function(result) {
		callback(JSON.parse(result));
	})
}