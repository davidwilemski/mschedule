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

public class TimesCustomize extends MISchedulePanel implements MouseListener
{
    private final static int HEADER_SIZE = 20;
    private final static int BOX_SIZE = 30;
    private final static int INSET_SIZE = 10;
    private final static int SEL_SIZE = 2;

    private Color colors[];
    private Rectangle rects[];
    private int selectedBox;
    private TimeHolder timeHolder;
    private boolean active=false;

    public TimesCustomize(TimeHolder pTimeHolder)
    {
        timeHolder = pTimeHolder;
	colors = timeHolder.getColors();
	selectedBox = 0;

	rects = new Rectangle[colors.length];
	int x = INSET_SIZE;
	int y = INSET_SIZE;
	for (int i=0; i<colors.length; i++)
	{
	    rects[i] = new Rectangle(x,y,BOX_SIZE,BOX_SIZE);
	    x += BOX_SIZE + INSET_SIZE;
	}
	
	addMouseListener(this);
    }

    public void paint(Graphics screen)
    {
	super.paint(screen);
	for (int i=0; i<colors.length; i++)
	{
	    screen.setColor(colors[i]);
	    screen.fillRect(rects[i].x, rects[i].y, rects[i].width, rects[i].height);
	    if (selectedBox == i)
	    {
		screen.setColor(Color.blue);
		screen.drawRect(rects[i].x, rects[i].y, rects[i].width, rects[i].height);
		screen.drawRect(rects[i].x+SEL_SIZE, rects[i].y+SEL_SIZE, rects[i].width-2*SEL_SIZE, rects[i].height-2*SEL_SIZE);
	    }
	    else
	    {
		screen.setColor(Color.black);
		screen.drawRect(rects[i].x, rects[i].y, rects[i].width, rects[i].height);
	    }
	}

        screen.setColor(Color.black);
	for (int i=0; i<rects.length; i++)
	{
	    FontMetrics fm = screen.getFontMetrics();
	    String s;
	    if (i==0) s = "Best";
	    else if (i==rects.length-1) s = "Worst";
	    else s = ".";
		
	    screen.drawString(s, rects[i].x, rects[0].y + rects[0].height + fm.getAscent() + 5);
	}
	
	if (active) 
	{
	    screen.drawString("Customize:", 5, 80);
	    screen.drawString("1. Click one of the boxes above", 15, 95);
	    screen.drawString("2. On the calendar, left-click and hold while", 15, 110);
	    screen.drawString("moving the mouse over the cells to color", 28, 125);
	}
	

        screen.setColor(borderColor);
        screen.drawRect(0,0,bounds.width-1, bounds.height-1);
    }

    public void setActive(boolean pActive)
    {
	active = pActive;
	repaint();
    }


    public void mouseClicked(MouseEvent e)
    {
    }

    public void mousePressed(MouseEvent e)
    {       
	if (!active) return;
	for (int i=0; i<colors.length; i++)
	{    
	    if (rects[i].contains(e.getX(), e.getY()))
	    {
		selectedBox = i;
                fireMIScheduleEvent( new MIScheduleEvent (this, new Integer(i), MIScheduleEvent.TIMES_CUSTOMIZE_CLICKED) );  
		repaint();
		return;
	    }
	}
        
    }

    public void mouseReleased(MouseEvent e)
    {
    }

    public void mouseEntered(MouseEvent e)
    {
    }

    public void mouseExited(MouseEvent e)
    {
    }


}
