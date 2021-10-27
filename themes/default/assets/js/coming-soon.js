var montharray=new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
function countdown(yr,m,d) {
theyear=yr;themonth=m;theday=d
var today=new Date()
var todayy=today.getYear()
if (todayy < 1000)
todayy+=1900
var todaym=today.getMonth()
var todayd=today.getDate()
var todayh=today.getHours()
var todaymin=today.getMinutes()
var todaysec=today.getSeconds()
var todaystring=montharray[todaym]+" "+todayd+", "+todayy+" "+todayh+":"+todaymin+":"+todaysec
futurestring=montharray[m-1]+" "+d+", "+yr
dd=Date.parse(futurestring)-Date.parse(todaystring)
dday=Math.floor(dd/(60*60*1000*24)*1)
dhour=Math.floor((dd%(60*60*1000*24))/(60*60*1000)*1)
dmin=Math.floor(((dd%(60*60*1000*24))%(60*60*1000))/(60*1000)*1)
dsec=Math.floor((((dd%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1)
if(dday<=0&&dhour<=0&&dmin<=0&&dsec<=0){
	document.getElementById('coming-countdown').innerHTML=current;
	document.getElementById('coming-countdown').style.display="block";
	document.getElementById('dday').style.display="none";
	document.getElementById('dhour').style.display="none";
	document.getElementById('dmin').style.display="none";
	document.getElementById('dsec').style.display="none";
	document.getElementById('days').style.display="none";
	document.getElementById('hours').style.display="none";
	document.getElementById('minutes').style.display="none";
	document.getElementById('seconds').style.display="none";
	document.getElementById('coming-soon').style.display="none";
	return
}else
	document.getElementById('dday').innerHTML=dday;
	document.getElementById('dhour').innerHTML=dhour;
	document.getElementById('dmin').innerHTML=dmin;
	document.getElementById('dsec').innerHTML=dsec;
	setTimeout("countdown(theyear,themonth,theday)",1000);
}