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

/*  This is the panel which holds all the components which 
    the user needs to enter the classes he wants to take.
    These components are the ClassesInputPanel, DivisionListPanel, 
    and CourseListPanel.
*/

public class ClassesPanel extends MISchedulePanel
{
    // these should get set in the UIManagerClass
    public Color listHeaderBackColor;
    public Color listHeaderForeColor;
    public String divListHeaderText;
    public String courseListHeaderText;
    public Rectangle divListHeaderBounds;
    public Rectangle courseListHeaderBounds;

    public ClassesPanel(ComponentFactory componentFactory)
    {
	add(componentFactory.getClassesInputPanel());	
	add(componentFactory.getDivisionListScroll());	
	add(componentFactory.getCourseListScroll());	
    }

    public void paint(Graphics screen)
    {
        super.paint(screen);
        System.out.println("Painting");

	screen.setColor(listHeaderBackColor);
	fillRect(screen, divListHeaderBounds);
	fillRect(screen, courseListHeaderBounds);

	screen.setColor(listHeaderForeColor);
	screen.setFont( getFont() );
        putTextInRectangle(screen, divListHeaderBounds, divListHeaderText, false);
        putTextInRectangle(screen, courseListHeaderBounds, courseListHeaderText, false);

        screen.setColor(new Color(0,0,0));
        screen.drawString("Enter your courses in the boxes above,",10,310);
        screen.drawString("      For example: PSYCH 111",10,325);

        screen.drawString("You can either type the information in-",10,355);
        screen.drawString("Or click on the lists to the right.",10,370);


        screen.setColor(borderColor);
	screen.drawRect(0,0,bounds.width-1,bounds.height-1);
    }
}

