<cfdirectory action="list" directory="#expandPath("./")#" recurse="false" name="myList">

<cfoutput query="">
    <li>#name#</li>
</cfoutput>