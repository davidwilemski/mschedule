/* Requires jQuery */

function Course(jsonObj) {
	var prop;
	for(prop in jsonObj) {
		if(jsonObj.hasOwnProperty(prop)) {
			this[prop] = jsonObj[prop];
		}
	}
}

function CourseSection(jsonObj) {
	this.sortTimeKey = function() {
		var key = 0;
		if(this.startTime.length) {
			key = parseInt(this.startTime, 10);
		}
		return key;
	};
	
	var prop;
	for(prop in jsonObj) {
		if(jsonObj.hasOwnProperty(prop)) {
			this[prop] = jsonObj[prop];
		}
	}
	
	var times = this.time.split('-');
	
	this.startTime = this.times[0];
	
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
	this.week = new CourseScheduleWeek();
	var weekDays;
	var section;
	
	//put a section where it belongs in each day of the week
	var i;
	for(i = 0; i < courseSections.length; i++) {
		section = courseSections[i];
		this.scheduleId += section.classid + ';';
		weekDays = section.days.split(',');	
		var j;
		for(j = 0; j < weekDays.length; j++) {
			this.week[weekDays[j]].push(section);
		}
	}
	
	//sort each day of the week
	var prop;
	for(prop in this.week) {
		if(this.week.hasOwnProperty(prop)) {
			this.week[prop].sort(courseSectionSortFunc);
		}
	}
	
	//find the baseHour (earliest course's start time) for use when generating schedule html
	var minHour = 0;
	var curHour;
	for(prop in this.week) {
		if(this.week.hasOwnProperty(prop)) {
			if(this.week[prop].length && (curHour = this.week[prop][0].sortTimeKey()) < minHour) {
				minHour = curHour;
			}
		}
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
			return null;
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
function CourseListFactory() {
	this.courseListMap = {};
	this.getCourseList = function(deptId, callback) {
		if(this.courseListMap.hasOwnProperty(deptId)) {
			callback(this.courseListMap[deptId]);
		}
		else {
			var listMap = this.courseListMap;
			var localCallback = callback;
			
			$.post('api/json/class_model/getDeptClassList',{ 'data[]': [deptId]}, function(data) {
				var objKey;
				var courseList = listMap[deptId] = [];
				for(objKey in data) {
					if(data.hasOwnProperty(objKey)) {
						courseList.push(new Course(data[objKey]));
					}
				}
				localCallback(courseList);
			}, 'json');
		}
	};
}

//use this to get the singleton
function getCourseListFactory() {
	var theWindow = document.window;
	if(typeof theWindow.courseSectionFactory === 'undefined') {
		theWindow.courseListFactory = new CourseListFactory();
	}
	return theWindow.courseListFactory;
}


//this constructor should never be called explicitly
function CourseSectionListFactory() {
	this.courseSectionListMap = {};
	
	//this function assumed all needed sections have already been cached
	function getCachedCourseSectionLists(deptsNums, callback) {
		var retList = [];
		var dept;
		for(dept in deptsNums) {
			if(deptsNums.hasOwnProperty(dept)) {
				var i;
				var keyString;
				for(i = 0; i < deptsNums[dept].length; i++) {
					keyString = dept + deptsNums[dept][i];
					retList = retList.concat(this.courseSectionListMap[keyString]);
				}
			}
		}
		callback(retList);
	}
	
	/*
	deptsNums should be an object in the form:
	{
	dept1:[num1, num2, num3],
	dept2:[num1, num2, num3],
	...
	}
	*/ 
	this.getCourseSectionList = function(deptsNums, callback) {
		var sendData = [];
		var dept;
		
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
			var localDeptsNums = deptsNums;
			var sectionListMap = this.courseSectionListMap;
			var localCallback = callback;
			var finishSectionListGet = getCachedCourseSectionLists;
			
			$.post('api/json/class_model/getClassSections', { 'data[]': sendData }, function(data) {
				var sectionKey;
				var sectionObj;
				var keyString;
				
				for(sectionKey in data) {
					if(data.hasOwnProperty(sectionKey)) {
						sectionObj = new CourseSection(data[sectionKey]);
						keyString = sectionObj.dept + sectionObj.number;
						if(!sectionListMap.hasOwnProperty(keyString)) {
							sectionListMap[keyString] = [];
						}
						sectionListMap[keyString].push(sectionObj);
					}
				}
				
				finishSectionListGet(localDeptsNums, localCallback);
			}, 'json');
		}
		else {
			getCachedCourseSectionLists(deptsNums, callback);
		}
	};
}

//use this to get the singleton
function getCourseSectionListFactory() {
	var theWindow = document.window;
	if(typeof theWindow.courseSectionListFactory === 'undefined') {
		theWindow.courseSectionListFactory = new CourseSectionListFactory();
	}
	return theWindow.courseSectionListFactory;
}


//this constructor should never be called explicitly
function CourseScheduleListFactory() {
	var curScheduleListKey = '';
	this.courseScheduleListMap = {};
	
	//courseSections is a sequential array of CourseSection objects
	this.getCourseSchedules = function(courseSections, callback) {
		var classIds = [];
		var i;
		for(i = 0; i < courseSections.length; i++) {
			if(courseSections[i].classid.length > 1) {
				classIds.push(courseSections[i].classid);
			}
		}
		var curSheduleKey = classIds.sort().join('');
		if(this.courseSheduleMap.hasOwnProperty(curSheduleKey)) {
			callback(this.courseScheduleListMap[curSheduleKey]);
		}
		else {
			var localCallback = callback;
			var localCourseScheduleListMap = this.courseScheduleListMap;
			$.post('api/json/class_model/createSchedules', { 'data[]': classIds }, function(data) {
				var prop;
				var schedules = [];
				for(prop in data) {
					if(data.hasOwnProperty(prop)) {
						var sections = [];
						var sectionObjs = data[prop];
						
						for(i = 0; i < sectionObjs.length; i++) {
							sections.push(new CourseSection(sectionObjs[i]));
						}
						
						schedules.push(new CourseSchedule(sections));
					}
				}
				
				var scheduleList = new CourseScheduleList(schedules);
				localCourseScheduleListMap[curSheduleKey] = scheduleList;
				
				localCallback(scheduleList);
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
				var localCallback = callback;
				$.post('api/json/class_model/saveSchedule', { 'data': schedule.scheduleId }, function(data){
					localCallback(data === 'true');
				});
			}
		}
	};
}

function getCourseScheduleListFactory() {
	var theWindow = document.window;
	if(typeof theWindow.courseScheduleListFactory === 'undefined') {
		theWindow.courseScheduleListFactory = new CourseScheduleListFactory();
	}
	return theWindow.courseScheduleListFactory;
}