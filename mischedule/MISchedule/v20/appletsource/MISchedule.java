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
import java.applet.*;
import java.net.*;
import java.util.*;

public class MISchedule extends Applet implements MIScheduleListener
{
    private Model model;
    private View view;
    private ComponentFactory factory;

    private int numToRetrieve;
    private int retrieved;
    private Vector toRetrieve;
    private int scheduleMode;
    private final static int BUILD_SCHEDULES = 0;
    private final static int DISPLAY_SECTIONS = 1;

    // happens only once, at the very beginning
    public void init()
    {
        System.out.println("..................applet starting............................");
        model = new Model(getParameter("term"), getParameter("request"));

	factory = new ComponentFactory(this, model);
        view = factory.getView();
	setBackground(Color.white);
	setLayout(null);
	add(view);
        getInitialDataFromServer();
    }

    public void getInitialDataFromServer()
    {
        // when the division list is ready, an MIScheduleEvent should be fired
        // and the division list will appear in our applet.
        DivisionListPanel d = factory.getDivisionListPanel();
        d.setLoading(true);
	model.requestDivisionList(this);
    }

    // happens at first, when called directly, or when the 
    // user returns to the page
    //public void start()
    //{
    //}

    // happens when the user leaves the page, or when
    // called directly
    //public void stop()
    //{
    //}

    public View getView()
    { 
        return view;
    }

    public Model getModel()
    {
        return model;
    }


    public void requestFinished( MIScheduleEvent e )
    {
        MainFrame m;
	switch ( e.getType() )
	{
	case MIScheduleEvent.GOT_DIVISION_LIST:
            handleGotDivisionList((DivisionList) e.getData());
	    break;
        case MIScheduleEvent.GOT_COURSE_LIST:
            handleGotCourseList((CourseList) e.getData());
	    break;    
        case MIScheduleEvent.DIVISION_SELECTED:
            handleDivisionSelected((String) e.getData(), e.getSource());
            break;
        case MIScheduleEvent.COURSE_SELECTED:
            handleCourseSelectedFromList((String) e.getData(), e.getSource());
            break;
        case MIScheduleEvent.GOT_BOOKS:
            handleGotBooks();
            break;
        case MIScheduleEvent.GOT_SECTION_LIST:
            handleGotSectionList((SectionList) e.getData());
            break;
        case MIScheduleEvent.BOOK_CLICKED:
            handleBookClicked( (String) e.getData());
            break;
        case MIScheduleEvent.TITLE_BAR_CLICKED:
            m = factory.getMainFrame();
            String data = (String) e.getData();
	    if (m.getCard() == MainFrame.CLASSES) 
	    {
                ClassesInputPanel cip = factory.getClassesInputPanel();
                cip.saveSelectionsToModel(); 
	    }
            else if (m.getCard() == MainFrame.SCHEDULE_FETCH)
	    {
		// We don't want the user just clicking around all willy-nilly
                factory.getCantLeave().show();
		break;
	    }

            if ( data == MainFrame.SECTIONS )
	    {
		scheduleMode = DISPLAY_SECTIONS;
		retrieveSections();
	    }           
            else if ( data == MainFrame.SCHEDULE_FETCH )
	    {
                scheduleMode = BUILD_SCHEDULES;
	    	retrieveSections();
            }
            else m.setCard( (String) e.getData() );
	    break;
        case MIScheduleEvent.TIMES_CUSTOMIZE_CLICKED:
            WeekView w = factory.getWeekView();
            w.setCursorColor( ( (Integer) e.getData() ).intValue() );
            break;
        case MIScheduleEvent.CELL_CLICKED:
	    handleCellClicked(e.getSource(), (Point) e.getData());
	    break;
        case MIScheduleEvent.FINISHED_BUILDING_SCHEDULES:
            m = factory.getMainFrame();
            SchedulesDisplayPanel sdp = factory.getSchedulesDisplayPanel();
            sdp.setData( (Vector) e.getData() );
	    model.setBestSchedules( (Vector) e.getData() );
            m.setCard( MainFrame.SCHEDULE_DISPLAY );
            break;
	case MIScheduleEvent.SET_TIME_PROFILE:
	    handleSetTimeProfile(e);
	    break;
        case MIScheduleEvent.DONE_FETCHING_SECTIONS:
            handleDoneFetchingSections();
	    break;
	case MIScheduleEvent.SCROLL_TO:
	    handleScrollTo(e);
	    break;
	case MIScheduleEvent.SCHEDULE_STATUS:
	    handleScheduleStatus(e);
	    break;
        case MIScheduleEvent.PRINT_SECTIONS:
            handlePrintSections(e);
 	    break;
        case MIScheduleEvent.PRINT_SCHEDULES:
            handlePrintSchedules(e);
	    break;
	}
    }

