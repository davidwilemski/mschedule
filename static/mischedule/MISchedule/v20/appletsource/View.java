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
import java.applet.*;

/***************************************************** 
* 
* The View class is:
*
*    1. The Panel which holds all other panels in the applet
* 
*
******************************************************/

public class View extends MISchedulePanel
{
    public View ( ComponentFactory componentFactory )
    {
        add ( componentFactory.getMainFrame() );
        add ( componentFactory.getTitleBar() );
        //add ( componentFactory.getBooksPanelScroll() );
    }

    public void paint(Graphics screen)
    {
        super.paint(screen);
    }
}
