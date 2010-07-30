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

/* The ComponentFactory class should initialize the appearance 
   of all the UI components */


public class ComponentFactory
{
    private ClassesInputPanel classesInputPanel = null;
    private DivisionListPanel divisionListPanel = null;
    private ScrollPaneContainer divisionListScroll = null;
    private CourseListPanel courseListPanel = null;
    private ScrollPaneContainer courseListScroll = null;
    private ClassesPanel classesPanel = null;
    private TimesPanel timesPanel = null;
    private SchedulesFetchPanel schedulesFetchPanel = null;
    private SchedulesDisplayPanel schedulesDisplayPanel = null;
    private ScrollPaneContainer schedulesDisplayPanelScroll = null;
    //private BooksPanel booksPanel = null;
    private MainFrame mainFrame = null;
    private TitleBar titleBar = null;
    private WeekView weekView = null;
    private TimesCustomize timesCustomize = null;
    private TimesCategory timesCategory = null;
    private ScrollPaneContainer booksPanelScroll = null;
    private DisplaySectionsPanel displaySectionsPanel = null;
    private ScrollPaneContainer displaySectionsPanelScroll = null;
    private TableData topBarTable = null;
    private TableData sectionDataTable = null;
    private TableData topBarTableSchedules = null;
    private TableData scheduleDataTable = null;
    private TableData scheduleModeTable = null;
    private TableData scheduleListTable = null;
    private PrintSections printSections = null;
    private PrintSchedules printSchedules = null;
    private MessageBox cantLeave = null;
    
    private View view = null;
    private MISchedule controller = null;
    private Model model = null;
    private UIManager uiManager;

    public ComponentFactory(MISchedule pController, Model pModel)
    {
        controller = pController;
	model = pModel;
        uiManager = new UIManager();
    }

    public View getView()
    {
	if (view == null)
	{
            view = new View(this);
            uiManager.initializeUI(view);
        }
        return view;
    }

    public ClassesInputPanel getClassesInputPanel()
    {
        if (classesInputPanel == null)
	{
            classesInputPanel = new ClassesInputPanel( model );
            uiManager.initializeUI(classesInputPanel);
            classesInputPanel.addMIScheduleListener(controller);
        }
	return classesInputPanel;
    }

    public DivisionListPanel getDivisionListPanel()
    {
        if (divisionListPanel == null)
	{
            divisionListPanel = new DivisionListPanel();
            divisionListPanel.addMIScheduleListener(controller);
            uiManager.initializeUI(divisionListPanel); 
        }
	return divisionListPanel;
    }

    public CourseListPanel getCourseListPanel()
    {
        if (courseListPanel == null)
	{
            courseListPanel = new CourseListPanel();
            courseListPanel.addMIScheduleListener(controller);
            uiManager.initializeUI(courseListPanel);
        }
	return courseListPanel;
    }

    public ClassesPanel getClassesPanel()
    {
        if (classesPanel == null)
	{
            classesPanel = new ClassesPanel(this);
            classesPanel.addMIScheduleListener(controller);
            uiManager.initializeUI(classesPanel);
	}
        return classesPanel;
    }

    public TimesPanel getTimesPanel()
    {
        if (timesPanel == null)
	{
            timesPanel = new TimesPanel(this, model.getTimeHolder());
            uiManager.initializeUI(timesPanel);
	}
        return timesPanel;
    }

    public SchedulesFetchPanel getSchedulesFetchPanel()
    {
        if (schedulesFetchPanel == null)
	{
            schedulesFetchPanel = new SchedulesFetchPanel();
            schedulesFetchPanel.addMIScheduleListener(controller);
            uiManager.initializeUI(schedulesFetchPanel);
	}
        return schedulesFetchPanel;
    }

    public SchedulesDisplayPanel getSchedulesDisplayPanel()
    {
        if (schedulesDisplayPanel == null)
	{
            schedulesDisplayPanel = new SchedulesDisplayPanel(this);
            uiManager.initializeUI(schedulesDisplayPanel);
	}
        return schedulesDisplayPanel;
    }

   
    public MainFrame getMainFrame()
    {
        if (mainFrame == null)
	{
            mainFrame = new MainFrame(this);
            uiManager.initializeUI(mainFrame);
	}
        return mainFrame;
    }

    public TitleBar getTitleBar()
    {
        if (titleBar == null)
	{
            titleBar = new TitleBar( controller );
            titleBar.addMIScheduleListener( controller );
            uiManager.initializeUI( titleBar );
	}
        return titleBar;
    }

    /*public BooksPanel getBooksPanel()
    {
        if (booksPanel == null)
	{
            booksPanel = new BooksPanel(controller);
	    booksPanel.addMIScheduleListener(controller);
            uiManager.initializeUI(booksPanel);
	}
        return booksPanel;
    }
    */

    public WeekView getWeekView()
    {
        if (weekView == null)
	{
            weekView = new WeekView( controller, model.getTimeHolder(), 14, 48);
            uiManager.initializeUI(weekView);
        }
        return weekView;
    }

    public TimesCustomize getTimesCustomize()
    {
        if (timesCustomize == null)
	{
            timesCustomize = new TimesCustomize( model.getTimeHolder());
            timesCustomize.addMIScheduleListener(controller);
            uiManager.initializeUI(timesCustomize);
        }
        return timesCustomize;
    }