    public void handleCellClicked(Object source, Point data)
    {
        if (source == factory.getTopBarTable())
	{
            DisplaySectionsPanel dsp = factory.getDisplaySectionsPanel();
            dsp.setActiveSection(data.x);
	    factory.getDisplaySectionsPanelScroll().doLayout();
            return;
        }

        if (source == factory.getTopBarTableForSchedules())
	{
            SchedulesDisplayPanel sdp = factory.getSchedulesDisplayPanel();
            sdp.setActiveSchedule(data.x);
	    factory.getSchedulesDisplayScroll().doLayout();
            return;
	}       

        if (source == factory.getScheduleModeTable())
	{
            SchedulesDisplayPanel sdp = factory.getSchedulesDisplayPanel();
	    if (data.x == 0)
                sdp.setMode(SchedulesDisplayPanel.TABLE_MODE);
            else
	        sdp.setMode(SchedulesDisplayPanel.LIST_MODE);
	    factory.getSchedulesDisplayScroll().doLayout();
            return;
	}

    }

    public void retrieveSections()
    {
        model.sectionPackage.removeAllElements();
        numToRetrieve = model.getNumFilledSlots();
        retrieved = 0;
        toRetrieve = new Vector(10,10);

        Slot slot = new Slot(); 
        int i = model.getNextFilledSlot(0,slot);
        while (i != -1)
	{
	    toRetrieve.addElement(slot);
            slot = new Slot();
	    i = model.getNextFilledSlot(i+1,slot);
	}
        SchedulesFetchPanel sfp = factory.getSchedulesFetchPanel();
        sfp.setSlotsToRetrieve(toRetrieve);
        sfp.repaint();
        factory.getMainFrame().setCard( MainFrame.SCHEDULE_FETCH );
        for (i=0; i<toRetrieve.size(); i++)
	{
	    slot = (Slot) toRetrieve.elementAt(i);
            model.requestSectionList(this, slot.division, slot.course);
	}     
    }

    public void handleGotSectionList(SectionList sectionList)
    {
        retrieved++;
        System.out.println("Got section " + retrieved + " of " + numToRetrieve);
        SchedulesFetchPanel sfp = factory.getSchedulesFetchPanel();
        if (sectionList.getNumSections() > 0)
	{
	    model.sectionPackage.addElement(sectionList);
	    sfp.setRetrieved( sectionList.getDivision(), sectionList.getCourse(), true );
	}
	else 
	{
	    sfp.setRetrieved( sectionList.getDivision(), sectionList.getCourse(), false );
	}
        if ( numToRetrieve == retrieved )
	{
            sfp.allRetrieved();
	}
    }

    private void handleDoneFetchingSections()
    {
        System.out.println("In DoneFetchingSections");

        if (model.sectionPackage.size() == 0)
	{
	    MainFrame m = factory.getMainFrame();
	    m.setCard(MainFrame.CLASSES);
	    return;
	}

	// we got them all
        if (scheduleMode == DISPLAY_SECTIONS)
	{
            DisplaySectionsPanel dsp = factory.getDisplaySectionsPanel();
            dsp.setActiveSection(0);
            dsp.reset();
            MainFrame m = factory.getMainFrame();
            m.setCard(MainFrame.SECTIONS);
        }
        else if (scheduleMode == BUILD_SCHEDULES)
	{
            ScheduleThread s = new ScheduleThread(model.sectionPackage, model.getTimeHolder().getTimeValues());
            s.addMIScheduleListener(this);
            s.start();
        }
    }

    private void handleGotDivisionList(DivisionList divisionList)
    {
        DivisionListPanel divisionListPanel = factory.getDivisionListPanel();
	divisionListPanel.setDivisionList(divisionList);
        divisionListPanel.setLoading(false);
        ScrollPane divisionListScroll = factory.getDivisionListScroll();
        divisionListScroll.doLayout();
    }

    private void handleGotCourseList(CourseList courseList)
    {
      	CourseListPanel courseListPanel = factory.getCourseListPanel();
	courseListPanel.setCourseList(courseList);
	courseListPanel.setLoading(false);

	// Set the active row
        ClassesInputPanel classesInputPanel = factory.getClassesInputPanel();
	Slot s = model.getSlotAt( classesInputPanel.getSelectedSlot() );
	courseListPanel.setActiveRow(s.course);

        ScrollPane courseListScroll = factory.getCourseListScroll();
        courseListScroll.doLayout();
    }
	
    private void handleDivisionSelected(String division, Object source)
    {
        boolean requestList=true;

        // If we switched to a blank division
        if (division.length() == 0)
	{
            requestList = false;
	}

        ClassesInputPanel classesInputPanel = factory.getClassesInputPanel();

        // If the user entered an invalid division
        if ( requestList == true && source == classesInputPanel )  
	{
	    if ( !model.isDivisionValid( division) ) requestList = false;
	}

        if ( requestList )
        {
            CourseListPanel courseListPanel = factory.getCourseListPanel();
	    courseListPanel.setLoading(true);
            model.requestCourseList(this, division);        
	}
	else
	{
            CourseListPanel courseListPanel = factory.getCourseListPanel();
	    courseListPanel.setCourseList(new CourseList(model.getReader(), ""));
	    ScrollPane courseListScroll = factory.getCourseListScroll();
	    courseListScroll.doLayout();           
	}

	if (source == classesInputPanel) 
	{
	    DivisionListPanel divisionListPanel = factory.getDivisionListPanel();
	    divisionListPanel.setActiveRow(division);
        }
	else
	{
	    classesInputPanel.setDivision(division);       
	}
    }


