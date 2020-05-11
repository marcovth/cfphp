-
<cfset news = queryNew("id,title", "integer,varchar")>
<cfset queryAddRow(news)>
<cfset querySetCell(news, "id", "1")>
<cfset querySetCell(news, "title", "Dewey defeats Truman")>
<cfset queryAddRow(news)>
<cfset querySetCell(news, "id", "2")>
<cfset querySetCell(news, "title", "Men walk on Moon")>

<cfquery name="sortedNews" dbtype="query">
    SELECT id, title FROM news
    ORDER BY title DESC
</cfquery>