    public TimesCategory getTimesCategory()
    {
        if (timesCategory == null)
	{
            timesCategory = new TimesCategory( model.getTimeHolder());
            timesCategory.addMIScheduleListener(controller);
            uiManager.initializeUI(timesCategory);
        }
        return timesCategory;
    }

    public ScrollPaneContainer getDivisionListScroll()
    {
        if ( divisionListScroll == null )
	{
            DivisionListPanel dlp = getDivisionListPanel();
            divisionListScroll = new ScrollPaneContainer( dlp );
            divisionListScroll.setBounds( dlp.bounds );
	}
        return divisionListScroll;
    }

    public ScrollPaneContainer getCourseListScroll()
    {
        if ( courseListScroll == null )
	{
            CourseListPanel clp = getCourseListPanel();
            courseListScroll = new ScrollPaneContainer( clp );
 	    courseListScroll.setBounds( clp.bounds );
	}
        return courseListScroll;
    }

    /*
    public ScrollPaneContainer getBooksPanelScroll()
    {
        if ( booksPanelScroll == null )
	{
            BooksPanel bp = getBooksPanel();
            booksPanelScroll = new ScrollPaneContainer( bp );
            booksPanelScroll.setBounds( bp.bounds );
	}
        return booksPanelScroll;
    }
    */

    public DisplaySectionsPanel getDisplaySectionsPanel()
    {
        if ( displaySectionsPanel == null )
	{
            displaySectionsPanel = new DisplaySectionsPanel(this, model.sectionPackage);
            uiManager.initializeUI(displaySectionsPanel);
        }
        return displaySectionsPanel;
    }

    // Since I want to get rid of the UI Manager eventually, I'm not using it 
    // after this point
    public ScrollPaneContainer getDisplaySectionsPanelScroll()
    {
        if ( displaySectionsPanelScroll == null )
	{
            DisplaySectionsPanel dsp = getDisplaySectionsPanel();
            displaySectionsPanelScroll = new ScrollPaneContainer( dsp );
            displaySectionsPanelScroll.setBounds( dsp.bounds );
	}
        return displaySectionsPanelScroll;
    }

    public ScrollPaneContainer getSchedulesDisplayScroll()
    {
        if ( schedulesDisplayPanelScroll == null )
	{
            SchedulesDisplayPanel dsp = getSchedulesDisplayPanel();
            schedulesDisplayPanelScroll = new ScrollPaneContainer( dsp );
            schedulesDisplayPanelScroll.setBounds( dsp.bounds );
	}
        return schedulesDisplayPanelScroll;
    }

    public TableData getTopBarTable()
    {
        if ( topBarTable == null )
	{
            TableDataFormat topBarFormat = new TableDataFormat();
            topBarTable = new TableData(topBarFormat, true);
            topBarTable.addMIScheduleListener(controller);
        }
        return topBarTable;
    }

    public TableData getTopBarTableForSchedules()
    {
        if ( topBarTableSchedules == null )
	{
            TableDataFormat topBarFormat = new TableDataFormat();
            topBarTableSchedules = new TableData(topBarFormat, true);
            topBarTableSchedules.addMIScheduleListener(controller);
        }
        return topBarTableSchedules;
    }

    public TableData getSectionDataTable()
    {
        if ( sectionDataTable == null )
	{
	    TableDataFormat sectionDataFormat = new TableDataFormat();
            sectionDataTable = new TableData(sectionDataFormat, false);
        }
        return sectionDataTable;
    }

    public TableData getScheduleDataTable()
    {
        if ( scheduleDataTable == null )
	{
	    TableDataFormat scheduleDataFormat = new TableDataFormat();
            scheduleDataTable = new TableData(scheduleDataFormat, false);
        }
        return scheduleDataTable;
    }

    public TableData getScheduleModeTable()
    {
        if ( scheduleModeTable == null )
	{
	    TableDataFormat scheduleModeFormat = new TableDataFormat();
            scheduleModeTable = new TableData(scheduleModeFormat, true);
            scheduleModeTable.addMIScheduleListener(controller);
        }
        return scheduleModeTable;
    }

    public TableData getScheduleListTable()
    {
        if ( scheduleListTable == null )
	{
	    TableDataFormat scheduleListFormat = new TableDataFormat();
            scheduleListTable = new TableData(scheduleListFormat, false);
        }
        return scheduleListTable;
    }

    public PrintSections getPrintSections()
    {
        if ( printSections == null )
	{ 
            printSections = new PrintSections();
            printSections.addMIScheduleListener(controller);
	}
        return printSections;
    }
 
    public PrintSchedules getPrintSchedules()
    {
        if ( printSchedules == null )
	{ 
            printSchedules = new PrintSchedules();
            printSchedules.addMIScheduleListener(controller);
	}
        return printSchedules;
    }

    public MessageBox getCantLeave()
    {
        if ( cantLeave == null )
	{ 
            cantLeave = new MessageBox("Please wait until all sections/schedules are loaded before trying to exit this panel.", "Warning");
	}
        return cantLeave;
    }


 
}
