/* Requires jQuery and mschedule_model.js */

var pixelsPerHour = 40;

function diffTimes(greater, lesser) {
	if(typeof greater === 'string') {
		greater = parseInt(greater, 10);
	}
	if(typeof lesser === 'string') {
		lesser = parseInt(lesser, 10);
	}
	greater /= 100.0;
	lesser /= 100.0;
	if(greater % 1 < 1) {
		greater = Math.ceil(greater) + 0.5;
	}
	if(lesser % 1 < 1) {
		lesser = Math.ceil(lesser) + 0.5;
	}
	
	return (greater - lesser);
}

//courseSchedule should be a CourseSchedule object
function CourseScheduleView(courseSchedule) {
	this.courseSchedule = courseSchedule;
	var scheduleElement = $('<div/>', { 'class' : 'schedule', 'id' : courseSchedule.scheduleId });
	this.getElement = function() {
		return scheduleElement;
	};
	
	//create the scheduleElement, which can just be injected into the page
	var weekList = $('<ul/>', {'class' : 'schedule_week'});
	var day;
	for(day in this.courseSchedule.week) {
		if(this.courseSchedule.week.hasOwnProperty(day)) {
			var dayListElement = $('<ul/>', {'class': 'schedule_day'});
			var dayArr = this.courseSchedule.week[day];
			var section;
			var courseSection = null;
			var numPixels;
			
			if(dayArr.length) {
				for(section = 0; section < dayArr.length; section++) {
					var prevHourDiff;
					if(courseSection !== null) {
						prevHourDiff = diffTimes(courseSection.endTime, dayArr[section].startTime);
						if(prevHourDiff) {
							numPixels = Math.ceil(pixelsPerHour * prevHourDiff);
							dayListElement.append($('<li/>', { 'class' : 'day_break', 'style' : 'height:' + numPixels + 'px;' }));
						}
					}
					else if((prevHourDiff = diffTimes(dayArr[section].startTime, this.courseSchedule.baseHour)) > 0) {
						numPixels = Math.ceil(pixelsPerHour * prevHourDiff);
						dayListElement.append($('<li/>', { 'class' : 'day_empty', 'style' : 'height:' + numPixels + 'px;' }));
					}
					
					courseSection = dayArr[section];
					numPixels = Math.ceil(pixelsPerHour * diffTimes(courseSection.endTime,courseSection.startTime));
					dayListElement.append($('<li/>', {
											'style' : 'height:' + numPixels + 'px;', 
											text : courseSection.dept + ' ' + courseSection.number + '-' + section
											}));
				}
			}
			else {
				dayListElement.append($('<li/>', {'class' : 'day_empty'}));
			}
			
			var weekListItemElement = $('<li/>');
			weekListItemElement.append(dayListElement);
			weekList.append(weekListItemElement);
		}
	}
	scheduleElement.append(weekList);
}


