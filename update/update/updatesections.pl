#!/usr/bin/perl -w
use DBI;
use LWP::Simple;
use Text::ParseWords;

## configuration stuff
$courses = "http://www.ro.umich.edu/timesched/pdf/FA2010_open.csv";
$term = "f10";
####
$| = 1;
# get the course guide...
print "fetching course guide...";
$csv = get($courses) or die "can't get courses";
print "done.\n";

print "connecting to database...";
$dbh = DBI->connect("dbi:mysql:database=mschedule_mi;localhost:3306","mschedule_up","");
print "done.\n";

print "parsing file...";
@classes = split(/\n/,$csv);
shift(@classes);
print "done.\n";

#print "truncating information from database...";
#
#my $sth = $dbh->prepare("DELETE FROM sections") ;
#$sth->execute(); 
#$sth->finish();
#print "done.\n";

print "updating class database...";
my $prev_classnum = '';
my $prev_num = '';
my $prev_starttimeindex = '';
my $prev_endtimeindex = '';
my @prev_days = '';
my $prev_seats = 0;
foreach $class (@classes)
{
	@fields = quotewords(",",0,$class);
	my $starttimeindex=0;
	my $endtimeindex=0;
	my $num = $fields[5];
	my $name = $fields[7];
	my $classnum = $fields[3];
	my $location = $fields[18];
	my $time = $fields[17];

	my $instructor = $fields[19];
	my $totalseats = $fields[20];
	my $seats = $fields[21];
	my $section = $fields[6];
	my $sectype = $fields[8];
	#not in csv 
	my $waitlist = 0;
	my $credits = 0;

	
	#make the fields sql-friendly
	$name =~ s/'/\\'/g;
	$instructor =~ s/'/\\'/g;
	$location =~ s/'/\\'/g;
	$num  =~ s/[ "]//g;
	$classnum  =~ s/[ "]//g;
	$classnum  =~ s/[ "]//g;
	$seats  =~ s/[ "]//g;
	$section =~ s/[^0-9]//g;

	if ($fields[4] =~ /([^\"]+?) \(([^\"]+?)\)/ )
	{
		#if we have a legit course name/number add it to the db
		if($name ne "" && $num ne "" && $classnum ne "")
		{
			my $linkage = 0;

			if(!$seats)
			{
				$seats = $prev_seats;
				if($seats eq '')
				{
					$seats = 0;
				}
			}

			if($sectype eq 'LEC')
			{
				$linkage = substr($section,length($section)-1);
			}
			my $query= "INSERT INTO sections VALUES('$term','$2','$num','$classnum','$credits',$seats,$waitlist,'$section','$sectype','$instructor',$linkage) ON DUPLICATE KEY UPDATE openSeats=$seats, waitlistNum=$waitlist, instructor='$instructor'" ;
			my $sth = $dbh->prepare("$query") ;
			$sth->execute() or print "bad query ::$query ::\n";
			$sth->finish();

		}
	}

	$prev_classnum = $classnum;
	$prev_num = $num;
	$prev_starttimeindex = $starttimeindex;
	$prev_endtimeindex = $endtimeindex;
	$prev_seats = $seats;
	print ".";
}
print "done\n";

$dbh->disconnect;

exit;

sub reformat_time {
	my $time = shift;
	my $stindex;# = shift;
	my $etindex;# = shift;
	$th1='', $th2='', $tm1='', $tm2='', $ampm1=''; 

	if($time =~ /([0-9]+?)-([0-9]+?)(AM|PM)/)
	{
		my $t1 = $1;
		my $t2 = $2;
		$ampm2 = $3;
	
		if($t1 ne "" && $t2 ne "" and $ampm2 ne "")
		{
			if(length($t1) < 3)
			{
				$th1 = $t1;
				$tm1 = '00';
			}
			elsif(length($t1) == 3)
			{
				$th1 = substr($t1,0,1);
				$tm1 =  substr($t1,1,2);
			}
			else 
			{
				$th1 = substr($t1,0,2);
				$tm1 = substr($t1,2,2);
			}

			if(length($t2) < 3)
			{
				$th2 = $t2;
				$tm2 = '00';
			}
			elsif(length($t2) == 3)
			{
				$th2 = substr($t2,0,1);
				$tm2 = substr($t2,1,2);	
			}
			else 
			{
				$th2 = substr($t2,0,2);
				$tm2 = substr($t2,2,2);
			}

			if($ampm2 eq "PM" && $th1 > $th2 && $th1 < 12)
			{
				$ampm1 = "AM";
			}
			else
			{
				$ampm1 = $ampm2;	
			}
		}
	}
	if($th1 ne '')
	{
		
		#make the mischedule time indexes
		if($th1 == 12)
		{
			$stindex = 0;
		}
		else
		{
			$stindex = $th1*2;
		}
		if($ampm1 eq 'PM')
		{
			$stindex += 12*2;
		}

		if($tm1 >= 30)
		{
			$stindex++;
		}

		if($th2 == 12)
		{
			$etindex = 0;
		}
		else
		{
			$etindex = $th2*2;
		}
		if($ampm2 eq 'PM')
		{
			$etindex += 12*2;
		}

		if($tm2 >= 30)
		{
			$etindex++;
		}

		#$time = "$th1:$tm1$ampm1-$th2:$tm2$ampm2";
		return ("$th1:$tm1$ampm1-$th2:$tm2$ampm2", $stindex, $etindex);

	}
	else
	{
		$time = $time;
		$etindex = 0;
		$stindex = 0;
	}
}



