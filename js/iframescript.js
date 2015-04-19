/**********
Sets iFrame to be the height of the content. Note that webkit and mozilla report height differntly on the body element. Works better when set to the HTML element.

**********/

window.onload = function() {
  var iFrameID = document.getElementById('idIframe');
  if(iFrameID) {
	  	var htmlHeight = iFrameID.contentWindow.document.getElementsByTagName('html');
		var pageHeight = htmlHeight[0].scrollHeight + "px";
		iFrameID.height = "";
		iFrameID.height = pageHeight;
  }   
}