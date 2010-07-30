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

import java.awt.event.*;
import java.awt.*;
import java.util.*;

public class DoubleListPanel extends MISchedulePanel implements MouseListener
{
    private Font font;
    private int totalWidth;
    private Vector firstColumn;
    private Vector secondColumn;
    private int rowHeight;
    private int selectedRow;
    private int firstColWidth;
    private int secondColWidth;
    private final static int HEADER_HEIGHT = 10;
    private boolean loading;

    public DoubleListPanel()
    {
	super();
        loading = false;
    }

    public void setData(Vector pFirstColumn, Vector pSecondColumn) 
    {	
	addMouseListener(this);
	font = new Font("TimesRoman", Font.PLAIN, 12);
	setBackground( Color.white );
	firstColumn = pFirstColumn;
	secondColumn = pSecondColumn;
	selectedRow = -1;

	firstColWidth = 0;
	secondColWidth = 0;
	totalWidth = 0;

	FontMetrics fm = getFontMetrics(font);

	rowHeight = fm.getHeight();
	for (int i=0; i< firstColumn.size(); i++)
	{
	    int w = fm.stringWidth((String) firstColumn.elementAt(i));
	    if (w > firstColWidth) firstColWidth = w;
	    w = fm.stringWidth((String) secondColumn.elementAt(i));
	    if (w > secondColWidth) secondColWidth = w;
	}
	totalWidth = firstColWidth + secondColWidth + 10; 
	repaint();
    }

    public void setLoading(boolean pLoading)
    {
        loading = pLoading;
        if (loading == true) firstColumn = null;
        repaint();
    }

    public void setActiveRow(String rowData)
    {
        if (rowData.length() == 0)
	{
	    selectedRow = -1;
  	    repaint();
	    return;
	}

	for (int i=0; i<firstColumn.size(); i++)
	{
	    String s = (String) firstColumn.elementAt(i);
	    if (s.compareTo(rowData) == 0)
	    {
		selectedRow = i;
		repaint();
		fireMIScheduleEvent(new MIScheduleEvent( this,
							 new Point(0, selectedRow * rowHeight - 3),
							 MIScheduleEvent.SCROLL_TO) );
		return;
	    }
	}

    }

    public Dimension getPreferredSize()
    {
	if (firstColumn == null) 
	    return new Dimension(bounds.width-50,bounds.height-50);
	else
   	    return new Dimension(totalWidth, rowHeight * firstColumn.size());
    }

    public Dimension getMinimumSize()
    {
	if (firstColumn == null) 
	    return new Dimension(bounds.width-50,bounds.height-50);
	else
	    return new Dimension(totalWidth, rowHeight * firstColumn.size());
    }

    public void paint(Graphics screen)
    {    
	super.paint(screen);
        if (loading)
	{
            screen.setColor( new Color(239,239,239) );
            Dimension d = getPreferredSize();
            screen.fillRect(0,0,d.width,d.height);
	    screen.setColor( Color.black );
            putTextInRectangle(screen, new Rectangle(0,0,bounds.width,bounds.height), "loading...", false);
        }
        else if (firstColumn == null)
	{
            screen.setColor( new Color(239,239,239) );
            Dimension d = getPreferredSize();
            screen.fillRect(0,0,d.width,d.height);
        }
        else
	{
	    screen.setColor( new Color(239,239,239) );
	    screen.fillRect( 0, 0, firstColWidth + 5, firstColumn.size() * rowHeight );

	    if (selectedRow >= 0 && selectedRow < firstColumn.size())
	    {
	        screen.setColor(new Color(0,0,51));
 	        screen.fillRect(0, selectedRow * rowHeight - 3, 400, rowHeight);  
	    }

            screen.setFont(font);  
	    for (int i=0; i<firstColumn.size(); i++)
	    {
	        if (i == selectedRow) 
                    screen.setColor(new Color(255,255,255));
                else screen.setColor(new Color(0,0,0));
	        screen.drawString((String) firstColumn.elementAt(i), 0, HEADER_HEIGHT + i*rowHeight);
	        screen.drawString((String) secondColumn.elementAt(i), firstColWidth + 10, HEADER_HEIGHT + i*rowHeight);
	    }
	}
    }

    public void mouseClicked(MouseEvent e)
    {
    }

    public void mousePressed(MouseEvent e)
    {
	int newSelectedRow = ( e.getY() - HEADER_HEIGHT + rowHeight ) / rowHeight;
	if (newSelectedRow >= 0 && newSelectedRow < firstColumn.size())
	{
	    selectedRow = newSelectedRow;		
	    rowChanged(selectedRow);
	    repaint();
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

    protected void rowChanged(int selectedRow)
    {
        // this function should be overridden so that the child
        // class can throw the appropriate event
    }

}