    private void handleCourseSelectedFromList(String course, Object source)
    {
        // set the course in the ClassesInputPanel and in the model
        ClassesInputPanel classesInputPanel = factory.getClassesInputPanel();

	if (source != classesInputPanel)
	    classesInputPanel.setCourse(course);

	Slot s = model.getSlotAt( classesInputPanel.getSelectedSlot() );
        //model.requestBooks(this, s.division, course);        
    }

    private void handleGotBooks()
    {
	/*
        BooksPanel bp = factory.getBooksPanel();
        bp.paintCurrentBooks( model.getBooksForUsed() );
        ScrollPane sp = factory.getBooksPanelScroll();
        sp.doLayout();
	*/
    }

    private void handleBookClicked(String ISBN)
    {
	String s = getCodeBase() + "../php/amazon_window.php?ISBN="+ISBN;

	try 
	{
            System.out.println(s);
	    getAppletContext().showDocument( new URL(s), "amazon" );
	}
	catch (MalformedURLException e)
	{
	}
    }

    private void handleScrollTo(MIScheduleEvent e)
    {
	if ( e.getSource() == factory.getDivisionListPanel() )
	{
	    ScrollPaneContainer s = factory.getDivisionListScroll();
	    s.setScrollPosition( (Point) e.getData() );
	}
    }

    private void handleSetTimeProfile(MIScheduleEvent e)
    {
        TimeHolder th = model.getTimeHolder();
        String data = (String) e.getData();
	th.setActiveProfile(data);
	TimesCustomize tc = factory.getTimesCustomize();
	WeekView w = factory.getWeekView();
	if (data == TimeProfile.CUSTOM_STRING)
	{
	    tc.setActive(true);
	    w.setActive(true);
	}
	else
	{
	    tc.setActive(false);
	    w.setActive(false);
	}

        w.refreshValues();          
    }

    private void handleScheduleStatus(MIScheduleEvent e)
    {
	SchedulesFetchPanel sfp = factory.getSchedulesFetchPanel();
	sfp.setNodesExamined( ((Integer) e.getData()).intValue() );
    }

    private void handlePrintSections(MIScheduleEvent e)
    {
        int data = ((Integer) e.getData()).intValue();
        String url = getCodeBase() + "../php/print.php?command=printsections&term=" + model.getTerm() + "&data=";
        int activeSection = -1;
        if (data == PrintSections.PRINT_CURRENT)
	{
  	    DisplaySectionsPanel dsp = factory.getDisplaySectionsPanel();
            activeSection = dsp.getActiveSection();      
        }
        Slot slot = new Slot(); 
        int i = model.getNextFilledSlot(0,slot);
        int j = 0;
	while (i != -1)
	{
            if ( data == PrintSections.PRINT_ALL || j == activeSection)
	    {
                if ( j != 0 ) url += ";";
                url += slot.division + ":" + slot.course;
	    }
            j++;
	    slot = new Slot();
	    i = model.getNextFilledSlot(i+1,slot);
	}
        try 
	{
            getAppletContext().showDocument( new URL(url), "printsections" );
	}
        catch (Exception ex)
	{
            System.out.println("An exception occurred while trying to launch print sections window");
	}
    }

    private void handlePrintSchedules(MIScheduleEvent e)
    {
	int data = ((Integer) e.getData()).intValue();
        String url = getCodeBase() + "../php/print.php?command=printschedules&term=" + model.getTerm();
	if (data == PrintSchedules.PRINT_BOTH)
	    url += "&table=1&list=1";
	else if (data == PrintSchedules.PRINT_TABLE)
    	    url += "&table=1&list=0";
	else
	    url += "&table=0&list=1";

	url += "&data=";
	
	SchedulesDisplayPanel sdp = factory.getSchedulesDisplayPanel();
        Vector skeds = model.getBestSchedules();
	Schedule sked = (Schedule) skeds.elementAt( sdp.getActiveSchedule() );
	for (int i=0; i<sked.getNumSections(); i++)
	{
	    Section sect = sked.getSectionAt(i);
	    if (i!=0) url+= ";";
	    url += sect.classNum;
	}
        try 
	{
            getAppletContext().showDocument( new URL(url), "printschedules" );
	}
        catch (Exception ex)
	{
            System.out.println("An exception occurred while trying to launch print schedules window");
	}
    }
	 
}
