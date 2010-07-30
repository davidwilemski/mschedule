FYI:  MISchedule is open-source under the GPL.  See the license for more info.

Okay.  So you want to fix MISchedule?

Before trying to figure out how MISchedule works, you need to figure out the new Wolverine Access (WA).

I will be happy to answer all sorts of questions about the MISchedule code later.  But it is all a waste of time if we can't figure out how to get data from WA.

So, using PHP, we have to write a program to get course data from WA.  The new course data is behind a HTTPS layer, which in annoying because:

1) We can't use a packet sniffer to figure out what's going on behind the scenes
2) The PHP code I'm about to give you may not work for HTTPS

Don't know PHP?  Check out http://www.php.net/manual/en/index.php. 

Okay, here's the most basic of starts.  This simply gets the main WA page.  Why use HTTP?  Because most likely we will need to get and set cookies and other header information.

Alright, good luck.  E-mail me if you have questions. (dhostetl@yahoo.com)

-Dan

<?

$host = "wolverineaccess.umich.edu";

$httpRequest  = "GET /index.jsp HTTP/1.0\r\n";
$httpRequest .= "Host: " . $host . "\r\n";
$httpRequest .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.6) Gecko/20040113\r\n";
$httpRequest .= "Accept: application/x-shockwave-flash,text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,image/jpeg,image/gif;q=0.2,*/*;q=0.1\r\n";
$httpRequest .= "Accept-Language: en-us,en;q=0.5\r\n";
$httpRequest .= "Accept-Encoding: gzip,deflate\r\n";
$httpRequest .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";
$httpRequest .= "Connection: close\r\n";
$httpRequest .= "\r\n";

printf("<p>%s\n", $httpRequest);

$response = "";
makeHttpRequest($host, $httpRequest, $response);
printf("<p>%s\n", $response);


function makeHttpRequest($host, $httpRequest, &$response)
{
    $connection = fsockopen ($host, 80, &$errno, &$errstr, 30);
    if (!$connection)
    {
        return -1;
    }
    fputs ($connection, $httpRequest);
    $response = "";
    while (!feof($connection)) 
    {
        $response .= fgets ($connection,1024);
    }
    fclose ($connection);
    return 0;
}
?>