# cfphp
cfphp - coldfusion cfml to php parser.

This is a first alpha attempt to code a cfml-to-php parser.

(Considering my limited PHP coding skills) The goal of this project is to make an 80% correct cfml->php parser, which should require limited php manual editing before a final php template is exported.

When a final php template is stored, every time a cfml (*.cfml, *.cfm, *.cfc) page is called, the final php template (with the same cfml file name) is executed as an include file on top the cfml code in the cfml file, while the cfml code is further ignored by a die() command.

So basically, if you browse to "whatever.cfm" on the server, "whatever_final.php" gets executed instead as an include, but "whatever.cfm" remains the page-address. 

When the project is finished, with this setup, virtually you will have a mixed Coldfusion-PHP server, with added posibilites of custom CF and other HTML-style tags.

Unlike the "Smarty" engine, with cfphp you can eventually use functional tags straight inside your PHP code if this project succeeds to that point. No need for seperate tlp templates. If cfphp can translate a CFML file, it should also be able to tanslate a PHP file with tags inside.

The .htaccess file will add .cfm, .cfml, .cfc as PHP template types.

The big trick of this project is that [php_value auto_prepend_file "./Application.php"] in .htaccess will load the Application.php page before every other php, cfm, cfml and cfc template. And this Application.php contains the selection mechanisme of how to process a cfml file. PHP files are passed through without altering the php code, but you can use Application.php for php files as well.



Parsing cftags and arguments don't seem to be a the greatest problem. However, seperating function-names from variables, and especially nested #function(#variable#)# pound-signs is a big challenge, and will likely require hand-editing. 

I would therefore advise everybody testing this code to start using php-dollar-$variables in CFML pages ... instead of pound-#variables# to avoid parsing headaches.

For a strange reason I don't seem to be able to detect "<cf" tags with a capital letter in C or F or both: <CFxxx <Cfxxx <cFxxx or </CFxxx etc. 

For the moment, only use small-letter cf-tags and /cf-endtags to test, until this is debugged.

The cfscripts (code blocks) are copied over from CFML to PHP, without altering. Please use or edit to php-script inside your <cfscript></cfscript> tags. 

Eventually, most of the cffunctions will be translated with cffunction-names, and you will be able to use cffunctions in your php code as well.


Why this project?

* It will make easier for CFML coders to switch to PHP.
* PHP hosting is cheaper and easier to obtain than CFML hosting. All current Coldfusion engines seems to require a Java layer.
* In my experience, PHP servers are faster than (shared) CFML servers. Java based script can get memory issues, requiring server reboots.
* CFML offers a bit more higher level programming with functionality inside HTML-like CF-tags.
* Less CFML code than PHP == less debugging. Hopefully better PHP programmers will pick up the project to make it 100% CFML compatible.
* I know about Smarty temples. It's a great project, I have been playing with it for some months, but I realy mis cfml. 
--- I hope that php coders will start to appreciate cftags coding, and use this project to make their own tag to extend PHP directly inside PHP code (later on when this project develops).
* [ It's my personal killing some Corona-time while waiting for new bioinformatics data to come in later this year. ]

I have 20 years experience with CFML, and about 6 months with PHP. If you want to improve my PHP coding, please join and make sure you teach me :) 

This project is making use of the Ace online code-editor to debug CFML->PHP code before saving the final PHP file. Please copy the Ace src folder to your cfphp folder as a subdirectory, and rename the "src" folder to "AceEditor".

https://ace.c9.io/  and https://github.com/ajaxorg/ace-builds/tree/2ea299a2bee97fdf58fc90cb76eec6c45535a63f
 

