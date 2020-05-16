<cfdirectory action="list" directory="#expandPath("../")#" recurse="false" name="myList">

<cfoutput query="myList">
    <li>#name# | #size# | #type# | </li>
</cfoutput>
<li>#myList_Recordcount#</li>

<cfquery name="myList2" dbtype="query">SELECT * FROM myList WHERE type='Dir'</cfquery>
<hr>

<cfoutput query="myList2">
    <li>#name# | #size# | #type# | </li>
</cfoutput>