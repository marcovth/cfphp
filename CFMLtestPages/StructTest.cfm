<cfset Struct2D=StructNew()>
<cfset Struct2D = {KeyA = {innerKeyA1 = "innerValueA1", {KeyC = {innerKeyC1 = "innerValueC1"}}, {KeyB={innerKeyB1 = "innerValueB1", innerKeyB2 = "innerValueB2"}}>

<cfset student=StructNew()>
<cfset student = {firstName="Jane", lastName="Smith", grades=[91, 78, 87, "A"], city="New York", housenumber=8} >

#student.housenumber#    

<cfset myStruct=StructNew()>
<cfset myStruct=[first:"I am number one",second="I am number two"]> <cfcommend "// Don't use arrays (like the grades above) in combination with square-brackets used here !!!">

<cfset #TestStruct2#=StructNew()>
<cfset TestStruct2.name.last.offical="Google corp">

<li>Name = #TestStruct2.name.last.offical#</li>