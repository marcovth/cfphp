<cfset string="hELLO">
<cfset test=ArrayNew("1,4,55,'zz'")>#test[4]#
<cfset arrayInsertAt($test,2,6)>#test[2]#