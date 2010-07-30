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
import java.awt.event.*;

public class TimesCategory extends MISchedulePanel implements ItemListener
{
    final static int HEADER_SIZE = 20;
    final static int ROW_SIZE = 30;
    final static int COL_SIZE = 150;
    final static int INSET_SIZE = 5;

    private TimeHolder timeHolder;
    private int numButtons;
    private CheckboxGroup checkboxGroup;

    private boolean firstPaint = true;

    public TimesCategory(TimeHolder pTimeHolder)
    {
        timeHolder = pTimeHolder;
        numButtons = 5;
    }

    public void paint(Graphics screen)
    {
	if (firstPaint)
	{
            checkboxGroup = new CheckboxGroup();
	    for (int i=0; i<timeHolder.getNumProfiles(); i++)
	    {
		String title = timeHolder.getProfileTitle(i);
                Checkbox c;
                if ( i==0 )
                    c = new Checkbox(title, checkboxGroup, true);
		else
                    c = new Checkbox(title, checkboxGroup, false);
                c.setBounds(5,i*20+40,150,15);
                c.addItemListener(this);
                add(c);
            }
            firstPaint = false;
        }

        super.paint(screen);
        screen.setColor( Color.black );
        screen.drawString("Choose Time Preference", 5, 20);

        screen.setColor(borderColor);
        screen.drawRect(0,0,bounds.width-1,bounds.height-1); 
    }

    public void itemStateChanged(ItemEvent e)
    {
        String label = ((Checkbox) e.getSource()).getLabel();
        fireMIScheduleEvent( new MIScheduleEvent(this, label, MIScheduleEvent.SET_TIME_PROFILE) );
    }
    
}
