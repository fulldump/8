function setFavicon(url) {
	docHead = document.getElementsByTagName("head")[0];
	
	var links = this.docHead.getElementsByTagName("link");
	for (var i=0; i<links.length; i++) {
		var link = links[i];
		if (link.type=="image/x-icon" && link.rel=="shortcut icon")
			docHead.removeChild(link);
	}

	var new_link = document.createElement("link");
	new_link.type = "image/x-icon";
	new_link.rel = "shortcut icon";
	new_link.href = url;	
	docHead.appendChild(new_link);

}