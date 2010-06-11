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
import java.net.*;

public class Spider extends Applet implements MIScheduleListener, ActionListener
{
    MIScheduleReader reader;
    DivisionList divisionList;
    Button goButton;
    List startList;
    List endList;
    TextField numThreads;
    Checkbox getSections;

    int nMade = 0;
    int nReceived = 0;
    int nErrors = 0;
    String sStatus = "Ready";
 
    // happens only once, at the very beginning
    public void init()
    {
        System.out.println("..................applet starting............................");
        Model model = new Model(getParameter("term"), getParameter("request"));
        reader = model.getReader();
         
        setLayout(null);
        Label l = new Label("Starting Division");
	l.setBounds(100,100,100,20);
        add(l);
        
        l = new Label("End Division");
        l.setBounds(200,100,100,20);
        add(l);

        l = new Label("Num Threads");
        l.setBounds(350,150,100,20);
        add(l);

        l = new Label("Get Sections?");
        l.setBounds(350,200,100,20);
        add(l);

        numThreads = new TextField();
        numThreads.setBounds(450,150,50,20);
        add(numThreads);

        getSections = new Checkbox();
        getSections.setBounds(450,200,20,20);
        add(getSections);

        goButton = new Button("Go");
        goButton.addActionListener(this);
        goButton.setBounds(350,300,50,20);
        add(goButton);

        l = new Label("Requests Made");
        l.setBounds(100,350,120,20);
        add(l);

        l = new Label("Requests Received");
        l.setBounds(100,370,120,20);
        add(l);

        l = new Label("Errors");
        l.setBounds(100,390,120,20);
        add(l);

        l = new Label("Status");
        l.setBounds(100,410,120,20);
        add(l);


        divisionList = new DivisionList( reader );
        divisionList.addMIScheduleListener(this);
        divisionList.start();
    }

    public void paint(Graphics screen)
    {
        screen.drawString(String.valueOf(nMade), 220, 362);
        screen.drawString(String.valueOf(nReceived), 220, 382);
        screen.drawString(String.valueOf(nErrors), 220, 402);
        screen.drawString(String.valueOf(sStatus), 220, 422);

    }
 


    public void requestFinished( MIScheduleEvent e )
    {
        MainFrame m;
	switch ( e.getType() )
	{
	case MIScheduleEvent.GOT_DIVISION_LIST:
            DivisionList d = (DivisionList) e.getData();
            startList = new List(10);
            endList = new List(10);
            int n = d.getNumDivisions();
            for (int i=0; i<n; i++)
	    {
                Division div = d.getDivisionAt(i); 
                startList.add(div.abbrev);
                endList.add(div.abbrev);
            }
            startList.setBounds(100,120,100,200);
            endList.setBounds(200,120,100,200);
            add(startList);
            add(endList);
            startList.select(0);
            endList.select(0);
            break;
	}
    }

    public void actionPerformed(ActionEvent e)
    {
        if (e.getSource() == goButton)
	{  
            nMade = 0;
            nReceived = 0;
            nErrors = 0;
            sStatus = "Ready";

            String startDiv = startList.getSelectedItem();
            String endDiv = endList.getSelectedItem(); 
            boolean sections = getSections.getState();
            int threads = new Integer(numThreads.getText()).intValue();
            SpiderThread t = new SpiderThread(this,reader,divisionList,startDiv,endDiv,sections,threads);
            t.start();
	}
    }
}
