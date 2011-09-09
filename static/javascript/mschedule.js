/* Requires jQuery, jQueryUI Effects Core, mschedule_viewcontroller.js, and mschedule_model.js */

$j(document).ready(function() {
	
	/* Start Schedule Picker */
	
	var deptListFactory = getDeptListFactory();
	var pickerDiv = $j('#schedule_picker_div');
	var courseList = $j('#course_list_container ul');
	var optionsDiv;
	var nextButton = $j('#nextButton');
	var backButton = $j('#backButton');
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
			
			var action = $j(this).attr('href').replace('#','');
			var breadCrumbText = $j(this).next('h1').html();
			
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
						var listItem = $j('<li/>');
						listItem.append($j('<a/>', {'href' : '#' + courseObj.getAction()}).html(deleteSymbolEntity));
						listItem.append($j('<h1/>', {text:courseObj.getHeader()}));
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
			pickerDiv.ScheduleItemPicker('goto', $j(this).attr('href').replace('#',''));
			return false;
		});
		
	});
	
	/* End Schedule Picker */
	
	/* Start Course List */
	
	courseList.delegate('li a', 'click', function() {
		var $jthis = $j(this);
		if($jthis.hasClass('section_on') || $jthis.hasClass('section_off')) {
			$jthis.toggleClass('section_on');
			$jthis.toggleClass('section_off');
			$jthis.next().toggleClass('strikethrough').next().toggleClass('strikethrough');
			
			if($jthis.hasClass('section_on')) {
				$jthis.html(checkMarkSymbolEntity);
			}
			else {
				$jthis.html('');
			}
		}
		else {
			var action = $jthis.attr('href').replace('#','');
			$jthis.parent().remove();
			delete courseListMap[action];
			if($j.isEmptyObject(courseListMap) && !nextButton.hasClass('button_disabled')) {
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
		var optionsDiv = $j('<div/>', {'id' : 'schedule_options'});
		optionsDiv.append($j('<h1/>', {text : 'Scheduler Options'}));
		optionsDiv.append($j('<h2/>', {text : 'Get up early, or stay out late?'}));
		var optionList = $j('<ul/>', {'class' : 'option_list'});
		optionList.append($j('<li/>', {text : 'Early Riser'}).append($j('<a/>', {'href' : '#0', 'class' : 'option_on'}).html(checkMarkSymbolEntity)));
		optionList.append($j('<li/>', {text : 'Sleep In'}).append($j('<a/>', {'href' : '#1'})));
		optionList.append($j('<li/>', {text : 'Friday Off'}).append($j('<a/>', {'href' : '#2'})));
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
		return $j('<img/>', {'src' : spinnerImage.src, 'alt' : 'spinner_image', style : 'margin:5px 0 0;padding:0;'});
	}
	
	nextButton.click(function() {
		var $jthis = $j(this);
		if(!$jthis.hasClass('button_disabled')) {
			$jthis.addClass('button_disabled');
			if(!backButton.hasClass('button_disabled')) {
				backButton.addClass('button_disabled');
			}
			
			$jthis.html('').append(getSpinnerElem());
			if(curStep > 0) {
				backButton.html('').append(getSpinnerElem());
			}
			
			switch (curStep) {
				case 0:
					var deptsNums = {};
					courseList.children('li').each(function() {
						var action = $j(this).children('a').first().attr('href').replace('#','').split(',');
						
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
							$j('#content').append(optionsDiv);
						}
						
						$j('#' + optionsDiv.attr('id') + ' ul li a').unbind('click');
						$j('#' + optionsDiv.attr('id') + ' ul li a').click(function() {
							$j(this).closest('ul').find('a').removeAttr('class').html('');
							$j(this).addClass('option_on').html(checkMarkSymbolEntity);
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
								sectionListKey = $j(this).children('h1').first().html().replace(' ','');
								$j(this).append(map[sectionListKey].getElement().removeAttr('class'));
							});
							
							$jthis.html('Continue ' + forwardUnicodeEntity);
							$jthis.removeClass('button_disabled');
							curStep++;
						});
					}, true);
					break;
				case 1:
					var timesOption = parseInt(optionsDiv.find('ul li a.option_on').first().attr('href').replace('#',''), 10);
					var classIds = [];
					courseList.find('li a.section_on').each(function() {
						classIds.push($j(this).attr('href').replace('#',''));
					});
					
					var scheduleListFactory = getCourseScheduleListFactory();
					scheduleListFactory.getCourseSchedules(classIds, timesOption, function(list) {
					
						var scheduleViewerDiv = $j('#schedule_viewer_div');
						if(!scheduleViewerDiv.length) {
							scheduleViewerDiv = $j('<div/>', {'id' : 'schedule_viewer_div'});
							scheduleViewerDiv.ScheduleListViewer(list);
							scheduleViewerDiv.css('display','none');
							$j('#content').append(scheduleViewerDiv);
						}
						else {
							scheduleViewerDiv.ScheduleListViewer('setScheduleList', list);
							
						}
						
						animateForwardShift(curStep, function() {
							scheduleViewerDiv.show(flowDuration, flowEasing, function() {
								$jthis.html('Save Selected Schedules');
								$jthis.removeClass('button_disabled');
								
								backButton.html(backwardUnicodeEntity + ' Back');
								backButton.removeClass('button_disabled');
								curStep++;
							});
						});						
					});
					break;
				case 2:
					var scheduleListFactory = getCourseScheduleListFactory();
					scheduleListFactory.saveCourseSchedule($j('#schedule_master li a.schedule_on:first').attr('href').replace('#',''), function(success) {
						if(success) {
							alert('SUCCESS');
						}
						else {
							alert('OOPS!');
							$jthis.html('Save Selected Schedules');
							$jthis.removeClass('button_disabled');
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
		var $jthis = $j(this);
		if(!$jthis.hasClass('button_disabled')) {
			$jthis.addClass('button_disabled');
			$jthis.html('').append(getSpinnerElem());
			
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
					$j('#schedule_viewer_div').hide(flowDuration, flowEasing, function() {
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

