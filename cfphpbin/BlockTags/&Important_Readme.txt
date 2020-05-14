Hello ...

I think we can all agree that <cfset x=5> is a single line tag.

It doesn't need an end-tag to capture and use the innerCFML lines to make it functional.

On the other hand, cfquery needs the innerCFML to obtain the SQL lines, and is translated 
not when the start-tag is detected, but when the end-tag is found.

The code for cfquery therefore should be stored in the BlockTags folder and not in the SingleLineTags folder.

<cfquery name=qryTest dbtype=query>
	SELECT whatever FROM table
</cfquery> << Processed and translated here after the SQL-line is captured.

I think thus far we can all agree on the logic.

What makes a cfif tag different (as one of many examples) from a cfquery tag? ...

At first you will probably think that cfif is a BlockTag as well.

<cfif x IS 20>
	Print #x#
</cfif>

The difference is that cfif absolutely doesn't need the innerCFML lines to function. "It" doesn't care what you put inside.

Therefore, the cfif gets translated to PHP seperately from the Print #x# line as two different events.

So, cfif, cfelseif and cfelse are all SingleLineTags, and the code for them should be placed in that folder.

The file name for that code is CFif_CFelseif_CFelse.php. cfPHP needs the name of every tag in a file to know which tags are coded and can be used for translation.

This is very important.

Please don't mixup SingleLineTags and BlockTags in a single file, eventhough it makes sense for groupings.

Because you might end up with tags being skipped.

So, the important part of this story ... only use BlockTags if you need the innerCFML lines for the translation.

They might be important for the execution by PHP in the final script, but that's a different matter.

The SQL line in cfquery is used to setup a SQlite DB, table, then selecting and sorting, etc, this requires a lot of extra PHP code.

For this the SQL line is absolutely necessary.

At the time of this writing I made the mistake to add cfoutput and cfloop as BlockTags. That's probably a mistake.

I hope this makes sense?

- Marco.

