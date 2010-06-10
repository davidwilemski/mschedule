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

import java.io.*;
import java.util.EventObject;

public class MIScheduleEvent extends EventObject 
{
    private Object data;
    private int type;
    
    public final static int GOT_DIVISION_LIST = 1;
    public final static int GOT_COURSE_LIST = 2;
    public final static int GOT_SECTION_LIST = 3;
    public final static int GOT_BOOKS = 4;
    public final static int DIVISION_SELECTED = 5;
    public final static int COURSE_SELECTED = 6;
    public final static int BOOK_CLICKED = 7;
    public final static int TITLE_BAR_CLICKED = 8;
    public final static int TIMES_CUSTOMIZE_CLICKED = 9;
    public final static int CELL_CLICKED = 11;
    public final static int FINISHED_BUILDING_SCHEDULES = 12;
    public final static int SET_TIME_PROFILE = 13;
    public final static int DONE_FETCHING_SECTIONS = 14;
    public final static int SCROLL_TO = 15;
    public final static int SCHEDULE_STATUS = 16;
    public final static int PRINT_SECTIONS = 17;
    public final static int PRINT_SCHEDULES = 18;


    public MIScheduleEvent( Object source, Object pData, int pType ) 
    {
        super( source );
	data = pData;
	type = pType;
    }

    public Object getData()
    {
	return data;
    }

    public int getType()
    {
	return type;
    }
}    

