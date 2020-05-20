<cfset student=StructNew()>
<cfset student = {firstName="Jane", lastName="Janes", grades=[91, 78, 87]} >
    
<cfset #TestStruct2#=StructNew()>
<cfset TestStruct2.name.last.offical="Google corp">

<li>Name = #TestStruct2.name.last.offical#</li>
<li>$TestStruct.name</li>