<cfloop from=1 to=#nPoints# index=m>
	<cfset NextLong=getNextLong(plat,plong,azimuth,(m*50))>
	<cfset Nextlat=getNextLat(plat,plong,azimuth,(m*50))>
	<li> *** [ #Nextlat# | #NextLong# | #azimuth# ]
	<cfset n=DownloadImg(Nextlat,NextLong,azimuth,n)>
</cfloop>



<cfset n=n+1> This is HTML code after and inbetween a tag with a #variable# <cfset m=$m+10> Some more HTML #code# ...

<cfset azimuth=Mid(line,Find("azimuth",line),20)>
<cfset azimuth=ListgetAt(azimuth,2,">")>
<cfset azimuth=ListFirst(azimuth,"<")>
    
    
test test <!--- commented out --->more test.    
    
