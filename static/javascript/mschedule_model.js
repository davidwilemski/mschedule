/* Requires jQuery */

function FlexiStack() {
	var arr = [];
	this.push = function(obj) {
		if(obj === undefined) {
			$.error('FlexiStack: Attempt to push undefined obj!');
			return;
		}
		arr.push(obj);
	};
	
	this.pop = function(index) {
		if(!arr.length) {
			$.error('FlexiStack: Attempt to pop empty stack!');
			return;
		}
		if(index !== undefined) {
			var elem = arr[index];
			arr.length = index;
			return elem;
		}
		else {
			return arr.pop();
		}
	};
	
	this.top = function() {
		if(!arr.length) {
			$.error('FlexiStack: Attempt to look at empty stack!');
			return;
		}
		return arr[arr.length - 1];
	};
	
	this.size = function() {
		return arr.length;
	};
	
	this.clear = function () {
		arr.length = 0;
	};
}

function Dept(jsonObj) {
	this.getHeader = function() {
		return this.dept;
	};
	
	this.getDetail = function() {
		return this.full_name;
	};
	
	this.getAction = function() {
		return this.dept.trim();
	};
	
	var prop;
	for(prop in jsonObj) {
		if(jsonObj.hasOwnProperty(prop)) {
			this[prop] = jsonObj[prop];
		}
	}
}

function Course(jsonObj) {
	this.getHeader = function() {
		return this.dept + ' ' + this.number;
	};
	
	this.getDetail = function() {
		return this.class_name;
	};
	
	this.getAction = function() {
		return (this.dept + ',' + this.number).trim();
	};
	
	var prop;
	for(prop in jsonObj) {
		if(jsonObj.hasOwnProperty(prop)) {
			this[prop] = jsonObj[prop];
		}
	}
}

function CourseSection(jsonObj) {
	this.getHeader = function() {
		return this.dept + ' ' + this.number + '-' + this.section;
	};
	
	this.getDetail = function() {
		return this.class_name;
	};
	
	this.getAction = function() {
		return this.classid.trim();
	};
	
	this.getPlace = function() {
		if(typeof this.location !== 'undefined') {
			if(this.location.length) {
				return this.location.join(', ');
			}
			else {
				return '';
			}
		}
		else {
			return '';
		}
	};
	
	function formatTime(time) {
		var hours = parseInt(time, 10);
		var minutes = hours % 100;
		hours /= 100;
		var minuteStr = minutes.toString();
		if(minutes < 10) {
			minuteStr += '0';
		}
		
		var ampm = 'a';
		if(hours > 11) {
			ampm = 'p';
		}
		if(hours > 12) {
			hours -= 12;
		}
		
		return hours.toString() + ':' + minuteStr + ampm;
	}
	
	var cachedTimeStr = '';
	
	this.getTimes = function() {
		if(!cachedTimeStr) {
			var timeStr = '';
			if(!this.startTime && !this.endTime) {
				timeStr = 'TBD';
			}
			else {
				if(this.startTime) {
					timeStr += formatTime(this.startTime);
				}
				else {
					timeStr += 'TBD';
				}
				timeStr += '-';
				
				if(this.endTime) {
					timeStr += formatTime(this.endTime);
				}
				else {
					timeStr += 'TBD';
				}
			}
			cachedTimeStr = timeStr;
		}
		return cachedTimeStr;
	};
	
	this.sortTimeKey = function() {
		var key = 0;
		if(this.startTime.length) {
			key = parseInt(this.startTime, 10);
		}
		return key;
	};
	
	this.time = '';
	this.days = '';
	
	var prop;
	for(prop in jsonObj) {
		if(jsonObj.hasOwnProperty(prop)) {
			this[prop] = jsonObj[prop];
		}
	}	
	
	//implicit string conversion to fix corner cases
	this.time += '';
	this.days += '';
	
	var times = this.time.split('-');
	
	this.startTime = times[0];
	
	this.endTime = '';
	if(times.length > 1) {
		this.endTime = times[1];
	}
}

//section1 and section2 must be of type CourseSection
function courseSectionSortFunc(section1, section2) {
	var key1 = section1.sortTimeKey();
	var key2 = section2.sortTimeKey();
	if(key1 < key2) {
		return -1;
	}
	if(key1 > key2) {
		return 1;
	}
	return 0;
}

function CourseScheduleWeek() {
	this.M = [];
	this.TU = [];
	this.W = [];
	this.TH = [];
	this.F = [];
}

