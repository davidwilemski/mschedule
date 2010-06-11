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

/*  MainFrame contains the main content frame as
    distinguished from the title bar and the books
    frame.  The main content frame can be switched
    from choosing classes, to choosing times, to 
    displaying schedules.
*/

public class MainFrame extends MISchedulePanel
{
    private CardLayout layout;

    public static final String CLASSES = "classes";
    public static final String TIMES = "times";
    public static final String SCHEDULE_FETCH = "fetch";
    public static final String SCHEDULE_DISPLAY = "display";
    public static final String SECTIONS = "sections";
    
    private String currentCard;

    private ComponentFactory factory;

    public MainFrame(ComponentFactory pFactory)
    {
        factory = pFactory;
	layout = new CardLayout();
	setLayout(layout);

        add(CLASSES, factory.getClassesPanel());
        add(TIMES, factory.getTimesPanel());
        add(SCHEDULE_FETCH, factory.getSchedulesFetchPanel());
        add(SCHEDULE_DISPLAY, factory.getSchedulesDisplayScroll());
	add(SECTIONS, factory.getDisplaySectionsPanelScroll());

        currentCard = CLASSES;
    }

    public void setCard(String cardName)
    { 
        if (currentCard == cardName) return;
        
        if (cardName == SCHEDULE_DISPLAY || cardName == SECTIONS)
	    factory.getCantLeave().setVisible(false);

	layout.show(this, cardName);
        currentCard = cardName;
    }

    public String getCard()
    {
        return currentCard;
    }
}
