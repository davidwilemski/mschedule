$('document').ready(function() 
{	
	//Initial content grab
	updateChangedPages();
	updateNewUsers();
	updateRecentUsers();

	var refresh = setInterval(function()
	{
		updateRecentUsers();
		updateChangedPages();
		updateNewUsers();
		
		
	}, 15000);
});

//Request for 'Changed Pages'
function updateChangedPages()
{
	var page_div = $("#updated_pages");

	$.ajax(
	{
	    url : 'data/updatedpages',
	
	    method : 'GET',
	
	    dataType : 'json',
	
	    success : function(json) 
	    {
	    	for(var page in json.pages)
			{
				if($('#' + json.pages[page].id).length == 0)
				{
					var div_content ="";
					div_content += "<div class='admin_entry' id='" + json.pages[page].id + "' ><p>";
					div_content += json.pages[page].title;
					div_content += "</p></div>";
					page_div.prepend(div_content);
				}
				else
				{
					//$('#' + json.pages[page].id).fadeOut('slow');
					//$('#' + json.new_users[user].userID).remove();
				}
			}
			
	        
	   	},
	
	    error : function(xhr, status) {
	        page_div.html("<p>There has been some trouble retrieving the data you have requested. I&rsquo;m feeling lazy, come back later.")
	    },
	});

}

//Request for 'New Users'
function updateNewUsers()
{
	var newusers_div = $("#new_users");

	$.ajax(
	{
	    url : 'data/newusers',
	
	    method : 'GET',
	
	    dataType : 'json',
	
	    success : function(json) 
	    {
			
			for(var user in json.new_users)
			{
			
				if($('#newuser_' + json.new_users[user].userID).length == 0)
				{
					var div_content ="";
					div_content += "<div class='admin_entry' id='newuser_" + json.new_users[user].userID + "'><p>";
					div_content += json.new_users[user].first_name;
					div_content += ' ';
					div_content += json.new_users[user].last_name;
					div_content += "</p></div>";
					newusers_div.prepend(div_content);
					$('#newuser_' + json.new_users[user].userID).hide();
					$('#newuser_' + json.new_users[user].userID).fadeIn();
				}
				else
				{
					/*
$('#newuser_' + json.new_users[user].userID).fadeOut();
					$('#newuser_' + json.new_users[user].userID).remove();
					
					
					var div_content ="";
					div_content += "<div class='admin_entry' id='newuser_" + json.new_users[user].userID + "'><p>";
					div_content += json.new_users[user].first_name;
					div_content += ' ';
					div_content += json.new_users[user].last_name;
					div_content += "</p></div>";
					newusers_div.prepend(div_content);
					$('#newuser_' + json.new_users[user].userID).hide();
					$('#newuser_' + json.new_users[user].userID).fadeIn();

*/

				}
			}
			
	        
	    	},
	
	    error : function(xhr, status) {
	        newusers_div.html("<p>There has been some trouble retrieving the data you have requested. I&rsquo;m feeling lazy, come back later.")
	    },
	});

}

//Request for Recent activity
function updateRecentUsers()
{
	var activeusers_div = $("#active_users");

	$.ajax(
	{
	    url : 'data/recentActivity',
	
	    method : 'GET',
	
	    dataType : 'json',
	
	    success : function(json) 
	    {
	    	//Additional Error checking
			if(json.error)
			{
				activeusers_div.html(json.error);
			}
			//Display stuff
			else
			{
				
				
			}	
		        
    	},
	
	    error : function(xhr, status) {
	        activeusers_div.html("<p>There has been some trouble retrieving the data you have requested. I&rsquo;m feeling lazy, come back later.")
	    },
	});

}