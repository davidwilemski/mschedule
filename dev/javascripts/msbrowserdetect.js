var browser;

if (navigator.appName == "Netscape")
	browser = "Mozilla";
else if (navigator.appName == "Microsoft Internet Explorer")
	browser = "IE";
else
	browser = "Mozilla";

if (navigator.userAgent.indexOf('Safari') != -1)
	browser = "Safari";


