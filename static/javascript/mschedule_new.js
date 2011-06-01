/* Requires jQuery, jQueryUI Effects Core, mschedule_viewcontroller.js, and mschedule_model.js */

$(document).ready(function(event) {
	
	/* Start Schedule Picker */
	
	var deptListFactory = getDeptListFactory();
	var pickerDiv = $('#schedule_picker_div');
	var courseList = $('#course_list_container ul');
	var optionsDiv = undefined;
	var nextButton = $('#nextButton');
	var courseListMap = {};
	
	var deleteSymbolEntity = '&#10761;';
	var checkMarkSymbolEntity = '&#10004;';
	var spinnerImage = new Image();
	spinnerImage.src = 'http://localhost/mschedule/static/images/spinner.gif';
	
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
						courseList.append(listItem);
						if(nextButton.hasClass('button_disabled')) {
							nextButton.removeClass('button_disabled');
						}
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
	
	courseList.delegate('li a', 'click', function() {
		var $this = $(this);
		if($this.hasClass('section_on') || $this.hasClass('section_off')) {
			$this.toggleClass('section_on');
			$this.toggleClass('section_off');
			$this.next().toggleClass('strikethrough').next().toggleClass('strikethrough');
			
			if($this.hasClass('section_on')) {
				$this.html(checkMarkSymbolEntity);
			}
			else {
				$this.html('');
			}
		}
		else {
			var action = $this.attr('href').replace('#','');
			$this.parent().remove();
			delete courseListMap[action];
			if($.isEmptyObject(courseListMap) && !nextButton.hasClass('button_disabled')) {
				nextButton.addClass('button_disabled');
			}
		}
		return false;
	});
	
	/* End Course List  */
	
	
	/* Start Scheduler Flow */
	
	var curStep = 0;
	var flowEasing = 'easeInOutQuint';
	var flowDuration = 750;
	
	nextButton.click(function(event) {
		var $this = $(this);
		if(!$this.hasClass('button_disabled')) {
			$this.addClass('button_disabled');
			
			var oldInnerHTML = $this.html();
			$this.html('').append($('<img/>', {'src' : spinnerImage.src, 'alt' : 'spinner_image', style : 'margin:5px 0 0;padding:0;'}));
			
			switch (curStep) {
				case 0:
					var deptsNums = {};
					courseList.children('li').each(function() {
						var action = $(this).children('a').first().attr('href').replace('#','').split(',');
						
						if(!deptsNums.hasOwnProperty(action[0])) {
							deptsNums[action[0]] = [action[1]];
						}
						else {
							deptsNums[action[0]].push(action[1]);
						}
					});
					courseList.find('li a').remove();
					
					var sectionListFactory = getCourseSectionListFactory();
					sectionListFactory.getCourseSectionList(deptsNums, function(map) {
						
						if(optionsDiv === undefined) {
						/*
$times = array(
	'0' => 'Early Riser',
	'1' => 'Sleep In',
	'2' => 'Friday Off!'
);
echo form_dropdown('times', $times, 'free_morning');
*/
							optionsDiv = $('<div/>', {'id' : 'schedule_options'});
							optionsDiv.append($('<h1/>', {text : 'Scheduler Options'}));
							optionsDiv.append($('<h2/>', {text : 'Get up early, or stay out late?'}));
							var optionList = $('<ul/>', {'class' : 'option_list'});
							optionList.append($('<li/>', {text : 'Early Riser'}).append($('<a/>', {'href' : '#0', 'class' : 'option_on'}).html(checkMarkSymbolEntity)));
							optionList.append($('<li/>', {text : 'Sleep In'}).append($('<a/>', {'href' : '#1'})));
							optionList.append($('<li/>', {text : 'Friday Off'}).append($('<a/>', {'href' : '#2'})));
							optionsDiv.append(optionList);
							optionsDiv.css('left','800px');
							$('#content').append(optionsDiv);
						}
						
						$('#' + optionsDiv.attr('id') + ' ul li a').unbind('click');
						$('#' + optionsDiv.attr('id') + ' ul li a').click(function() {
							$(this).closest('ul').find('a').removeAttr('class').html('');
							$(this).addClass('option_on').html(checkMarkSymbolEntity);
							return false;
							
						});
						
						optionsDiv.animate({left : '355px'}, flowDuration, flowEasing);
						pickerDiv.parent().animate({left : '-' + pickerDiv.parent().css('width')}, flowDuration, flowEasing);
						courseList.parent().animate({left : '20px'}, flowDuration, flowEasing, function() {
							
							var sectionListKey;
							for(sectionListKey in map) {
								if(map.hasOwnProperty(sectionListKey)) {
									map[sectionListKey] = new ScheduleItemListView(map[sectionListKey], undefined, 'section_on', checkMarkSymbolEntity);
								}
							}
							
							courseList.children('li').each(function() {
								sectionListKey = $(this).children('h1').first().html().replace(' ','');
								$(this).append(map[sectionListKey].getElement().removeAttr('class'));
							});
							
							$this.html(oldInnerHTML);
							$this.removeClass('button_disabled');
							curStep++;
						});
					}, true);
					break;
				case 1:
					timesOption = parseInt(optionsDiv.find('ul li a.option_on').first().attr('href').replace('#',''), 10);
					classIds = [];
					courseList.find('li a.section_on').each(function() {
						classIds.push($(this).attr('href').replace('#',''));
					});
					
					var scheduleListFactory = getCourseScheduleListFactory();
					scheduleListFactory.getCourseSchedules(classIds, timesOption, function(list) {
					optionsDiv.animate({left : '-=800px'}, flowDuration, flowEasing);
						pickerDiv.parent().animate({left : '-=800px' + pickerDiv.parent().css('width')}, flowDuration, flowEasing);
						courseList.parent().animate({left : '-=800px'}, flowDuration, flowEasing, function() {
							
							var sectionListKey;
							for(sectionListKey in map) {
								if(map.hasOwnProperty(sectionListKey)) {
									map[sectionListKey] = new ScheduleItemListView(map[sectionListKey], undefined, 'section_on', checkMarkSymbolEntity);
								}
							}
							
							courseList.children('li').each(function() {
								sectionListKey = $(this).children('h1').first().html().replace(' ','');
								$(this).append(map[sectionListKey].getElement().removeAttr('class'));
							});
							
							$this.html(oldInnerHTML);
							$this.removeClass('button_disabled');
							curStep++;
							
							console.log(list);
						});
					});
					break;
				default:
					break;
			}
		}
		return false;
	});
	
	/* End Scheduler Flow */
	
});

