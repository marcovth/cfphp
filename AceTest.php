<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Editor</title>
  <style type="text/css" media="screen">
	#editor1 {
        margin: 0;
        //position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }

    #editor2 {
        margin: 0;
        //position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
  </style>
</head>
<body>
<table width="100%"><tr>
<td id="editor1" width="50%">function foo(items) {
    var i;
    for (i = 0; i &lt; items.length; i++) {
        alert("Ace Rocks " + items[i]);
    }
}</td>
<td id="editor2" width="50%">
	Hello World
}</td>
</table>
<script src="AceEditor/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor1 = ace.edit("editor1");
    editor1.setTheme("ace/theme/twilight");
    editor1.session.setMode("ace/mode/javascript");
	
	var editor2 = ace.edit("editor2");
    editor2.setTheme("ace/theme/twilight");
    editor2.session.setMode("ace/mode/javascript");
</script>

</body>
</html>
