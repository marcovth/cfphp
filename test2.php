
  <style type="text/css" media="screen">
	.ace_editor {
		border: 1px solid lightgray;
		margin: auto;
		height: 600px;
		width: 50%;
	}
	.scrollmargin {
		height: 80px;
        text-align: center;
	}
    </style>

<form>
<table width="100%">
	
	<tr>
		
		<td id="editor1" width="50%"><textarea name="x1">function foo(items) {
    var i;
    for (i = 0; i &lt; items.length; i++) {
        alert("Ace Rocks " + items[i]);
    }
}</textarea></td>
<td id="editor2" width="50%"><textarea name="x2">
	Hello World
</textarea></td>
		
	</tr>

</table>
</form>


<script src="AceEditor/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
	
	var editor1 = ace.edit("editor1");
    editor1.setTheme("ace/theme/tomorrow");
    editor1.session.setMode("ace/mode/html");
    editor1.setAutoScrollEditorIntoView(true);
    //editor1.setOption("maxLines", 100);
	document.getElementById('editor1').style.fontSize='16px';

	
	var editor2 = ace.edit("editor2");
    editor2.setTheme("ace/theme/tomorrow");
    editor2.session.setMode("ace/mode/php");
    editor2.setAutoScrollEditorIntoView(true);
    //editor2.setOption("maxLines", 100);
	document.getElementById('editor2').style.fontSize='16px';

	
</script>

