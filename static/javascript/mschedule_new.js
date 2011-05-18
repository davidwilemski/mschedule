/* Requires jQuery, jQueryUI Effects Core, mschedule_viewcontroller.js, and mschedule_model.js */

$(document).ready(function(event) {
	var deptListFactory = getDeptListFactory();
	deptListFactory.getDeptList(function(list) {
		var pickerDiv = $('#class_div');
		var listView = new ScheduleItemListView(list, 'Departments');
		
		pickerDiv.ScheduleItemPicker();
		pickerDiv.ScheduleItemPicker('push', listView);
		
		pickerDiv.ScheduleItemPicker('bindItem', 'click', function(event) {
			var index = pickerDiv.ScheduleItemPicker('stackSize') - 1;
			
			var action = $(this).attr('href').replace('#','');
			var breadCrumbText = $(this).next('h1').html();
			
			switch (index) {
				case 0:
					var courseListFactory = getCourseListFactory();
					courseListFactory.getCourseList(action, function(newList) {
						var newListView = new ScheduleItemListView(newList, breadCrumbText);
						pickerDiv.ScheduleItemPicker('push', newListView);
						return false;
					});
					break;
				case 1:
					var actionArr = action.split(',');
					var actionObj = {};
					actionObj[actionArr[0]] = [actionArr[1]];
					
					var courseSectionListFactory = getCourseSectionListFactory();
					courseSectionListFactory.getCourseSectionList(actionObj, function(newList) {
						var newListView = new ScheduleItemListView(newList, breadCrumbText);
						pickerDiv.ScheduleItemPicker('push', newListView);
					});
					break;
				case 2:
					break;
				default:
					pickerDiv.ScheduleItemPicker('reset');
					break;
			}
			
			return false;
		});
		
		pickerDiv.ScheduleItemPicker('bindBreadCrumb', 'click', function(event) {
			pickerDiv.ScheduleItemPicker('goto', $(this).attr('href').replace('#',''));
			return false;
		});
	});
});

