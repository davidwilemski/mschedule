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

public class TableData extends MISchedulePanel implements MouseListener
{
    public TableDataFormat format;

    private String data[][];
    private Component components[][];
    private Rectangle dataBounds[][];
    private CellFormat cellFormats[][];
    private int rowHeights[];
    private int colWidths[];
    private boolean finalized = false;
    private boolean initialized = false;
    private int spaceBetweenRows = 2;

    public TableData(TableDataFormat pFormat, boolean pListen)
    {   
        format = pFormat; 
        if (pListen)
	{
	    setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
	    addMouseListener(this);     
	}
        setBackground(pFormat.bgColor);
    }

    public void init()
    {     
        removeAll();
        data = new String[format.numRows][format.numCols];
        dataBounds = new Rectangle[format.numRows][format.numCols];
        components = new Component[format.numRows][format.numCols];
        cellFormats = new CellFormat[format.numRows][format.numCols];
        colWidths = new int[format.numCols];
        rowHeights = new int[format.numRows];
        for (int i=0; i<colWidths.length; i++) colWidths[i] = 0;
        initialized = true;
    }

    public void setData(String pData, int r, int c)
    {
        if (!initialized)
	    System.out.println("Trying to set table data before initialization.");
        else if ( r < 0 || c < 0 || r >= format.numRows || c >= format.numCols )
	    System.out.println("Out of bounds setting table data at " + r + " , " + c);
        else
	{
            if (pData == null) return;
            data[r][c] = pData;  
            String lines[] = getLinesFromString(pData);
            for (int l=0; l<lines.length; l++)
	    {
                int width = format.fontMetrics.stringWidth(lines[l]);
                if (width > colWidths[c]) colWidths[c] = width;
            }
            int height = (format.fontMetrics.getAscent() + spaceBetweenRows) * lines.length;
            if (height > rowHeights[r]) rowHeights[r] = height;
        }
    }

    public void setData(String pData, CellFormat cellFormat, int r, int c)
    {
        if (!initialized)
	    System.out.println("Trying to set table data before initialization.");
        else if ( r < 0 || c < 0 || r >= format.numRows || c >= format.numCols )
	    System.out.println("Out of bounds setting table data at " + r + " , " + c);
        else
	{
            if (pData == null) return;
            data[r][c] = pData;  
            cellFormats[r][c] = cellFormat;
            String lines[] = getLinesFromString(pData);
            for (int l=0; l<lines.length; l++)
	    {
                int width = format.fontMetrics.stringWidth(lines[l]);
                if (width > colWidths[c]) colWidths[c] = width;
            }
            int neededHeight = (format.fontMetrics.getAscent() + spaceBetweenRows) * lines.length;        
	    
	    int heightWeHave = 0;
	    for (int i=0; i<cellFormat.rowSpan; i++)
	    {
		heightWeHave += rowHeights[r+i];
	    }
            if ( neededHeight > heightWeHave )
	    {
		int averageHeightNeeded = ( neededHeight / cellFormat.rowSpan ) + 1;
		for (int i=0; i<cellFormat.rowSpan; i++)
	        {
		    if ( rowHeights[r+i] < averageHeightNeeded ) rowHeights[r+i] = averageHeightNeeded;
	        }
	    }
        }
    }

    public void setComponent(Component comp, int r, int c)
    {
        if (!initialized)
	    System.out.println("Trying to set table data before initialization.");
        else if ( r < 0 || c < 0 || r >= format.numRows || c >= format.numCols )
	    System.out.println("Out of bounds setting component at " + r + " , " + c);
        else 
	{
            Dimension d = comp.getSize();
            if (d.width > colWidths[c]) colWidths[c] = d.width;
            if (d.height > rowHeights[r]) rowHeights[r] = d.height;
            components[r][c] = comp;
        }
    }