//courseSections should be a sequential array of CourseSection objects
function CourseSchedule(courseSections) {
	this.scheduleId = '';
	this.title = '';
	this.week = new CourseScheduleWeek();
	var weekdays;
	var section;
	
	//put a section where it belongs in each day of the week
	var i;
	var classIds = [];
	for(i = 0; i < courseSections.length; i++) {
		section = courseSections[i];
		classIds.push(section.classid);
		if(section.days.length) {
			weekdays = section.days.split(',');	
			var j;
			for(j = 0; j < weekdays.length; j++) {
				this.week[weekdays[j]].push(section);
			}
		}
	}
	
	this.scheduleId = classIds.join('_');
	
	//sort each day of the week
	var prop;
	for(prop in this.week) {
		if(this.week.hasOwnProperty(prop)) {
			this.week[prop].sort(courseSectionSortFunc);
		}
	}
	
	//find the baseHour (earliest course's start time) for use when generating schedule html
	var minHour = 2400;
	var curHour;
	for(prop in this.week) {
		if(this.week.hasOwnProperty(prop)) {
			if(this.week[prop].length && (curHour = this.week[prop][0].sortTimeKey()) < minHour) {
				minHour = curHour;
			}
		}
	}
	
	//if it didn't work, reset to 0
	if(minHour === 2400) {
		minHour = 0;
	}
	this.baseHour = minHour;
}

//courseSchedules should be a sequential array of CourseSchedule objects
function CourseScheduleList(courseSchedules) {
	this.arrList = courseSchedules;
	this.courseScheduleMap = {};
	this.size = function() {
		return this.arrList.length;
	};
	var curSchedule = 0;
	
	this.getSchedule = function(key) {
		if(typeof key === 'string') {
			if(this.courseScheduleMap.hasOwnProperty(key)) {
				return this.courseScheduleMap[key];
			} else {
				return null;
			}
		}
		else if (typeof key === 'number') {
			if(key >= 0 && key < this.arrList.length) {
				return this.arrList[key];
			} else {
				return null;
			}
		}
		else {
			return this.getCurrentSchedule();
		}
	};
	
	this.getNextSchedule = function() {
		if(curSchedule + 1 < this.arrList.length) {
			curSchedule++;
			return this.arrList[curSchedule];
		}
		else {
			return null;
		}
	};
	
	this.getCurrentSchedule = function() {
		if(this.arrList.length) {
			return this.arrList[curSchedule];
		}
		else {
			return null;
		}
	};
	
	this.getPrevSchedule = function() {
		if(curSchedule - 1 >= 0) {
			curSchedule--;
			return this.arrList[curSchedule];
		}
		else {
			return null;
		}
	};
	
	var i;
	for(i = 0; i < courseSchedules.length; i++) {
		this.courseScheduleMap[courseSchedules[i].scheduleId] = courseSchedules[i];
	}
}

//this constructor should never be called explicitly
function DeptListFactory() {
	this.deptList = [];
	this.getDeptList = function(callback) {
		if(this.deptList.length) {
			callback(this.deptList);
		}
		else {
			var localDeptList = this.deptList;
			
			$.post('api/json/class_model/getMasterDepartmentList', function(data) {
				var objKey;
				for(objKey in data) {
					if(data.hasOwnProperty(objKey)) {
						localDeptList.push(new Dept(data[objKey]));
					}
				}
				callback(localDeptList);
			}, 'json');
		}
	};
}

//use this to get the singleton
function getDeptListFactory() {
	var theWindow = window;
	if(typeof theWindow.deptListFactory === 'undefined') {
		theWindow.deptListFactory = new DeptListFactory();
	}
	return theWindow.deptListFactory;
}

//this constructor should never be called explicitly
function CourseListFactory() {
	this.courseListMap = {};
	this.getCourseList = function(deptId, callback) {
		if(this.courseListMap.hasOwnProperty(deptId)) {
			callback(this.courseListMap[deptId]);
		}
		else {
			var listMap = this.courseListMap;
			
			$.post('api/json/class_model/getDeptClassList',{ 'data[]': [deptId]}, function(data) {
				var objKey;
				var courseList = listMap[deptId] = [];
				for(objKey in data) {
					if(data.hasOwnProperty(objKey)) {
						courseList.push(new Course(data[objKey]));
					}
				}
				callback(courseList);
			}, 'json');
		}
	};
}

//use this to get the singleton
function getCourseListFactory() {
	var theWindow = window;
	if(typeof theWindow.courseListFactory === 'undefined') {
		theWindow.courseListFactory = new CourseListFactory();
	}
	return theWindow.courseListFactory;
}


