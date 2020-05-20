<cfset Struct2D=StructNew()>
<cfset Struct2D = {Key1 = {innerKey1 = "innerValue1"}}> Working on this.


<cfset student=StructNew()>
<cfset student = {firstName="Jane", lastName="Smith", grades=[91, 78, 87, "A"], city="New York", housenumber=8} >

#student.housenumber#    
    
<cfset #TestStruct2#=StructNew()>
<cfset TestStruct2.name.last.offical="Google corp">

<li>Name = #TestStruct2.name.last.offical#</li>