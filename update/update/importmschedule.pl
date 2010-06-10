#!/usr/bin/perl -w
use DBI;
use LWP::Simple;
use Text::ParseWords;

## configuration stuff
$courses = "http://www.ro.umich.edu/timesched/pdf/FA2010.csv";
$term = "fall10";
####
$| = 1;
# get the course guide...
print "fetching course guide...";
$csv = get($courses) or die "can't get courses";
print "done\n";
$dbh = DBI->connect("dbi:mysql:database=mschedule;localhost:3306","mschedule_up","") or die ("can't ocnnect to db!");
@classes = split(/\n/,$csv);
shift(@classes);

print "truncating information from database...";
my $sth = $dbh->prepare("DELETE FROM classes_$term") ;
$sth->execute();
$sth->finish();
print "done.\n";


print "updating class database...";
my $prevclassnum = "";
my $prevsection = "";
foreach $class (@classes)
{
	@fields = quotewords(",",0,$class);
	my $starttimeindex=0;
	my $endtimeindex=0;
	my $num = $fields[5];
	my $name = $fields[7];
	my $classnum = $fields[3];
	my $location = $fields[18];
        my $instructor = $fields[19];
        my $section = $fields[6];
        my $sectype = $fields[8];


	my $time = $fields[17];
	my $mon = $fields[10];
	my $tue = $fields[11];
	my $wed = $fields[12];
	my $thu = $fields[13];
	my $fri = $fields[14];
	my $sat = $fields[15];
	my $sun = $fields[16];

	$time=~ s/AM//;
	$tue =~ s/T/TU/;
	my $days = $mon.$tue.$wed.$thu.$fri.$sat.$sun;

	#make the fields sql-friendly
	$name =~ s/'/\\'/g;
	$location =~ s/'/\\'/g;
	$num  =~ s/[ "]//g;
	$classnum  =~ s/[ "]//g;
        $instructor =~ s/'/\\'/g;
        $section =~ s/[^0-9]//g;


	if ($fields[4] =~ /([^\"]+?) \(([^\"]+?)\)/ )
	{
		#if we have a legit course name/number add it to the db
		if($name ne "" && $num ne "" && $classnum ne "")
		{

			my $sth = $dbh->prepare("INSERT INTO classes_$term VALUES('$classnum','$2','$num','$section','','$sectype','$days','$time','$location','$instructor') ON DUPLICATE KEY UPDATE location='$location', instructor='$instructor'");
			$sth->execute();
			$sth->finish();
		}
		else 
		{
			$classnum = $prevclassnum;
			$section = $prevsection;
			#if we have a legit course name/number add it to the db
			if($name ne "" && $num ne "" && $classnum ne "")
			{

				my $sth = $dbh->prepare("INSERT INTO classes_$term VALUES('$classnum','$2','$num','$section','','$sectype','$days','$time','$location','$instructor') ON DUPLICATE KEY UPDATE location='$location', instructor='$instructor'");
				$sth->execute();
				$sth->finish();
			}
		}

	}
	$prevclassnum = $classnum;
	$prevsection = $section;

	print ".";
}
print("done\n");

$dbh->disconnect;

exit;

