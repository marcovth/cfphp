<cfquery name="qryNW" datasource="northwind">
    SELECT Products.ID AS pID, Product_Name, list_price FROM Products
    WHERE (((list_price)<20) AND ((Discontinued)=False))
    ORDER BY list_price DESC
</cfquery>

<cfoutput query="qryNW">
    <li>#Product_Name# [#list_price#] CurrentRow=#CurrentRow#</li>
</cfoutput>

<hr>

<cfset news = queryNew("id,title", "integer,varchar")>
<cfset queryAddRow(news)><cfset querySetCell(news, "id", "1")><cfset querySetCell(news, "title", "Dewey defeats Truman")>
<cfset queryAddRow(news)><cfset querySetCell(news, "id", "2")><cfset querySetCell(news, "title", "Men walk on Moon")>

<cfquery name="sortedNews" dbtype="query">SELECT id, title FROM news ORDER BY title DESC</cfquery>

<cfoutput query="sortedNews">
    <li>#id# [#title#] CurrentRow=#CurrentRow#</li>
</cfoutput>
<p>
RecordCount= #Recordcount#<br>
RecordCount= $sortedNews_Recordcount <br>