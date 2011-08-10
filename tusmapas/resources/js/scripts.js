
function KeywordSuggestions() {
}

KeywordSuggestions.prototype.lookFromKeyword = function(keyword){
	var keywordsArray = new Array();
	
	var length = keyword.length;
	if(length < 2){
		return keywordsArray;
	}
	jQuery.ajax({
		   type: "GET",
		   url: "http://localhost/tusmapas/php_scripts/keywords-search.php",
		   data: ("keywords=" +  keyword),
		   success: function(msg){
			   if(msg.toString() == "\"{}\""){
				   return keywordsArray;
			   }	   

				var jsonResponseArray = eval(msg);
				var length = jsonResponseArray.length;
				for(i=0; i < length; i++ ){
					keywordsArray.push(jsonResponseArray[i].text);	
				}
		   }
		 });
		return keywordsArray;
};

KeywordSuggestions.prototype.requestSuggestions = function (oAutoSuggestControl ,
                                                          bTypeAhead /*:boolean*/) {
    var aSuggestions = [];
    var sTextboxValue = oAutoSuggestControl.textbox.value;
    
    if (sTextboxValue.length > 0){
    
        var keywords = this.lookFromKeyword(sTextboxValue);
        for (var i=0; i < keywords.length; i++) { 
//            if (keywords[i].indexOf(sTextboxValue) == 0) {
//                aSuggestions.push(keywords[i]);
//            } 
        	 aSuggestions.push(keywords[i]);
        }
    }

    //provide suggestions to the control
    oAutoSuggestControl.autosuggest(aSuggestions, bTypeAhead);
};
