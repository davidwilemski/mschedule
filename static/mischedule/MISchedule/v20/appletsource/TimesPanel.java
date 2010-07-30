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

public class TimesPanel extends MISchedulePanel
{
    private WeekView weekView;
    private TimeHolder timeHolder;
    
    public TimesPanel(ComponentFactory componentFactory, TimeHolder pTimeHolder)
    {
        timeHolder = pTimeHolder;
        add(componentFactory.getWeekView());
        add(componentFactory.getTimesCustomize());
        add(componentFactory.getTimesCategory());
    }

    public void paint(Graphics screen)
    {
        screen.setColor(borderColor);
	screen.drawRect(0,0,bounds.width-1,bounds.height-1);
    }
}
