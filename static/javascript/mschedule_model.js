/* Requires JQuery */

function Course(anyObj) {
	if(anyObj !== undefined) {
		var prop;
		for(prop in anyObj) {
			if(anyObj.hasOwnProperty(prop)) {
				this[prop] = anyObj[prop];
			}
		}
	} else {
		this.class_name = '';
		this.classid = '';
		this.dept = '';
		this.number = '';
	}
}

function CourseSection(anyObj) {
	if(anyObj !== undefined) {
		var prop;
		for(prop in anyObj) {
			if(anyObj.hasOwnProperty(prop)) {
				this[prop] = anyObj[prop];
			}
		}
	} else {
		this.class_name = '';
		this.classid = '';
		this.code = '';
		this.days = '';
		this.dept = '';
		this.instructor = '';
		this.location = '';
		this.number = '';
		this.section = '';
		this.time = '';
		this.type = '';
	}
	this.sortTimeKey = function() {
		var key = 0;
		var times = this.time.split('-');
		if(times[0].length) {
			key = parseInt(times[0], 10);
		}
		return key;
	};
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

//courseSections should be a sequential array
function CourseSchedule(courseSections) {
	this.week = new CourseScheduleWeek();
	var weekDays;
	var section;
	
	//put a section where it belongs in each day of the week
	var i;
	for(i = 0; i < courseSections.length; i++) {
		section = courseSections[i];
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

//this constructor should never be called explicitly
function CourseListFactory() {
	this.courseListMap = {};
	this.getCourseList = function(deptId, callback) {
		if(this.courseListMap.hasOwnProperty(deptId)) {
			callback(this.courseListMap[deptId]);
		} else {
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
					retList.concat(this.courseSectionListMap[keyString]);
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
		} else {
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
