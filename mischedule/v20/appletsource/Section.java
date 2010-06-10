/*  
    Copyright 2004, Dan Hostetler

    This file is part of MISchedule.

    MISchedule is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    MISchedule is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with MISchedule; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

public class Section
{
    public String division;
    public String course;

    public String classNum;
    public String credits;
    public int openSeats;
    public int waitlistNum;
    public String waitlistDisplay;
    public String sectionType;
    public String sectionTypeAbbrev;
    public String sectionNum;
    public String instructor;
    public int linkageGroup;
    public int numLocations;
    public String timeString[];
    public String location[];
    public int numMeetings;
    public int startTime[];
    public int endTime[];
    public String campus[];
   
    public int score=0;
    public boolean consider=true;

    public Section()
    {
    }
}
