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

public class TimeProfile
{
    public static int NUM_TYPES = 6;
 
    private static final int DEFAULT = 0;
    private static final int FRIDAY = 1;
    private static final int LATE = 2;
    private static final int MORNING = 3;
    private static final int FOUR_DAY = 4;
    private static final int CUSTOM = 5;

    public static final String CUSTOM_STRING = "Custom";

    private int type;
    private int timeValues[];
    private String title;

    public TimeProfile(int pType)
    {
	type = pType;
        timeValues = new int[TimeHolder.NUM_TIME_VALUES];
        switch ( type )
	{ 
	case DEFAULT:
	    title = "Default";
            initTimeValuesDefault();
            break;
        case FRIDAY:
            title = "Fridays Off";
            initTimeValuesFridaysOff();
            break;
        case LATE:
            title = "Late Riser";
            initTimeValuesLateRiser();
            break;
        case MORNING:
            title = "Afternoons Off";
            initTimeValuesAfternoonsOff();
            break;
        case FOUR_DAY:
            title = "Four Day Weekend";
            initTimeValuesFourDayWeekend();
            break;
        case CUSTOM:
            title = CUSTOM_STRING;
            break;
        }
    }

    public String getTitle()
    {
        return title;
    }

    public int[] getTimeValues()
    {
	return timeValues;
    }

    public void initTimeValuesDefault()
    {
        for (int d=0; d<5; d++)
	    for (int hh=0; hh<48; hh++)
	    {
                int value=0;
                if (hh<18) value=2;
                else if (hh<20) value=1;
                else if (hh<34) value=0;
                else value=1;
                if (d == 4 && value != 4) value++;
                timeValues[d*48+hh] = value;
	    }
    }

    public void initTimeValuesLateRiser()
    {
        for (int d=0; d<5; d++)
	    for (int hh=0; hh<48; hh++)
	    {
                int value=0;
                if (hh<20) value=4;
                else if (hh<22) value=3;
                else if (hh<24) value=2;
                else if (hh<26) value=1;
                else if (hh<42) value=0;
		else value = 1;
                timeValues[d*48+hh] = value;
	    }
    }

    public void initTimeValuesAfternoonsOff()
    {
        for (int d=0; d<5; d++)
	    for (int hh=0; hh<48; hh++)
	    {
                int value=0;
                if (hh<18) value=1;
                else if (hh<24) value=0;
                else if (hh<26) value=1;
                else if (hh<28) value=2;
                else if (hh<30) value=3;
                else value=4;
                timeValues[d*48+hh] = value;
	    }
    }

    public void initTimeValuesFridaysOff()
    {
        for (int d=0; d<5; d++)
	    for (int hh=0; hh<48; hh++)
	    {
                int value=0;
	        if (d == 4)
		{
		    value = 4;
		}
                else 
		{
		    if (hh<18) value=2;
		    else if (hh<20) value=1;
		    else if (hh<34) value=0;
		    else value=1;
	        }
                timeValues[d*48+hh] = value;              
	    }   
    }

    public void initTimeValuesFourDayWeekend()
    {
        for (int d=0; d<5; d++)
	    for (int hh=0; hh<48; hh++)
	    {
                int value=0;
                if (d == 0)
		{
                    if (hh<24) value=4;
                    else value=3;
		}
	        else if (d == 4)
		{
		    if (hh>=24) value = 4;
		    else value = 3;	
		}
                else 
		{
		    if (hh<18) value=2;
		    else if (hh<20) value=1;
		    else if (hh<34) value=0;
		    else value=1;
	        }
                timeValues[d*48+hh] = value;              
	    }   
    }


}

