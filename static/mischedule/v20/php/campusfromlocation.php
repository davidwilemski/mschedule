<?
function getCampusFromLocation($location)
{
   $components = explode(" ", $location);
   if (sizeof($components) != 2)
       return "Central";
   else
       $building = $components[1];

   if (strcmp($building, "EECS") == 0) 
       $campus = "North";
   else if (strcmp($building, "FXB") == 0)
       $campus = "North";
   else if (strcmp($building, "DOW") == 0)
       $campus = "North";
   else if (strcmp($building, "GGBL") == 0)
       $campus = "North";
   else if (strcmp($building, "SRB") == 0)
       $campus = "North";
   else if (strcmp($building, "IOE") == 0)
       $campus = "North";
   else if (strcmp($building, "COOL") == 0)
       $campus = "North";
   else if (strcmp($building, "A&AB") == 0)
       $campus = "North";
   else if (strcmp($building, "BMT") == 0)
       $campus = "North";
   else if (strcmp($building, "SM") == 0)
       $campus = "North";
   else if (strcmp($building, "CHRYS") == 0)
       $campus = "North";
   else
       $campus = "Central";

   return $campus;
}