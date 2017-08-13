# ArFrame

Tested ON : PHP == 5.6.23 , PHP == 7.1.1


You need to config file  config/db.php

then if you wont creat new controller 
run :

php arf CONTROLLER_NAME

Adding route from index.php

# Auto-tag.js

This is my personal lib and i developed it

to use it , you need to JQuery 
to style it , /public/css/auto-tag.css

# Auto-tag.js Usage

You need to link it by "SCRIPT" tag

Preview : https://youtu.be/EEzXBN35CBg

JS File : https://github.com/TPLinux/ArFrame/blob/master/public/js/auto-tag.js 

CSS FILE : https://github.com/TPLinux/ArFrame/blob/master/public/css/auto-tag.css


```js

AutoTAG({
	 inputID: "any", // input to type in it or textarea
	 divID: "tags", // tags will shown in this div
	 tagsArrayName: "new", // all tags will be saved in localStorage variable new to (send it to server if you need)
	 classOFSpan: "tag-auto-style", // class of tags to styling those tags (spans)
	 defaultTags: [1,2,3], // default tags forec use those tags
	 existsMsg: "Sorry, but this tag is alraady exists!", // if tag exists after press enter show alert wit this msg
	 loadSavedTags: true // if you need to load all saved tags and display it in the div above
     });
```

# Auto-tag Example

```HTML
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title>auto-tag.js ArFrame</title>
	<link href="auto-tag.css" rel="stylesheet"/>
    </head>
    <body>
	<input id="any" type="text" value=""/>
	<br/>
	<br/>
	<div id="tags"></div>
    </body>
    <script src="jq.js"></script>
    <script src="auto-tag.js"></script>
    <script>
     AutoTAG({
	 inputID: "any", // input to type in it or textarea
	 divID: "tags", // tags will shown in this div
	 tagsArrayName: "new", // all tags will be saved in localStorage variable new to (send it to server if you need)
	 classOFSpan: "tag-auto-style", // class of tags to styling those tags (spans)
	 defaultTags: [1,2,3], // default tags forec use those tags
	 existsMsg: "هذا المفتاح موجود بالفعل !, حاول بمفتاح جديد", // if tag exists after press enter show alert wit this msg
	 loadSavedTags: true // if you need to load all saved tags and display it in the div above
     });
    </script>
</html>


```
# Arabian developer
Twitter: https://twitter.com/i127001
