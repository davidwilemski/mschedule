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

import java.util.*;

public class MIScheduleThread extends Thread
{
    Vector listeners;

    public MIScheduleThread()
    {
	listeners = new Vector();
    }

    public synchronized void addMIScheduleListener( MIScheduleListener l ) 
    {
        listeners.addElement( l );
    }
    
    public synchronized void removeMIScheduleListener( MIScheduleListener l ) {
        listeners.removeElement( l );
    }

    public synchronized void fireMIScheduleEvent(MIScheduleEvent e) 
    {
	for (int i=0; i<listeners.size(); i++) 
	{
            ( (MIScheduleListener) listeners.elementAt(i) ).requestFinished( e );
        }
    }
}