    public void finalizeData()
    { 
        int x = format.inset, y = format.inset;
	for (int r=0; r<format.numRows; r++)
	{
            x = format.inset;
            for (int c=0; c<format.numCols; c++)
	    {
                dataBounds[r][c] = new Rectangle(x,y,colWidths[c],rowHeights[r]);
                if (components[r][c] != null)
		{
                    components[r][c].setBounds(dataBounds[r][c]);
                    add(components[r][c]);
                }
                x += colWidths[c] + format.inset;
            }
            y += rowHeights[r] + format.inset;
        }

	for (int r=0; r<format.numRows; r++)
	{
            for (int c=0; c<format.numCols; c++)
	    {
                if (cellFormats[r][c] != null)
		{
                    if (cellFormats[r][c].rowSpan > 1)
		    {
                        int lastRow = r + cellFormats[r][c].rowSpan - 1;
                        int lastY = dataBounds[lastRow][c].height + dataBounds[lastRow][c].y;
                        dataBounds[r][c].height = lastY - dataBounds[r][c].y;
                    }
		}
            }
        }

        bounds = new Rectangle(0,0,x,y);
        finalized = true;
    }

    public void paint(Graphics screen)
    {
        if (!finalized) return;
 
        screen.setFont(format.font);
	for (int r=0; r<format.numRows; r++)
	{
            for (int c=0; c<format.numCols; c++)
	    {
                if (data[r][c] != null)
		{ 
                    Color fgColor = Color.black;
                    if (cellFormats[r][c] != null)
		    {
                        screen.setColor(cellFormats[r][c].bgColor);
                        Rectangle b = getFullBounds(r,c);
                        screen.fillRect(b.x, b.y, b.width, b.height);
                        fgColor = cellFormats[r][c].fgColor;
		    }
                    screen.setColor(fgColor);
                    String lines[] = getLinesFromString(data[r][c]);
                    for (int l=0; l<lines.length; l++)
		    {
                        screen.drawString(lines[l], 
					  dataBounds[r][c].x, 
                                          dataBounds[r][c].y + (format.fontMetrics.getAscent()+spaceBetweenRows) * (l+1) );
		    }
		}
                if (data[r][c] != null || components[r][c] != null)
		{
                    if (format.insideBorder)
		    {
                        screen.setColor(Color.black);
                        Rectangle b = getFullBounds(r,c);
                        screen.drawRect(b.x,b.y,b.width,b.height);
                    }
                }
            }
        }
        if (format.outsideBorder)
	{
            screen.setColor(Color.black);
            screen.drawRect(0,0,bounds.width-1,bounds.height-1);
        }
    }

    public Rectangle getFullBounds(int r, int c)
    {
        int x, y, width, height;
        if (c == 0) 
	{
            x = dataBounds[r][c].x - format.inset;
	    width = dataBounds[r][c].width + format.inset + format.inset - format.inset/2;
	}
	else 
	{
	    x = dataBounds[r][c].x - format.inset/2;
	    width = dataBounds[r][c].width + format.inset;
	}
	if (r == 0)
	{
            y = dataBounds[r][c].y - format.inset;
	    height = dataBounds[r][c].height + format.inset + format.inset - format.inset/2;
	}
	else
	{
	    y = dataBounds[r][c].y - format.inset/2;
	    height = dataBounds[r][c].height + format.inset;
	}
        return new Rectangle(x,y,width,height);
    }

    public void mouseClicked(MouseEvent e)
    {
    }

    public void mousePressed(MouseEvent e)
    {
        int x = e.getX(), y = e.getY();
        for (int r=0; r<format.numRows; r++) 
        {
            for (int c=0; c<format.numCols; c++)
	    {
		if (dataBounds[r][c].contains(x,y)) 
		{
                    fireMIScheduleEvent(new MIScheduleEvent(this, new Point(c,r), MIScheduleEvent.CELL_CLICKED));
                    return;
                }
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

    public static int numLinesInString(String s)
    {
        int lines = 0;
        int index = 0;
        do
	{
	    index = s.indexOf('\n',index+1);
            lines++;
	} while (index != -1);
        return lines;
    }

    public static String[] getLinesFromString(String s)
    {
        String ret[] = new String[numLinesInString(s)];
       
        int index = 0;
        int arrayPos = 0;
        index = s.indexOf('\n');
	while (index != -1)
	{
  	    ret[arrayPos++] = s.substring(0,index);
            s = s.substring(index+1);
            index = s.indexOf('\n');
	}
        ret[arrayPos] = s;
        return ret;
    }
}
