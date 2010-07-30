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

public class WeekView extends MISchedulePanel implements MouseMotionListener, MouseListener
{
    private int colWidth;
    private int rowHeight;

    private TimeHolder timeHolder;
    private Color colors[];
    private int timeValues[];
    private int cursor;

    private int lastCellX = -1;
    private int lastCellY = -1;

    private int sizeX;
    private int sizeY;
    private Applet applet;
    private boolean paintCellOnly = false;
    private boolean firstPaint = true;      

    // @@ dispose of this later
    private Image offScreen;
    private Graphics offScreenGraphics;

    private int startHH;
    private int endHH;
    
    private int colHeaderHeight;
    private int rowHeaderWidth;

    private boolean active=false;

    public WeekView(Applet pApplet, TimeHolder pTimeHolder, int pStartHH, int pEndHH)
    {
        startHH = pStartHH;
        endHH = pEndHH;
        cursor = 1;
        applet = pApplet;
        timeHolder = pTimeHolder;
        colors = timeHolder.getColors();
        timeValues = timeHolder.getTimeValues();
        addMouseListener(this);
        addMouseMotionListener(this);
    }

    public void setActive(boolean pActive)
    {
	active = pActive;
    }

    public void refreshValues()
    {
        timeValues = timeHolder.getTimeValues();
        repaint();
    }

    public void setCursorColor(int c)
    {
        cursor = c;
        lastCellY = -1;
        lastCellX = -1;
    }

    public void paint(Graphics screen)
    {
        if (firstPaint)
        {
            // we can change these later, depending on font metrics
            colHeaderHeight = 20;
            rowHeaderWidth = 60;
            
            rowHeight = bounds.height / (endHH - startHH);
            colWidth = (bounds.width - rowHeaderWidth - 1) / 5;
            sizeX = 1 + colWidth * 5 + rowHeaderWidth;
            sizeY = (endHH - startHH ) * rowHeight + colHeaderHeight + 1; 
            offScreen = applet.createImage(sizeX, sizeY);
            offScreenGraphics = offScreen.getGraphics();
            firstPaint = false;
        }
        if (paintCellOnly)
        {
            offScreenGraphics.setColor(colors[cursor]);    
            offScreenGraphics.fillRect(
                                       lastCellX*colWidth+rowHeaderWidth, 
                                       lastCellY*rowHeight+colHeaderHeight,
                                       colWidth, 
                                       rowHeight);
            offScreenGraphics.setColor(Color.black);
            offScreenGraphics.drawRect(
                                       lastCellX*colWidth+rowHeaderWidth, 
                                       lastCellY*rowHeight+colHeaderHeight, 
                                       colWidth, 
                                       rowHeight);
            paintCellOnly = false;
            screen.drawImage(offScreen, 0,0, applet);        
            return;
        }

        offScreenGraphics.setColor(getBackground());
        offScreenGraphics.fillRect(0,0,sizeX,sizeY);
        offScreenGraphics.setColor(Color.black);
        offScreenGraphics.drawString("M", rowHeaderWidth, 10);
        offScreenGraphics.drawString("Tu", rowHeaderWidth + colWidth, 10);
        offScreenGraphics.drawString("W", rowHeaderWidth + colWidth*2, 10);
        offScreenGraphics.drawString("Th", rowHeaderWidth + colWidth*3, 10);
        offScreenGraphics.drawString("F", rowHeaderWidth  + colWidth*4, 10);



        for (int c=0; c<5; c++)
        {
            for (int hh = startHH; hh < endHH; hh++)
            {
                if (c==0 && hh%2 == 0)
                {
                    offScreenGraphics.setColor(new Color(0,0,0));
                    offScreenGraphics.drawLine(
                                               rowHeaderWidth - 5, 
                                               (hh-startHH)*rowHeight+colHeaderHeight, 
                                               rowHeaderWidth, 
                                               (hh-startHH)*rowHeight+colHeaderHeight);
                }
            
                int index = c*48 + hh;
                offScreenGraphics.setColor( colors[ timeValues[index] ]);
                offScreenGraphics.fillRect(
                                           c*colWidth+rowHeaderWidth, 
                                           (hh-startHH)*rowHeight+colHeaderHeight, 
                                           colWidth, 
                                           rowHeight);
                offScreenGraphics.setColor(new Color(0,0,0));
                offScreenGraphics.drawRect(
                                           c*colWidth+rowHeaderWidth, 
                                           (hh-startHH)*rowHeight+colHeaderHeight, 
                                           colWidth, 
                                           rowHeight);
            }
        }
    
        for (int hh=startHH; hh<endHH; hh++)
        {
            if (hh % 2 == 0)
            {
                String timeString = timeHolder.getTimeStringFromHH( hh );
                offScreenGraphics.drawString(timeString, 0, (hh-startHH)*rowHeight + 5 + colHeaderHeight);
            }
        }
        
        screen.drawImage(offScreen, 0, 0, applet);
    }

    public void mouseDragged(MouseEvent e)
    {
	if (!active) return;
        paintCell(e);
    }

    public void mouseMoved(MouseEvent e)
    {
    }

    public void mouseClicked(MouseEvent e)
    {
	if (!active) return;
        paintCell(e);
    }

    public void mousePressed(MouseEvent e)
    {
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

    private void paintCell(MouseEvent e)
    {
        int x = (e.getX() - rowHeaderWidth) / colWidth;
        int y = (e.getY() - colHeaderHeight) / rowHeight;

        if (x < 0 || y < 0) return;
        if (x >= 5) return;
        if (y >= endHH - startHH) return;
        if (x == lastCellX && y == lastCellY) return;

        lastCellX = x;
        lastCellY = y;

        paintCellOnly = true;
        int index = x*48 + y + startHH;
        timeValues[index] = cursor;
        repaint();
    }
}