//this constructor should never be called explicitly
function CourseSectionListFactory() {
	this.courseSectionListMap = {};
	
	//this function assumed all needed sections have already been cached
	function getCachedCourseSectionLists(courseSectionListMap, deptsNums, callback, returnMap) {
		var dept;
		var i;
		var keyString;
		if(returnMap) {
			var retMap = {};
			for(dept in deptsNums) {
				if(deptsNums.hasOwnProperty(dept)) {
					for(i = 0; i < deptsNums[dept].length; i++) {
						keyString = dept + deptsNums[dept][i];
						retMap[keyString] = courseSectionListMap[keyString];
					}
				}
			}
			callback(retMap);
		}
		else {
			var retArr = [];
			for(dept in deptsNums) {
				if(deptsNums.hasOwnProperty(dept)) {
					for(i = 0; i < deptsNums[dept].length; i++) {
						keyString = dept + deptsNums[dept][i];
						retArr = retArr.concat(courseSectionListMap[keyString]);
					}
				}
			}
			callback(retArr);
		}
	}
	
	/*
	deptsNums should be an object in the form:
	{
	dept1:[num1, num2, num3],
	dept2:[num1, num2, num3],
	...
	}
	
	returnMap === true ? return map of course to its array of sections : list
	*/ 
	this.getCourseSectionList = function(deptsNums, callback, returnMap) {
		var sendData = [];
		var dept;
		
		if(returnMap === undefined) {
			returnMap = false;
		}
		
		//check to see if there are some sections that we need to fetch
		for(dept in deptsNums) {
			if(deptsNums.hasOwnProperty(dept)) {
				var i;
				var keyString;
				for(i = 0; i < deptsNums[dept].length; i++) {
					keyString = dept + deptsNums[dept][i];
					if(!this.courseSectionListMap.hasOwnProperty(keyString)) {
						sendData.push(dept);
						sendData.push(deptsNums[dept][i]);
					}
				}
			}
		}
		
		//if there are some that need to be fetched, fetch em
		if(sendData.length) {
			var sectionListMap = this.courseSectionListMap;
			var finishSectionListGet = getCachedCourseSectionLists;
			
			$.post('api/json/class_model/getClassSections', { 'data[]': sendData }, function(data) {
				var sectionObj;
				var keyString;
				
				var i;
				for(i = 0; i < data.length; i++) {
					var sectionArr = data[i];
					var j;
					for(j = 0; j < sectionArr.length; j++) {
						sectionObj = new CourseSection(sectionArr[j]);
						keyString = sectionObj.dept + sectionObj.number;
						if(!sectionListMap.hasOwnProperty(keyString)) {
							sectionListMap[keyString] = [];
						}
						sectionListMap[keyString].push(sectionObj);
					}
				}
				
				finishSectionListGet(sectionListMap, deptsNums, callback, returnMap);
			}, 'json');
		}
		else {
			getCachedCourseSectionLists(this.courseSectionListMap, deptsNums, callback, returnMap);
		}
	};
}

//use this to get the singleton
function getCourseSectionListFactory() {
	var theWindow = window;
	if(typeof theWindow.courseSectionListFactory === 'undefined') {
		theWindow.courseSectionListFactory = new CourseSectionListFactory();
	}
	return theWindow.courseSectionListFactory;
}


//this constructor should never be called explicitly
function CourseScheduleListFactory() {
	var curScheduleListKey = '';
	this.courseScheduleListMap = {};
	
	//classIds is a sequential array of classid strings
	this.getCourseSchedules = function(classIds, timesOption, callback) {
		var curSheduleKey = classIds.sort().join(';');
		if(this.courseScheduleListMap.hasOwnProperty(curSheduleKey)) {
			callback(this.courseScheduleListMap[curSheduleKey]);
		}
		else {
			var localCourseScheduleListMap = this.courseScheduleListMap;
			$.post('api/json/class_model/createSchedules', { 'data[]': [timesOption].concat(classIds) }, function(data) {
				var prop;
				var schedules = [];
				for(prop in data) {
					if(data.hasOwnProperty(prop)) {
						var sections = [];
						var sectionObjs = data[prop];
						var i;
						for(i = 0; i < sectionObjs.length; i++) {
							sections.push(new CourseSection(sectionObjs[i]));
						}
						var schedule = new CourseSchedule(sections);
						schedule.title = 'Schedule ' + schedules.length;
						schedules.push(schedule);
					}
				}
				
				var scheduleList = new CourseScheduleList(schedules);
				localCourseScheduleListMap[curSheduleKey] = scheduleList;
				
				callback(scheduleList);
			}, 'json');
		}
	};
	
	this.getCurrentScheduleList = function() {
		if(!curScheduleListKey.length || !this.courseScheduleListMap.hasOwnProperty(curScheduleListKey)) {
			return null;
		}
		return this.courseScheduleListMap[curScheduleListKey];
	};
	
	this.saveCourseSchedule = function(key, callback) {
		var scheduleList = this.getCurrentScheduleList();
		if(scheduleList === null) {
			callback(false);
		}
		else {
			var schedule = scheduleList.getSchedule(key);
			if(schedule === null) {
				callback(false);
			}
			else {
				$.post('api/json/class_model/saveSchedule', { 'data': schedule.scheduleId }, function(data){
					callback(data === 'true');
				});
			}
		}
	};
}

function getCourseScheduleListFactory() {
	var theWindow = window;
	if(typeof theWindow.courseScheduleListFactory === 'undefined') {
		theWindow.courseScheduleListFactory = new CourseScheduleListFactory();
	}
	return theWindow.courseScheduleListFactory;
}