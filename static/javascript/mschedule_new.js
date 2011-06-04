/* Requires jQuery, jQueryUI Effects Core, mschedule_viewcontroller.js, and mschedule_model.js */

$(document).ready(function() {
	
	/* Start Schedule Picker */
	
	var deptListFactory = getDeptListFactory();
	var pickerDiv = $('#schedule_picker_div');
	var courseList = $('#course_list_container ul');
	var optionsDiv;
	var nextButton = $('#nextButton');
	var backButton = $('#backButton');
	var courseListMap = {};
	
	
	var deleteSymbolEntity = '&#10761;';
	var checkMarkSymbolEntity = '&#10004;';
	var forwardUnicodeEntity = '<span class="unicode_direction">&nbsp;&#8594;</span>';
	var backwardUnicodeEntity = '<span class="unicode_direction">&#8592;&nbsp;</span>';
	var spinnerImage = new Image();
	spinnerImage.src = 'http://localhost/mschedule/static/images/spinner.gif';
	
	deptListFactory.getDeptList(function(list) {	
		var listView = new ScheduleItemListView(list, 'Departments');
		
		pickerDiv.ScheduleItemPicker();
		pickerDiv.ScheduleItemPicker('push', listView);
		
		pickerDiv.ScheduleItemPicker('bindItem', 'click', function(courseObj) {
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
		
		pickerDiv.ScheduleItemPicker('bindBreadCrumb', 'click', function() {
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
	var buttonDuration = 500;
	var flowShiftMap = [
	{
		'pickerDiv' : '20px',
		'courseList' : '580px',
		'optionsDiv' : '900px',
		'backButton' : '-40px'
	},
	{
		'pickerDiv' : '-415px',
		'courseList' : '20px',
		'optionsDiv' : '355px',
		'backButton' : '15px'
	},
	{
		'pickerDiv' : '-1170px',
		'courseList' : '-735px',
		'optionsDiv' : '-400px',
		'backButton' : '15px'
	}];
	
	function createOptionsDiv() {
		var optionsDiv = $('<div/>', {'id' : 'schedule_options'});
		optionsDiv.append($('<h1/>', {text : 'Scheduler Options'}));
		optionsDiv.append($('<h2/>', {text : 'Get up early, or stay out late?'}));
		var optionList = $('<ul/>', {'class' : 'option_list'});
		optionList.append($('<li/>', {text : 'Early Riser'}).append($('<a/>', {'href' : '#0', 'class' : 'option_on'}).html(checkMarkSymbolEntity)));
		optionList.append($('<li/>', {text : 'Sleep In'}).append($('<a/>', {'href' : '#1'})));
		optionList.append($('<li/>', {text : 'Friday Off'}).append($('<a/>', {'href' : '#2'})));
		optionsDiv.append(optionList);
		return optionsDiv;
	}
	
	function animateForwardShift(step, callback) {
		var flowShift = flowShiftMap[step + 1];
		
		pickerDiv.parent().animate({left : flowShift['pickerDiv']}, flowDuration, flowEasing);
		courseList.parent().animate({left : flowShift['courseList']}, flowDuration, flowEasing);
		
		if(callback !== undefined) {
			optionsDiv.animate({left : flowShift['optionsDiv']}, flowDuration, flowEasing, callback);
		}
		else {
			optionsDiv.animate({left : flowShift['optionsDiv']}, flowDuration, flowEasing);
		}
	}
	
	function getSpinnerElem() {
		return $('<img/>', {'src' : spinnerImage.src, 'alt' : 'spinner_image', style : 'margin:5px 0 0;padding:0;'});
	}
	
	nextButton.click(function() {
		var $this = $(this);
		if(!$this.hasClass('button_disabled')) {
			$this.addClass('button_disabled');
			if(!backButton.hasClass('button_disabled')) {
				backButton.addClass('button_disabled');
			}
			
			$this.html('').append(getSpinnerElem());
			if(curStep > 0) {
				backButton.html('').append(getSpinnerElem());
			}
			
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
					courseList.find('li a').css('display','none');
					
					var sectionListFactory = getCourseSectionListFactory();
					sectionListFactory.getCourseSectionList(deptsNums, function(map) {
						
						if(optionsDiv === undefined) {
							optionsDiv = createOptionsDiv();
							optionsDiv.css('left','900px');
							$('#content').append(optionsDiv);
						}
						
						$('#' + optionsDiv.attr('id') + ' ul li a').unbind('click');
						$('#' + optionsDiv.attr('id') + ' ul li a').click(function() {
							$(this).closest('ul').find('a').removeAttr('class').html('');
							$(this).addClass('option_on').html(checkMarkSymbolEntity);
							return false;
							
						});
						
						animateForwardShift(curStep, function() {
							backButton.removeClass('button_disabled');
							backButton.animate({bottom : '15px'}, buttonDuration, flowEasing);
							
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
							
							$this.html('Continue ' + forwardUnicodeEntity);
							$this.removeClass('button_disabled');
							curStep++;
						});
					}, true);
					break;
				case 1:
					var timesOption = parseInt(optionsDiv.find('ul li a.option_on').first().attr('href').replace('#',''), 10);
					var classIds = [];
					courseList.find('li a.section_on').each(function() {
						classIds.push($(this).attr('href').replace('#',''));
					});
					
					var scheduleListFactory = getCourseScheduleListFactory();
					scheduleListFactory.getCourseSchedules(classIds, timesOption, function(list) {
					
						var scheduleViewerDiv = $('#schedule_viewer_div');
						if(!scheduleViewerDiv.length) {
							scheduleViewerDiv = $('<div/>', {'id' : 'schedule_viewer_div'});
							scheduleViewerDiv.ScheduleListViewer(list);
							scheduleViewerDiv.css('display','none');
							$('#content').append(scheduleViewerDiv);
						}
						else {
							scheduleViewerDiv.ScheduleListViewer('setScheduleList', list);
							
						}
						
						animateForwardShift(curStep, function() {
							scheduleViewerDiv.show(flowDuration, flowEasing, function() {
								$this.html('Save Selected Schedules');
								$this.removeClass('button_disabled');
								
								backButton.html(backwardUnicodeEntity + ' Back');
								backButton.removeClass('button_disabled');
								curStep++;
							});
						});						
					});
					break;
				case 2:
					var scheduleListFactory = getCourseScheduleListFactory();
					scheduleListFactory.saveCourseSchedule($('#schedule_master li a.schedule_on:first').attr('href').replace('#',''), function(success) {
						if(success) {
							alert('SUCCESS');
						}
						else {
							alert('OOPS!');
							$this.html('Save Selected Schedules');
							$this.removeClass('button_disabled');
						}
					});
				default:
					break;
			}
		}
		return false;
	});
	
	function animateBackwardShift(step) {
		var flowShift = flowShiftMap[step - 1];
					
		pickerDiv.parent().animate({left : flowShift['pickerDiv']}, flowDuration, flowEasing);
		courseList.parent().animate({left : flowShift['courseList']}, flowDuration, flowEasing);
		optionsDiv.animate({left : flowShift['optionsDiv']}, flowDuration, flowEasing, function() {
			backButton.animate({bottom : flowShift['backButton']}, buttonDuration, flowEasing);
			
			backButton.html(backwardUnicodeEntity + ' Back');
			if(step > 1) {
				backButton.removeClass('button_disabled');
			}
			
			nextButton.html('Continue ' + forwardUnicodeEntity);
			nextButton.removeClass('button_disabled');
		});
	}
	
	
	backButton.click(function() {
		var $this = $(this);
		if(!$this.hasClass('button_disabled')) {
			$this.addClass('button_disabled');
			$this.html('').append(getSpinnerElem());
			
			nextButton.addClass('button_disabled');
			nextButton.html('').append(getSpinnerElem());
					
			switch (curStep) {
				case 1:
					courseList.find('ul').remove();
					courseList.find('a').css('display','block');
					animateBackwardShift(curStep);
					break;
				case 2:
					var localStep = curStep; //need this so that we have value pre-decremented on animation callback
					$('#schedule_viewer_div').hide(flowDuration, flowEasing, function() {
						animateBackwardShift(localStep);
					});
					break;
				default:
					break;
			}
			
			curStep--;
		}
		
		return false;
	});
	
	/* End Scheduler Flow */
	
});

