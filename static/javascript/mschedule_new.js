/* Requires jQuery, jQueryUI Effects Core, mschedule_viewcontroller.js, and mschedule_model.js */

$(document).ready(function(event) {
	
	/* Start Schedule Picker */
	
	var deptListFactory = getDeptListFactory();
	var pickerDiv = $('#schedule_picker_div');
	var courseList = $('#course_list_container ul');
	var courseListMap = {};
	var deleteSymbolEntity = '&#10761;';
	
	deptListFactory.getDeptList(function(list) {	
		var listView = new ScheduleItemListView(list, 'Departments');
		
		pickerDiv.ScheduleItemPicker();
		pickerDiv.ScheduleItemPicker('push', listView);
		
		pickerDiv.ScheduleItemPicker('bindItem', 'click', function(event, courseObj) {
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
					if(!courseListMap.hasOwnProperty(courseObj.getAction())) {
						courseListMap[courseObj.getAction()] = courseObj;
						var listItem = $('<li/>');
						listItem.append($('<a/>', {'href' : '#' + courseObj.getAction()}).html(deleteSymbolEntity));
						listItem.append($('<h1/>', {text:courseObj.getHeader()}));
						listItem.append($('<p/>', {text:courseObj.getDetail()}));
						courseList.append(listItem);
					}
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
	
	/* End Schedule Picker */
	
	
	/* Start Course List */
	
	courseList.delegate('li a', 'click', function(event) {
		var $this = $(this);
		var action = $this.attr('href').replace('#','');
		$this.parent().remove();
		delete courseListMap[action];
		return false;
	});
	
	/* End Course List  */
	
	
});

