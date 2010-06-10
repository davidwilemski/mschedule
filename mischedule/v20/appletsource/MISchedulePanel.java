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
import java.util.*;
import java.awt.event.*;

/*  This class is a panel that has the added ability to
    fire events off to registered listeners

    It also provides a common interface for setting the
    size, position, and styles of all the MISchedule components
*/

public class MISchedulePanel extends Panel
{
    private Vector listeners;
    
    // the styles are public, just so they can be easily
    // set by the view, oop be damned
    public Rectangle bounds;
    public Color borderColor;

    public MISchedulePanel()
    {
        setLayout(null);
        listeners = new Vector(10,10);
    }
    
    public synchronized void addMIScheduleListener( MIScheduleListener l ) 
    {
        listeners.addElement( l );
    }
    
    public synchronized void removeMIScheduleListener( MIScheduleListener l ) 
    {
        listeners.removeElement( l );
    }
    
    public synchronized void fireMIScheduleEvent(MIScheduleEvent e) 
    {
	for (int i=0; i<listeners.size(); i++) 
	{
            ( (MIScheduleListener) listeners.elementAt(i) ).requestFinished( e );
        }
    }

    public Dimension getPreferredSize()
    {
        return new Dimension(bounds.width, bounds.height);
    }
   
    protected void putTextInRectangle(Graphics screen, Rectangle b, String s, boolean verticallyCentered)
    {
	FontMetrics fm = screen.getFontMetrics();
        if (verticallyCentered)
	    screen.drawString(s, b.x + (b.width - fm.stringWidth(s)) / 2, b.y + fm.getAscent() + b.height/2);	
        else 
	    screen.drawString(s, b.x + (b.width - fm.stringWidth(s)) / 2, b.y + fm.getAscent());
    }





    protected void fillRect(Graphics screen, Rectangle b)
    {
	screen.fillRect(b.x, b.y, b.width, b.height);
    }
}
