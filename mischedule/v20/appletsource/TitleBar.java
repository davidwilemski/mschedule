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
import java.applet.*;

/***************************************************** 
* 
* The TitleBar class 
*
*     The panel that is the TitleBar on top of the applets
*
******************************************************/

public class TitleBar extends MISchedulePanel implements MouseListener
{
    private Applet applet;

    private Image titleImage;
    private Image classesTab;
    private Image timesTab;
    private Image sectionsTab;
    private Image schedulesTab;

    private Rectangle classesRect;
    private Rectangle timesRect;
    private Rectangle sectionsRect;
    private Rectangle schedulesRect;

    public TitleBar(Applet pApplet)
    {
        applet = pApplet;

        addMouseListener(this);

        titleImage = applet.getImage(applet.getCodeBase(), "appletimages/title_small.gif");
        classesTab = applet.getImage(applet.getCodeBase(), "appletimages/classes_tab.gif");
        timesTab = applet.getImage(applet.getCodeBase(), "appletimages/times_tab.gif");
        sectionsTab = applet.getImage(applet.getCodeBase(), "appletimages/sections_tab.gif");
        schedulesTab = applet.getImage(applet.getCodeBase(), "appletimages/schedules_tab.gif");

        Image images[] = { titleImage, classesTab, timesTab, sectionsTab, schedulesTab };
        MediaTrackerThread t = new MediaTrackerThread(this, images);
        t.start();

        classesRect = new Rectangle(70,2,110,30);
        timesRect = new Rectangle(190,2,95,30);
        sectionsRect = new Rectangle(295,2,130,30);
        schedulesRect = new Rectangle(445,2,148,30);
    }

    public void mouseClicked(MouseEvent e) {}    
    public void mouseEntered(MouseEvent e) {}
    public void mouseExited(MouseEvent e) {}
    
    public void mousePressed(MouseEvent e)
    {
        if (classesRect.contains(e.getX(), e.getY()))
        {
            fireMIScheduleEvent( new MIScheduleEvent(this, MainFrame.CLASSES , MIScheduleEvent.TITLE_BAR_CLICKED) );
        }

        if (timesRect.contains(e.getX(), e.getY()))
        {
            fireMIScheduleEvent( new MIScheduleEvent(this, MainFrame.TIMES , MIScheduleEvent.TITLE_BAR_CLICKED) );
        }

        if (schedulesRect.contains(e.getX(), e.getY()))
        {
            fireMIScheduleEvent( new MIScheduleEvent(this, MainFrame.SCHEDULE_FETCH , MIScheduleEvent.TITLE_BAR_CLICKED) );
        }

        if (sectionsRect.contains(e.getX(), e.getY()))
	{
            fireMIScheduleEvent( new MIScheduleEvent(this, MainFrame.SECTIONS, MIScheduleEvent.TITLE_BAR_CLICKED) );
        }
    }

    public void mouseReleased(MouseEvent e) {}
    

    public void paint(Graphics screen)
    {
        screen.setColor(new Color(0,0,48));


        screen.drawImage(titleImage, 0, 0, 77, 60, applet);
        screen.fillRect(60,0,600-60,35);
        screen.drawImage(classesTab, 
                         classesRect.x, 
                         classesRect.y, 
                         classesRect.width, 
                         classesRect.height,    
                         applet);
        screen.drawImage(timesTab, 
                         timesRect.x, 
                         timesRect.y, 
                         timesRect.width, 
                         timesRect.height, 
                         applet);
        screen.drawImage(sectionsTab, 
                         sectionsRect.x, 
                         sectionsRect.y, 
                         sectionsRect.width, 
                         sectionsRect.height, 
                         applet);
        screen.drawImage(schedulesTab, 
                         schedulesRect.x, 
                         schedulesRect.y, 
                         schedulesRect.width, 
                         schedulesRect.height, 
                         applet);
        screen.setColor(new Color(0,0,0));
        screen.drawString("Click the buttons above to go through the stages of building your schedule",100,50);
    }
}
