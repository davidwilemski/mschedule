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

public class PrintSchedules extends MIScheduleFrame implements ActionListener, WindowListener
{
    private Button print;
    private Button cancel; 

    private Checkbox printTable;
    private Checkbox printList;
 
    public final static int PRINT_TABLE = 0;
    public final static int PRINT_LIST = 1;
    public final static int PRINT_BOTH = 2;

    public PrintSchedules()
    {
        setTitle("Print this schedule");
        addWindowListener(this);

        print = new Button("Print");
        print.setBounds(20,220,100,20);
        print.addActionListener(this);
        add(print);

        cancel = new Button("Cancel");
        cancel.setBounds(140,220,100,20);
        cancel.addActionListener(this);
        add(cancel);

        printTable = new Checkbox("Print schedule in table form", true);
        printTable.setBounds(40,120,250,15);
        add(printTable);

        printList = new Checkbox("Print schedule in list form", true);
        printList.setBounds(40,140,250,15);
        add(printList);

        setLayout(null);
        setSize(300,300);
    }

    public void paint(Graphics screen)
    {
        screen.drawString("Click 'Print' to open a new browser window,", 20, 50);
        screen.drawString("showing a printer friendly version of this data.", 20, 70);
        screen.drawString("You can then print straight from your browser.", 20, 90);
    }

    public Dimension getPreferredSize()
    {
        return new Dimension(300,300);
    }

    public void actionPerformed(ActionEvent e)
    {
        if (e.getSource() == print)
        {          
            if (printTable.getState() && printList.getState())
	    {         
                fireMIScheduleEvent( new MIScheduleEvent(this, new Integer(PRINT_BOTH), MIScheduleEvent.PRINT_SCHEDULES));
	    }
            else if (printTable.getState())
	    {
                fireMIScheduleEvent( new MIScheduleEvent(this, new Integer(PRINT_TABLE), MIScheduleEvent.PRINT_SCHEDULES));
	    }
            else if (printList.getState())
	    {
                fireMIScheduleEvent( new MIScheduleEvent(this, new Integer(PRINT_LIST), MIScheduleEvent.PRINT_SCHEDULES));
	    }
            setVisible(false);
            return;
        }
        if (e.getSource() == cancel)
        {
            setVisible(false);
        }
    }

    public void windowOpened(WindowEvent e) {}

    public void windowClosing(WindowEvent e) 
    {
        setVisible(false);
    }

    public void windowClosed(WindowEvent e) {
        System.out.println("Closed");
    }

    public void windowIconified(WindowEvent e) {}

    public void windowDeiconified(WindowEvent e) {}

    public void windowActivated(WindowEvent e) {}

    public void windowDeactivated(WindowEvent e) {}
}
