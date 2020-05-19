<cfset testArr=ListToArray('"a","b","c","d"')>

<cfset TestStruct=StructNew()>
<cfset TestStruct.name="Apple corp">
    
<cfset #TestStruct2#=StructNew()>
<cfset TestStruct2.name.last.offical="Google corp">

<li>#TestStruct2.name.last.offical#</li>