# cfphp
cfphp - coldfusion cfml to php parser.

This is a first alpha attempt to code a cfml-to-php parser.

The goal is to make an automatic parser, which (when you call a cfm/cfc file on a php server) will translate it into php, and store the translated script into a php file with the same file name for display.  

It would be nice if we can make 80% compatible, before debugging and finishing a php template by hand.

Parsing cftags and arguments doesn't seem to be a the greatest problem. However, parsing variables, especially with nested #function(#variable#)# pound-signs will be a big challenge (if indeed possible?). I would therefore advise everybody testing this code to start using php-dollar-$variables in the CFML pages ... instead of pound-#variables# to avoid parsing headaches.

For a strange reason, that I haven't figured out yet, I don't seem to be able to detect "<cf" tags with a capital letter in C or F or both: <CFxxx <Cfxxx <cFxxx or </CFxxx etc. 

For the moment, only use small-letter cf-tags and /cf-endtags to test.

The cfscripts (code blocks) are copied over from CFML to PHP, without altering. Please use php-script inside your <cfscript></cfscript> tags. 

Eventually, most of the cffunctions will be translated with cffunction-names, so you will be able to use cffunctions in your (cfscript) cfml code.

Will see if this project sinks. I think many cfml coders who (are forced to) switch to php will find the cffunctions library very useful.

I know about Smarty temples. I have been playing with it for some months, but I realy mis cfml. On the other hand, I hope that php coders will start to appreciate cftags coding, and use this project to make their own.

I have 20 years experience with CFML, and about 6 months with PHP. If you can find improvements for my PHP coding, please make sure you teach me :) 

The .htaccess file will add .cfm, .cfml, .cfc as PHP template types.
And, 




