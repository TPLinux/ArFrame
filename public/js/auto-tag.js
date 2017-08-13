function AutoTAG(AutoTAGObject){

	 AutoTAGObject.classOFSpan = AutoTAGObject.classOFSpan || "tag-auto-style";
	 var existsMsg = AutoTAGObject.existsMsg || "Tag Exists!!";
	 var aTag = null;
	 var allTags = JSON.parse(localStorage.getItem(AutoTAGObject.tagsArrayName)) || [];
	 var defaultTags = AutoTAGObject.defaultTags || [];

	 function isInArray(value, array) {
	     return array.indexOf(value) > -1;
	 }

	 if(AutoTAGObject.loadSavedTags === true){
	     var allTags = JSON.parse(localStorage.getItem(AutoTAGObject.tagsArrayName)) || [];
	     for(var x = 0; x < allTags.length ; x++){
		 allTags[x] = allTags[x].toString();
		 var TagExists = isInArray(allTags[x], defaultTags);
		 if(!TagExists)
		     defaultTags.push(allTags[x]);
		 }
	 }
	 
	 for(var i = 0; i < defaultTags.length; i++){
	     defaultTags[i] = defaultTags[i].toString();
	     var TagExists = isInArray(defaultTags[i], JSON.parse(localStorage.getItem(AutoTAGObject.tagsArrayName)) || []);
	     if(!TagExists){
		 allTags.push("" + defaultTags[i]);
		 localStorage.setItem(AutoTAGObject.tagsArrayName, JSON.stringify(allTags));
	     }
	     setTimeout(setUpRmAT, 5);
	 }

	 for(var i = 0; i < allTags.length; i++){
	    
	     $("#" + AutoTAGObject.divID).append("<span class='" + AutoTAGObject.classOFSpan + "' data-tag='" + allTags[i] + "'>" + allTags[i] + "</span> ");
	 }
	 
	 $("#" + AutoTAGObject.inputID).on('keypress', function(e){
	     var E = e = e || window.event;
	     // console.log(E.keyCode);
	     allTags = JSON.parse(localStorage.getItem(AutoTAGObject.tagsArrayName)) || [];
	     if(E.keyCode == 44 || E.keyCode == 13){

		 aTag = $(this).val();
		 
		 if(aTag != null && aTag != "" && aTag.trim().length != 0 && aTag.indexOf(',')){
		     aTag = aTag.toString();
		     var TagExists = isInArray(aTag, allTags);
		     if(!TagExists){
			 E.preventDefault();
			 $("#" + AutoTAGObject.divID).append("<span class='" + AutoTAGObject.classOFSpan + "' data-tag='" + aTag + "'>" + aTag + "</span> ");
			 allTags.push(aTag);
			 localStorage.setItem(AutoTAGObject.tagsArrayName, JSON.stringify(allTags));
			 $(this).val("");
			 console.log(allTags);
			 setTimeout(setUpRmAT, 500);
		     }else{
			 alert(existsMsg);
		     }
		 }
	     }
	 });

	 function setUpRmAT(){
	     $("." + AutoTAGObject.classOFSpan).click(function(){
		 $(this).remove();
		 var allTags = JSON.parse(localStorage.getItem(AutoTAGObject.tagsArrayName)) || [];
		 var index = allTags.indexOf($(this).text());
		 if (index > -1) {
		     allTags.splice(index, 1);
		 }
		 localStorage.setItem(AutoTAGObject.tagsArrayName, JSON.stringify(allTags));
	     });
	 }
     }
