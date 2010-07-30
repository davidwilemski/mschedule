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

public class MessageBox extends MIScheduleFrame implements ActionListener, WindowListener
{
    private Button okay;
    private String message;

    private int rowHeight;
    private int rowSpacing = 2;


    

    public MessageBox(String pMessage, String pCaption)
    {
        Font f = new Font("TimesRoman", Font.PLAIN, 14);
	setFont(f);

	FontMetrics fm = getFontMetrics(f);

	message = pMessage;
        int width = 140;
        int height = 110;
        rowHeight = fm.getAscent();

	String s[] = TableData.getLinesFromString(message);
        height += s.length * (rowHeight + rowSpacing);
        for (int i=0; i<s.length; i++)
	{
	    int w = fm.stringWidth(s[i]) + 40;
	    if (w > width) width = w;
	}        

        setTitle(pCaption);
        addWindowListener(this);

        setLayout(null);
        setSize(width,height);

        okay = new Button("Okay");
        okay.setBounds((width-100)/2,height-50,100,20);
        okay.addActionListener(this);
        add(okay);
    }

    public void paint(Graphics screen)
    {
	String s[] = TableData.getLinesFromString(message);
        for (int i=0; i<s.length; i++)
	{
	    screen.drawString(s[i], 20, 50+i*(rowHeight+rowSpacing));
	}
    }

    public void actionPerformed(ActionEvent e)
    {
        if (e.getSource() == okay)
        {
            setVisible(false);
        }
    }

    public void windowOpened(WindowEvent e) {}

    public void windowClosing(WindowEvent e) 
    {
        setVisible(false);
    }

    public void windowClosed(WindowEvent e) {}

    public void windowIconified(WindowEvent e) {}

    public void windowDeiconified(WindowEvent e) {}

    public void windowActivated(WindowEvent e) {}

    public void windowDeactivated(WindowEvent e) {}
}
