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

import java.awt.*;

public class TimeHolder
{
    public final static int NUM_TIME_VALUES = 48*7;

    private Color[] colors;
    private int timeValues[];
    private TimeProfile[] timeProfiles;
    private int activeProfile;

    public TimeHolder()
    {
	timeProfiles = new TimeProfile[TimeProfile.NUM_TYPES];
        activeProfile = 0;
        timeValues = getTimeProfile(activeProfile).getTimeValues();
    }

    public static int getDayFromTime(int t)
    {
        return (t / 48);
    }
    
    public static int getHalfHourFromTime(int t)
    {
        return (t % 48);
    }

    public int getNumProfiles()
    {
        return timeProfiles.length;
    }

    public String getProfileTitle(int i)
    {
        if (i<0 || i>timeProfiles.length)
	{
            System.out.println("Invalid call to timeHolder.getProfileTitle()");
            return null;
        }
        TimeProfile t = getTimeProfile(i);
        return t.getTitle();
    }

    public void setActiveProfile(String s)
    {
        for (int i=0; i<timeProfiles.length; i++)
	{
            if (s == getProfileTitle(i))
	    {
                activeProfile = i;
                timeValues = getTimeProfile(activeProfile).getTimeValues();
                return;
	    }
        }
        System.out.println("Invalid call to timeHolder.setActiveProfile");
    }
     
    private TimeProfile getTimeProfile(int i)
    {
        if ( timeProfiles[i] == null )
            timeProfiles[i] = new TimeProfile(i);
        return timeProfiles[i];
    }

    public static String getTimeStringFromHH(int hh)
    {
        String timeString;
        String suffix;

        int hour = hh / 2;
        if ( hh % 2 == 1 )
        {
            if (hour < 12)
                suffix = ":30am";
            else 
                suffix = ":30pm";
        }
        else
        {
            if (hour < 12)
                suffix = ":00am";
            else 
                suffix = ":00pm";
        }

        if ( hour <= 12 ) 
            timeString = (new Integer(hour).toString()) + suffix;
        else 
            timeString = (new Integer(hour-12).toString()) + suffix;

        return timeString;
    }
 
    public int[] getTimeValues()
    {
	return timeValues;
    }
   
    public Color[] getColors()
    {
	if (colors == null)
	{
             colors = new Color[5];
             colors[0] = Color.white;
             colors[1] = Color.green;
             colors[2] = Color.yellow;
             colors[3] = Color.orange;
             colors[4] = Color.red;
	}
        return colors;
    }
}
