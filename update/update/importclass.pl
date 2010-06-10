#!/usr/bin/perl -w
use DBI;
use LWP::Simple;
use Text::ParseWords;

## configuration stuff
$courses = "http://www.ro.umich.edu/timesched/pdf/FA2010.csv";
$term = "f10";
####
$| = 1;

# get the course guide...
print "fetching course guide...";
$csv = get($courses) or die "can't get courses";
print "done\n";
$dbh = DBI->connect("dbi:mysql:database=mschedule_mi;localhost:3306","mschedule_up","");
@classes = split(/\n/,$csv);
shift(@classes);


print "truncating information from database...";
my $sth = $dbh->prepare("DELETE FROM courses WHERE term='$term'") ;
$sth->execute();
$sth->finish();
$sth = $dbh->prepare("DELETE FROM divisions WHERE term='$term'") ;
$sth->execute();
$sth->finish();
$sth = $dbh->prepare("DELETE FROM locations WHERE term='$term'") ;
$sth->execute();
$sth->finish();
$sth = $dbh->prepare("DELETE FROM meetings WHERE term='$term'") ;
$sth->execute();
$sth->finish();
$sth = $dbh->prepare("DELETE FROM sections WHERE term='$term'") ;
$sth->execute();
$sth->finish();

print "done.\n";


print "updating class database...";
my $prev_classnum = '';
my $prev_num = '';
my $prev_starttimeindex = '';
my $prev_endtimeindex = '';
my @prev_days = '';
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
	my $mon = $fields[10];
	my $tue = $fields[11];
	my $wed = $fields[12];
	my $thu = $fields[13];
	my $fri = $fields[14];
	my $sat = $fields[15];
	my $sun = $fields[16];

	#fix case
	$thu =~ s/TH/Th/;
	$sun =~ s/SU/Su/;
	
	my $days = $mon.$tue.$wed.$thu.$fri.$sat.$sun;

	#reformat time
	($time, $starttimeindex, $endtimeindex) = reformat_time($time);
	
	#make the fields sql-friendly
	$name =~ s/'/\\'/g;
	$location =~ s/'/\\'/g;
	$num  =~ s/[ "]//g;
	$classnum  =~ s/[ "]//g;
	
	my $instructor = $fields[19];
        my $section = $fields[6];
        $section =~ s/[^0-9]//g;
        my $sectype = $fields[8];
        $instructor =~ s/'/\\'/g;

	if ($fields[4] =~ /([^\"]+?) \(([^\"]+?)\)/ )
	{
		#put the divisions in a hash container to insert after
		$divisions{$2} = $1;
		
		#if we have a legit course name/number add it to the db
		if($name ne "" && $num ne "")
		{
			#if it's an extension of the previous class
			if($classnum eq '' &&  $num eq $prev_num)
			{
				$classnum = $prev_classnum;
			}


			my $sth = $dbh->prepare("INSERT IGNORE INTO courses VALUES('$term','$2','$num','$name')");
			#print("INSERT IGNORE INTO courses VALUES('$term','$2','$num','$name')");
			$sth->execute();
			$sth->finish();
			$sth = $dbh->prepare("INSERT INTO locations VALUES('$term','$2','$num','$classnum','$days $time','$location') ON DUPLICATE KEY UPDATE location='$location'");
			#print("INSERT INTO locations VALUES('$term','$2','$num','$classnum','$days $time','$location') ON DUPLICATE KEY UPDATE location='$location'");
			$sth->execute();
			$sth->finish();
			
			if($section)
			{
				my $linkage = 0;
				if($sectype eq 'LEC')
				{
					$linkage = substr($section,length($section)-1);
				}	
				$sth = $dbh->prepare("INSERT IGNORE INTO sections VALUES('$term','$2','$num','$classnum','0',0,0,'$section','$sectype','$instructor',$linkage)");
				$sth->execute();
				$sth->finish();
			}

			if(($starttimeindex && $endtimeindex) && $starttimeindex != $endtimeindex)
			{
				my $stindex;
				my $etindex;
				
				#we recalculate the time index depending on the day
				if($mon ne "")
				{
					$stindex = $starttimeindex + 48*0;
					$etindex = $endtimeindex + 48*0;
					$sth = $dbh->prepare("INSERT IGNORE INTO meetings VALUES('$term','$2','$num','$classnum','$stindex', '$etindex', 'Central')");
					$sth->execute();
					$sth->finish();
				}
				if($tue ne "")
				{
					$stindex = $starttimeindex + 48*1;
					$etindex =  $endtimeindex + 48*1;
					$sth = $dbh->prepare("INSERT IGNORE INTO meetings VALUES('$term','$2','$num','$classnum','$stindex', '$etindex', 'Central')");
					$sth->execute();
					$sth->finish();
				}
				if($wed ne "")
				{
					$stindex = $starttimeindex + 48*2;
					$etindex =  $endtimeindex + 48*2;
					$sth = $dbh->prepare("INSERT IGNORE INTO meetings VALUES('$term','$2','$num','$classnum','$stindex', '$etindex', 'Central')");
					$sth->execute();
					$sth->finish();
				}
				if($thu ne "")
				{
					$stindex = $starttimeindex + 48*3;
					$etindex =  $endtimeindex + 48*3;
					$sth = $dbh->prepare("INSERT IGNORE INTO meetings VALUES('$term','$2','$num','$classnum','$stindex', '$etindex', 'Central')");
						$sth->execute();
					$sth->finish();
				}
				if($fri ne "")
				{
					$stindex = $starttimeindex + 48*4;
					$etindex =  $endtimeindex + 48*4;
					$sth = $dbh->prepare("INSERT IGNORE INTO meetings VALUES('$term','$2','$num','$classnum','$stindex', '$etindex', 'Central')");
						$sth->execute();
					$sth->finish();
				}
				if($sat ne "")
				{
					$stindex = $starttimeindex + 48*5;
					$etindex =  $endtimeindex + 48*5;
					$sth = $dbh->prepare("INSERT IGNORE INTO meetings VALUES('$term','$2','$num','$classnum','$stindex', '$etindex', 'Central')");
					$sth->execute();
					$sth->finish();
				}
				if($sun ne "")
				{
					$stindex = $starttimeindex + 48*6;
					$etindex =  $endtimeindex + 48*6;
					$sth = $dbh->prepare("INSERT IGNORE INTO meetings VALUES('$term','$2','$num','$classnum','$stindex', '$etindex', 'Central')");
						$sth->execute();
					$sth->finish();
				}
			}
		}
	}

	$prev_classnum = $classnum;
	$prev_num = $num;
	$prev_starttimeindex = $starttimeindex;
	$prev_endtimeindex = $endtimeindex;
	@prev_days = ($mon, $tue, $wed, $thu, $fri, $sat, $sun);

	print ".";
}
print("done\n");
#"Fall 2007","Regular Academic Session","Architecture & Urban Planning","10001","Architecture (ARCH)",
#" 201","001","Basic Drawing","LAB","P  W","","T","","TH","","","","130-430PM","1227 A&AB","Harris",




# iterate the container and put each division into the database 
print "inserting divisions into database...";
while ( ($abbr, $desc) = each(%divisions) ) 
{
	$sth = $dbh->prepare("INSERT IGNORE INTO divisions VALUES('$term','$desc','$abbr')");
	$sth->execute();
	$sth->finish();
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

			if($ampm2 eq "PM" && (($th1 > $th2 && $th1 < 12) || ($th2 == 12 && $th1 < 12)))
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



