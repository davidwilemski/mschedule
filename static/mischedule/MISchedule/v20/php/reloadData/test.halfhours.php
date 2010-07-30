<?

require "converttohalfhourspastmonday.php";

print $_GET[time].$_GET[day]."<br>\n";
print convertToHalfHoursPastMonday($_GET[time], $_GET[day]);