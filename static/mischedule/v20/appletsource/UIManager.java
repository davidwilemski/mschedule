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

/* This class should manage all aspects of the colors,
   sizes, and other style elements of all the components.
   If I had an IDE than I wouldn't need this blasted class!
*/

public class UIManager
{
    private final static int TOTAL_WIDTH = 600;
    private final static int TOTAL_HEIGHT = 500;
    private final static int INSET = 2;
    private final static int TITLE_BAR_HEIGHT = 60;
    private final static int MAIN_FRAME_WIDTH = 600;
    private final static int MAIN_FRAME_X = INSET;
    private final static int MAIN_FRAME_Y = INSET + TITLE_BAR_HEIGHT + INSET;
    private final static int MAIN_FRAME_HEIGHT = TOTAL_HEIGHT - TITLE_BAR_HEIGHT - INSET - INSET - INSET;
    private final static int CP_HEADER_HEIGHT = 20;
    private final static int CP_INSET = 5;
    private final static int WEEK_VIEW_WIDTH = 325;

    private Font defaultFont;

    public UIManager()
    {
	defaultFont = new Font("Times", Font.PLAIN, 12);
    }

    public void initializeUI(View c)
    {
        c.bounds = new Rectangle(0,0,TOTAL_WIDTH,TOTAL_HEIGHT);
	c.setBounds( c.bounds );
    }

    public void initializeUI(MainFrame c)
    {
        c.bounds = new Rectangle(   MAIN_FRAME_X,
                                    MAIN_FRAME_Y,
                                    MAIN_FRAME_WIDTH,
                                    MAIN_FRAME_HEIGHT);
        c.setBounds( c.bounds );                        
        c.setBackground(Color.white);
        c.borderColor = Color.black;
    }

    public void initializeUI(TitleBar c)
    {
        c.bounds = new Rectangle(   INSET,
                                    INSET,
                                    TOTAL_WIDTH - INSET - INSET, 
                                    TITLE_BAR_HEIGHT);
        c.setBounds( c.bounds );                        
        c.setBackground(Color.white);
        c.borderColor = Color.black;
    }

    /*public void initializeUI(BooksPanel c)
    {
        c.bounds = new Rectangle(   INSET + MAIN_FRAME_WIDTH + INSET,
                                    INSET + TITLE_BAR_HEIGHT + INSET,
                                    TOTAL_WIDTH - MAIN_FRAME_WIDTH - INSET - INSET - INSET, 
                                    TOTAL_HEIGHT - TITLE_BAR_HEIGHT - INSET - INSET - INSET);
        c.setBackground(Color.white);
        c.borderColor = Color.black;
     }*/

    public void initializeUI(ClassesPanel c)
    {
        c.bounds = new Rectangle(   MAIN_FRAME_X,
                                    MAIN_FRAME_Y,
                                    MAIN_FRAME_WIDTH,
                                    MAIN_FRAME_HEIGHT);
        c.setBounds( c.bounds );                        
        c.setBackground(new Color(224,224,255));
        c.borderColor = Color.black;
	c.setFont(defaultFont);
        c.divListHeaderText = "Select Division";
        c.courseListHeaderText = "Select Course";
        c.listHeaderBackColor = new Color(0,0,51);
        c.listHeaderForeColor = new Color(255,255,0);
	c.divListHeaderBounds = new Rectangle(275,CP_INSET,310,CP_HEADER_HEIGHT);
	c.courseListHeaderBounds = new Rectangle(275,220,310,CP_HEADER_HEIGHT);
    }

    public void initializeUI(ClassesInputPanel c)
    {
        c.bounds = new Rectangle(   CP_INSET,
                                    CP_INSET,
                                    250,
                                    280);
        c.setBounds( c.bounds );                        
        c.setBackground(new Color(224,224,255));
        c.borderColor = Color.black;
	c.setFont(defaultFont);
        c.rowLabelWidth = 10;
        c.divTextBoxWidth = 100;
        c.courseTextBoxWidth = 60;
        c.insetSize = 10;
        c.rowHeight = 20;
        c.headerBackColor = new Color(0,0,51);
        c.headerForeColor = new Color(255,255,0);
        c.headerHeight = 20;
        c.headerText1 = "Division";
        c.headerText2 = "Course";
        c.rowSelectorColor = new Color(0,0,255);  
    }

    public void initializeUI(TimesPanel c)
    {
        c.bounds = new Rectangle(   MAIN_FRAME_X,
                                    MAIN_FRAME_Y,
                                    MAIN_FRAME_WIDTH,
                                    MAIN_FRAME_HEIGHT);
        c.setBounds( c.bounds );   
        c.setBackground(new Color(224,224,255));                                                     
        c.borderColor = Color.black;
    }

    public void initializeUI(SchedulesFetchPanel c)
    {
        c.bounds = new Rectangle(   MAIN_FRAME_X,
                                    MAIN_FRAME_Y,
                                    MAIN_FRAME_WIDTH,
                                    MAIN_FRAME_HEIGHT);
        c.setBounds( c.bounds );
        c.setBackground(new Color(224,224,255));                                                     
        c.setBackground(Color.white);
        c.borderColor = Color.black;
    }

    public void initializeUI(SchedulesDisplayPanel c)
    {
        c.bounds = new Rectangle(   MAIN_FRAME_X,
                                    MAIN_FRAME_Y,
                                    MAIN_FRAME_WIDTH,
                                    MAIN_FRAME_HEIGHT);
        c.setBackground(new Color(224,224,255));
        c.borderColor = Color.black;
    }


    public void initializeUI(DivisionListPanel c)
    {
	c.bounds = new Rectangle ( 275,
                                   CP_INSET + CP_HEADER_HEIGHT,
                                   310,
                                   190);
        c.setBackground(Color.white);
        c.borderColor = Color.black;
    }

    public void initializeUI(CourseListPanel c)
    {
        c.bounds = new Rectangle (  275,
                                    220 + CP_HEADER_HEIGHT,
                                    310,
                                    190);              
        c.setBackground(Color.white);
        c.borderColor = Color.black;
    }

    public void initializeUI(WeekView c)
    {
        
        c.bounds = new Rectangle(   INSET,
                                    INSET,
                                    WEEK_VIEW_WIDTH,
                                    MAIN_FRAME_HEIGHT - 2*INSET);              
        c.setBounds( c.bounds );     
        c.setBackground(new Color(224,224,255));
        c.borderColor = Color.black;
    }

    public void initializeUI(TimesCustomize c)
    {
        c.bounds = new Rectangle(   WEEK_VIEW_WIDTH + INSET*2,
                                    231,
                                    260,
                                    150);              
        c.setBounds( c.bounds );                                
        c.setBackground(Color.white);
        c.borderColor = Color.black;
    }

    public void initializeUI(TimesCategory c)
    {
        c.bounds = new Rectangle(   WEEK_VIEW_WIDTH + INSET*2,
                                    21,
                                    260,
                                    200);              
        c.setBounds( c.bounds );                                
        c.setBackground(Color.white);
        c.borderColor = Color.black;
    }

    public void initializeUI(DisplaySectionsPanel c)
    {
        c.bounds = new Rectangle(   MAIN_FRAME_X,
                                    MAIN_FRAME_Y,
                                    MAIN_FRAME_WIDTH,
                                    MAIN_FRAME_HEIGHT);
        c.setBackground(new Color(224,224,255));
        c.borderColor = Color.black;
    }
}
