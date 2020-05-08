# cfphp
cfphp - coldfusion cfml to php parser.

This is a first alpha attempt to code a cfml-to-php parser.

(Considering my limited PHP coding skills) The goal of this project is to make an 80% correct cfml->php parser, which should require limited php manual editing before a final php template is exported.

When a final php template is stored, every time a cfml (*.cfml, *.cfm, *.cfc) page is called, the final php template (with the same cfml file name) is executed as an include file on top the cfml code in the cfml file, while the cfml code is further ignored by a die() command.

So basically, if you browse to "whatever.cfm" on the server, "whatever_final.php" gets executed instead as an include, but "whatever.cfm" remains the page-address. 

When the project is finished, with this setup, virtually you will have a mixed Coldfusion-PHP server, with added posibilites of custom CF and other HTML-style tags.

Unlike the "Smarty" engine, with cfphp you can eventually use functional tags straight inside your PHP page, if this project succeeds to that point. No need for seperate smarty tpl templates. If cfphp can translate a CFML file, it should also be able to translate a PHP file with CF-tags inside.

BTW, I would encourage everybody visiting here to also try and use "Smarty". It has been in development for many years. https://www.smarty.net/


The .htaccess file of this project adds [.cfm, .cfml, .cfc] as PHP template types. cfphp will only work if you can add or edit the .htaccess file in your website.

The big trick of this project is the added [php_value auto_prepend_file "./Application.php"] line in .htaccess. This will load the Application.php page before every other PHP, cfm, cfml and cfc template. 

This Application.php file contains the selection mechanisme of how to process a cfml file. PHP files are passed through without altering the php code, but you can optionally use Application.php to prepend every php file with php code as well.


Parsing cftags and arguments don't seem to be a the greatest problem. However, separating function-names from variables, and especially nested #function(#variable#)# pound-signs is a big challenge, and will likely require hand-editing. 

I would therefore advise everybody testing this code to start using php-dollar-$variables in CFML pages ... instead of CFML-style pound-#variables# to avoid parsing headaches.

The cfscripts (code blocks) are copied over from CFML to PHP without alterations. Please use (or hand-edit to) php-script inside your cfscript-tags. 

Eventually, most of the cffunctions will be translated with cffunction-names, and you will be able to use cffunctions in your php code as well.

Coding/translating CF-Tags and cffunctions will be a great tasks, and will only happen if other coders will join this project. 


Why this project?

* It will make easier for CFML coders to switch to PHP.
* PHP hosting is cheaper and easier to obtain than CFML hosting. All current Coldfusion engines seem to require a Java layer/server. There a lot more php hosts than host companies offering Java.
* In my experience, PHP servers are faster than (shared) CFML servers. Java based scripts can get memory issues, requiring server reboots.
* CFML offers a bit more higher level programming with functionality inside HTML-like CF-tags.
* The is less CFML code than with PHP == less debugging. (Hopefully better PHP programmers will pick up this project to make it 100% CFML compatible.)
* I know about Smarty templates. It's a great project, I have been playing with it for some months, but I realy mis my cfml. 
--- I hope that php coders will start to appreciate cftags coding, and use this project to make their own tag to extend PHP directly inside PHP code (later on when this project develops).
* [ This project is my personal "killing some Corona-time" while waiting for new bioinformatics data to come in later this year. ]

I have 22 years experience with CFML, and about 6 months with PHP. If you want to improve my PHP coding, please join and make sure you teach me :) 

This project is making use of the Ace online code-editor to debug CFML->PHP code before saving the final PHP file. There is no need for an external CFML/PHP editor. 
You still need FTP to upload CFML files to your server, but each CFML file run in Admin mode gets a CFML & PHP online Ace-editor window to make changes and to debug. So, direct editing on the server. 

Please copy the Ace src folder to your cfphp folder as a subdirectory, and rename the "src" folder to "AceEditor".

https://ace.c9.io/  and https://github.com/ajaxorg/ace-builds/tree/2ea299a2bee97fdf58fc90cb76eec6c45535a63f
 

