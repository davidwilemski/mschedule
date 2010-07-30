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

public class ClassesInputPanel extends MISchedulePanel implements FocusListener
{
    private TextField divisions[];
    private TextField courses[];
    private int selectedSlot;
    private int numSlots;
    private boolean firstPaint;


    // the UI sizes and colors set by the UIManager
    public int rowLabelWidth;
    public int divTextBoxWidth;
    public int courseTextBoxWidth;
    public int insetSize;
    public int rowHeight;
    public Color rowSelectorColor;
    public Color headerBackColor;
    public int headerHeight;
    public Color headerForeColor;
    public String headerText1;
    public String headerText2;

    private Model model;
    
    public ClassesInputPanel(Model pModel) 
    {
        model = pModel;
        numSlots = model.getNumSlots();
        selectedSlot = 0;
        firstPaint = true;
    }

    public void paint(Graphics screen)
    {
	if (firstPaint == true)
	{
    	    firstPaint = false;
            divisions = new TextField[numSlots];
            courses = new TextField[numSlots];
            for (int i=0; i<numSlots; i++)
            {
    	        Label l = new Label(new Integer(i+1).toString());
                divisions[i] = new TextField();
                courses[i] = new TextField();

        	divisions[i].addFocusListener(this);
                courses[i].addFocusListener(this);             

                l.setBounds( getRowLabelStart(), getRowStart(i), rowLabelWidth, rowHeight);
                divisions[i].setBounds( getDivTextBoxStart(), getRowStart(i), divTextBoxWidth, rowHeight);
                courses[i].setBounds( getCourseTextBoxStart(), getRowStart(i), courseTextBoxWidth, rowHeight);

                add(l);
                add(divisions[i]);
                add(courses[i]);
            }
        }

        super.paint(screen);

	// draw the header
        FontMetrics fm = screen.getFontMetrics();
        screen.setColor( headerBackColor );
        screen.fillRect(0, 0, bounds.width, headerHeight);
        Rectangle r;    
        screen.setColor( headerForeColor);
        r = new Rectangle( getDivTextBoxStart(), 0, divTextBoxWidth , headerHeight);
	putTextInRectangle(screen, r, headerText1, false);
	r = new Rectangle( getCourseTextBoxStart(), 0, courseTextBoxWidth, headerHeight);
	putTextInRectangle(screen, r, headerText2, false);

	// draw the box around the selected row
        screen.setColor( rowSelectorColor);
        screen.drawRect( insetSize/2, 
			 getRowStart(selectedSlot) - insetSize/2, 
                         bounds.width - insetSize, 
                         rowHeight + insetSize);

        // draw the border
        screen.setColor(borderColor);
        screen.drawRect(0,0,bounds.width-1,bounds.height-1);
    }

    //This only needs to be called because we could enter
    //text and then switch the card, and not get a focus lost
    //event
    public void saveSelectionsToModel()
    {
	focusLostOnDivision(selectedSlot);
	focusLostOnCourse(selectedSlot);
    }

    public int getSelectedSlot()
    {
        return selectedSlot;
    }

    public void setDivision(String s)
    {
	if (divisions[selectedSlot].getText().compareTo(s) != 0)
	{
	    divisions[selectedSlot].setText(s);
	    courses[selectedSlot].setText("");
	    model.getSlotAt(selectedSlot).division = s;
	    model.getSlotAt(selectedSlot).course = "";
	}
    }

    public void setCourse(String s)
    {
        courses[selectedSlot].setText(s);
        model.getSlotAt(selectedSlot).course = s;
    }

    public void focusGained(FocusEvent e)
    {
        for (int i=0; i<numSlots; i++)
        {
            if ( e.getSource() == divisions[i] || e.getSource() == courses[i] ) 
            {
		selectedSlot = i;
		repaint();
		// call this event which will refresh the course list
		fireMIScheduleEvent( new MIScheduleEvent( 
							 this, 
							 divisions[i].getText(),
							 MIScheduleEvent.DIVISION_SELECTED)  );
            }                   
        }
    }

    public void focusLost(FocusEvent e)
    {
        for (int i=0; i<numSlots; i++)
        {
	    Slot s = model.getSlotAt(i);
            if ( e.getSource() == divisions[i] ) focusLostOnDivision(i);
	    if ( e.getSource() == courses[i] ) focusLostOnCourse(i);
        }
    }

    private void focusLostOnCourse(int i)
    {
        Slot s = model.getSlotAt(i);
	if ( s.course.compareTo(courses[i].getText()) != 0 )
	{
	    // The course was edited by the user
	    s.course = courses[i].getText();
   	    // call this event which will get the books 
	    fireMIScheduleEvent( new MIScheduleEvent( 
						     this, 
						     courses[i].getText(),
						     MIScheduleEvent.COURSE_SELECTED)  );
	}	
	model.getSlotAt(i).course = courses[i].getText();
    }

    private void focusLostOnDivision(int i)
    {
        Slot s = model.getSlotAt(i);
	String text = divisions[i].getText().toUpperCase();
	if ( s.division.compareTo(text) != 0 )
	{
	    // The division was edited by the user
	    courses[i].setText("");
	    s.division = text;
	    s.course = courses[i].getText();
	}
	divisions[i].setText(text);
	System.out.println("Setting division to " + text);
	model.getSlotAt(i).division = text;
    }

    private int getRowStart(int row)
    {
        return headerHeight + insetSize + row*(rowHeight + insetSize);
    }

    private int getRowEnd(int row)
    {
        return ( getRowStart(row) + rowHeight );
    }

    private int getRowLabelStart()
    {
        return insetSize;
    }

    private int getDivTextBoxStart()
    {
        return ( getRowLabelStart() + insetSize + rowLabelWidth );
    }

    private int getCourseTextBoxStart()
    {
        return ( getDivTextBoxStart() + insetSize + divTextBoxWidth );
    }       
}
