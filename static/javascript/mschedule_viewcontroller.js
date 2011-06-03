/* Requires jQuery, jQueryUI Effects Core, jQuery.ScrollTo, and mschedule_model.js */

var pixelsPerHour = 60;

//Source: http://jdsharp.us/jQuery/minute/calculate-scrollbar-width.php
function scrollbarWidth() {
    var div = $('<div style="width:50px;height:50px;overflow:hidden;position:absolute;top:-200px;left:-200px;"><div style="height:100px;"></div>');
    $('body').append(div);
    var w1 = $('div', div).innerWidth();
    div.css('overflow-y', 'scroll');
    var w2 = $('div', div).innerWidth();
    $(div).remove();
    return (w1 - w2);
}

function diffTimes(greater, lesser) {
	if(typeof greater === 'string') {
		greater = parseInt(greater, 10);
		if(isNaN(greater)) {
			greater = 0;
		}
	}
	if(typeof lesser === 'string') {
		lesser = parseInt(lesser, 10);
		if(isNaN(lesser)) {
			lesser = 0;
		}
	}
	greater /= 100.0;
	lesser /= 100.0;
	if(greater % 1.0 < 1.0) {
		greater = Math.ceil(greater) + 0.5;
	}
	if(lesser % 1.0 < 1.0) {
		lesser = Math.ceil(lesser) + 0.5;
	}
	
	return (greater - lesser);
}

//courseSchedule should be a CourseSchedule object
function CourseScheduleView(courseSchedule) {
	this.courseSchedule = courseSchedule;
	
	//create the scheduleElement, which can just be injected into the page
	var scheduleElement = $('<div/>', { 'class' : 'schedule', 'id' : 'schedule_view_' + courseSchedule.scheduleId });
	this.getElement = function() {
		return scheduleElement;
	};
	
	scheduleElement.append($('<h1/>', {text : courseSchedule.title}));
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
						prevHourDiff = diffTimes(dayArr[section].startTime, courseSection.endTime);
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
					var courseTitle = $('<h1/>', {text : courseSection.dept + ' ' + courseSection.number + '-' + courseSection.section});
					var coursePlace = $('<h2/>', {text : courseSection.getPlace()});
					var courseTime = $('<h3/>', {text : courseSection.getTimes()});
					dayListElement.append($('<li/>', {'style' : 'height:' + numPixels + 'px;'}).append(courseTitle).append(coursePlace).append(courseTime));
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

//courseScheduleList should be a CourseScheduleList object
function CourseScheduleViewManager(courseScheduleList) {
	this.courseScheduleViewMap = {};
	this.courseScheduleList = courseScheduleList;
	
	function getCourseScheduleViewCached(localCourseScheduleViewMap, schedule) {
		if(schedule === null) {
			return null;
		}
		if(localCourseScheduleViewMap.hasOwnProperty(schedule.scheduleId)) {
			return localCourseScheduleViewMap[schedule.scheduleId];
		} else {
			var scheduleView = new CourseScheduleView(schedule);
			localCourseScheduleViewMap[schedule.scheduleId] = scheduleView;
			return scheduleView;
		}
	}
	
	this.size = function() {
		return this.courseScheduleList.size();
	};
	
	this.getScheduleView = function(key) {
		return getCourseScheduleViewCached(this.courseScheduleViewMap, this.courseScheduleList.getSchedule(key));
	};
	
	this.getNextScheduleView = function() {
		return getCourseScheduleViewCached(this.courseScheduleViewMap, this.courseScheduleList.getNextSchedule());
	};
	
	this.getCurrentScheduleView = function() {
		return getCourseScheduleViewCached(this.courseScheduleViewMap, this.courseScheduleList.getCurrentSchedule());
	};
	
	this.getPrevScheduleView = function() {
		return getCourseScheduleViewCached(this.courseScheduleViewMap, this.courseScheduleList.getPrevSchedule());
	};
}

//course should be a sequential array of objects that support getHeader(), getDetail(), getAction()
function ScheduleItemListView(items, breadCrumbText, aClass, aHTML) {
	this.items = items;
	this.anchors = [];
	if(breadCrumbText !== undefined) {
		this.breadCrumbText = breadCrumbText;
	}
	var listElement = $('<ul/>', {'class' : 'schedule_item_list'});
	this.getElement	= function() {
		return listElement;
	};
	
	var item;
	var obj;
	var curLetter = '';
	for(item = 0; item < items.length; item++) {
		obj = items[item];
		var listItem = $('<li/>');
		if(curLetter !== obj.getHeader().toUpperCase().charAt(0)) {
			curLetter = obj.getHeader().toUpperCase().charAt(0);
			this.anchors.push([item, curLetter]);
		}
		var aTag = $('<a/>', {'href' : '#' + obj.getAction()});
		if(aClass !== undefined) {
			aTag.addClass(aClass);
		}
		if(aHTML !== undefined) {
			aTag.html(aHTML);
		}
		listItem.append(aTag);
		listItem.append($('<h1/>', {text:obj.getHeader()}));
		listItem.append($('<p/>', {text:obj.getDetail()}));
		listElement.append(listItem);
	}
	
	if(this.anchors.length === 1) {
		this.anchors[0][1] = 'Top';
	}
}

(function( $ ){
	var methods = {
		init : function(options) {
			var $this = $(this);
			return $this.each(function() {
				var settings = {
					width : '400px',
					height : '400px',
					breadCrumbsHeight: '20px',
					scrollListWidth: '15px',
					easing: 'easeInOutQuint',
					duration: 750
				};
				
				if (options !== undefined) { 
					$.extend( settings, options );
				}
				var data = $this.data('ScheduleItemPicker');
				if($.isEmptyObject(data)) {
					data = {
						slideContainer : $('<div/>', {'style' : 'position:absolute; top:' + settings.breadCrumbsHeight + '; left:' + settings.scrollListWidth + ';'}),
						onScreen : $('<div/>', {'style' : 'position:absolute; top:0;'}),
						offScreen : $('<div/>', {'style' : 'position:absolute; top:0;'}),
						breadCrumbs : $('<ul/>', {'class' : 'schedule_item_list_breadcrumbs', 'style' : 'position:absolute; top:0; left:' + settings.scrollListWidth + ';'}),
						scrollList : $('<ul/>', {'class' : 'schedule_item_list_scrollList', 'style' : 'position:absolute; top:' + settings.breadCrumbsHeight + '; left:0;'}),
						listStack : (new FlexiStack()),
						settings : settings
					};
					$this.data('ScheduleItemPicker', data);
				}
				
				
				var fullHeight = parseInt(settings.breadCrumbsHeight, 10) + parseInt(settings.height, 10) + 'px';
				var fullWidth = parseInt(settings.scrollListWidth, 10) + parseInt(settings.width, 10) + 'px';
				$this.css('height', fullHeight);
				$this.css('width', fullWidth);
				$this.css('overflow', 'hidden');

				data.slideContainer.css('overflow', 'hidden');
				data.slideContainer.css('height', settings.height);
				data.slideContainer.css('width', settings.width);
				
				var widthWithScrollBars = (parseInt(settings.width, 10) - scrollbarWidth()).toString() + 'px';
				
				data.onScreen.css('width', widthWithScrollBars);
				data.onScreen.css('height', settings.height);
				data.onScreen.css('left', 0);
				data.onScreen.css('overflow-x', 'hidden');
				data.onScreen.css('overflow-y', 'auto');
				
				data.offScreen.css('width', widthWithScrollBars);
				data.offScreen.css('height', settings.height);
				data.offScreen.css('left', settings.width);
				data.offScreen.css('overflow-x', 'hidden');
				data.offScreen.css('overflow-y', 'auto');
				
				data.breadCrumbs.css('left', 0);
				
				$this.append(data.breadCrumbs);
				$this.append(data.scrollList);
				$this.append(data.slideContainer);
				data.slideContainer.append(data.onScreen);
				data.slideContainer.append(data.offScreen);
				
				var scrollToOptions = {duration : data.settings.duration, easing : data.settings.easing, axis : 'y'};
				$('#' + $this.attr('id')).delegate('ul.schedule_item_list_scrollList li a', 'click', function() {
					data.onScreen.scrollTo('li:eq(' + $(this).attr('href').replace('#','') + ')', scrollToOptions);
					return false;
				});
				
				return $this;
			});
		},
		
		push : function(listView, reverse, breadCrumbsManaged) {
			var $this = $(this);
			var data = $this.data('ScheduleItemPicker');
			if($.isEmptyObject(data)) {
				$.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $this;
			}
			
			function resetOffScreen() {
				data.offScreen.html('');
				data.offScreen.scrollTop(0);
			}
			
			data.offScreen.html('').append(listView.getElement());
			
			if(reverse === undefined || reverse === false) {
				data.offScreen.css('left', data.settings.width);
				data.onScreen.animate({left:'-' + data.settings.width}, data.settings.duration, data.settings.easing, resetOffScreen);
			} else {
				data.offScreen.css('left', '-' + data.settings.width);
				data.onScreen.animate({left:data.settings.width}, data.settings.duration, data.settings.easing, resetOffScreen);
			}
			
			data.offScreen.animate({left: data.settings.scrollListWidth}, data.settings.duration, data.settings.easing);
			
			var temp = data.offScreen;
			data.offScreen = data.onScreen;
			data.onScreen = temp;
			data.slideContainer.scrollTop(0);
			
			if(breadCrumbsManaged === undefined || breadCrumbsManaged === false) {
				if(data.listStack.size()) {
					data.breadCrumbs.append('<li> &gt; <a href="#' + (data.listStack.size() - 1).toString() + '">' + data.listStack.top().breadCrumbText + '</a></li>');
				}
			}
			
			var i;
			var anchors = listView.anchors;
			var tempList = $('<ul/>');
			for(i = 0; i < anchors.length; i++) {
				var listItem = $('<li/>');
				listItem.append($('<a/>', {href : '#' + anchors[i][0], text : anchors[i][1]}));
				tempList.append(listItem);
			}
			data.scrollList.html(tempList.html());
			
			data.listStack.push(listView);
			return $this;
		},
		
		goto : function(index) {
			var $this = $(this);
			var data = $this.data('ScheduleItemPicker');
			if($.isEmptyObject(data)) {
				$.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $this;
			}
			
			if(typeof index === 'string') {
				index = parseInt(index, 10);
			}
			
			if(index >= data.listStack.size() - 1 || index < 0) {
				$.error('ScheduleItemPicker: Bad stack location');
				return $this;
			}
			if(index === 0) {
				return $this.ScheduleItemPicker('reset');
			} else {
				$('#' + $this.attr('id') + ' ul.schedule_item_list_breadcrumbs li').slice(index).remove();
				var newTop = data.listStack.pop(index);
				return $this.ScheduleItemPicker('push', newTop, true, true);
			}
		},
		
		reset : function() {
			var $this = $(this);
			var data = $this.data('ScheduleItemPicker');
			if($.isEmptyObject(data)) {
				$.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $this;
			}
			data.breadCrumbs.html('');
			var first = data.listStack.pop(0);
			return $this.ScheduleItemPicker('push', first, true);
		},
		
		bindItem : function(type, callback) {
			var $this = $(this);
			var data = $this.data('ScheduleItemPicker');
			if($.isEmptyObject(data)) {
				$.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $this;
			}
			
			$('#' + $this.attr('id')).undelegate('ul.schedule_item_list li a', type);
			$('#' + $this.attr('id')).delegate('ul.schedule_item_list li a', type, function() {
				var courseObj = data.listStack.top().items[$(this).parent().index()];
				callback.apply(this, [courseObj]);
				return false;
			});
			
			return $this;
		},
		
		bindBreadCrumb : function(type, callback) {
			var $this = $(this);
			var data = $this.data('ScheduleItemPicker');
			if($.isEmptyObject(data)) {
				$.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $this;
			}
			
			$('#' + $this.attr('id')).undelegate('ul.schedule_item_list_breadcrumbs li a', type);
			$('#' + $this.attr('id')).delegate('ul.schedule_item_list_breadcrumbs li a', type, callback);
			
			return $this;
		},
		
		stackSize : function() {
			var $this = $(this);
			var data = $this.data('ScheduleItemPicker');
			if($.isEmptyObject(data)) {
				$.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $this;
			}
			
			return data.listStack.size();
		}
	};
		
	$.fn.ScheduleItemPicker = function(method) {
	    if(!this.attr('id')) {
			$.error('ScheduleItemPicker: jQuery.ScheduleItemPicker requires a container with a valid id attribute');
			return this;
	    }
		if (methods.hasOwnProperty(method)) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if ( typeof method === 'object' || !method ) {
			return methods.init.apply(this, arguments);
		}
		else {
			$.error('ScheduleItemPicker: Method ' +  method + ' does not exist on jQuery.ScheduleItemPicker');
			return this;
		}
	};
}(jQuery));

var checkMarkSymbolEntity = '&#10004;';

(function( $ ){
	var methods = {
		//scheduleList should be a CourseScheduleList object
		init : function(scheduleList) {
			var $this = $(this);
			return $this.each(function() {
				if(scheduleList === undefined || !scheduleList.size()) {
					$.error('ScheduleListViewer: jQuery.ScheduleListViewer requires a valid scheduleList argument');
					return $this;
				}
				
				var data = $this.data('ScheduleListViewer');
				if($.isEmptyObject(data)) {
					data = {
						scheduleViewManager : (new CourseScheduleViewManager(scheduleList)),
						scheduleDetailContainer : $('<div/>', {'id' : 'schedule_detail'}),
						scheduleMasterContainer : $('<ul/>', {'id' : 'schedule_master'})
					};
					$this.data('ScheduleListViewer', data);
				}
				
				var i;
				var scheduleListSize = scheduleList.size();
				var schedule;
				for(i = 0; i < scheduleListSize; i++) {
					schedule = scheduleList.getSchedule(i);
					data.scheduleMasterContainer.append($('<li/>', {text : 'Schedule ' + i.toString()}).prepend($('<a/>', {'href' : '#' + schedule.scheduleId})));
				}
				
				data.scheduleMasterContainer.find('li a').click(function() {
					if($(this).html().trim()) {
						$(this).html('');
					}
					else {
						$(this).html(checkMarkSymbolEntity);
					}
					$(this).toggleClass('schedule_on');
					$this.ScheduleListViewer('toggleScheduleView', $(this).attr('href').replace('#',''));
					return false;
				});
				
				$this.append(data.scheduleDetailContainer);
				$this.append(data.scheduleMasterContainer);
				
				data.scheduleMasterContainer.find('li a:first').toggleClass('schedule_on').html(checkMarkSymbolEntity);
				return $this.ScheduleListViewer('toggleScheduleView', scheduleList.getSchedule(0).scheduleId);
			});
		},
		
		toggleScheduleView : function(id) {
			var $this = $(this);
			var data = $this.data('ScheduleListViewer');
			if($.isEmptyObject(data)) {
				$.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
				return $this;
			}
			var fullId = '#schedule_view_' + id;
			var schedule = $(fullId);
			if(schedule.length) {
				schedule.remove();
			}
			else {
				schedule = data.scheduleViewManager.getScheduleView(id);
				if(schedule === null) {
					$.error('ScheduleListViewer: jQuery.ScheduleListViewer could not toggle id' + id);
				} else {
					data.scheduleDetailContainer.append(schedule.getElement());
				}
			}
			return $this;
		},
		
		showNextScheduleView : function() {
			var $this = $(this);
			var data = $this.data('ScheduleListViewer');
			if($.isEmptyObject(data)) {
				$.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
				return $this;
			}
			var newScheduleView = data.scheduleViewManager.getNextScheduleView();
			if(newScheduleView !== null) {
				data.scheduleDetailContainer.html('');
				data.scheduleDetailContainer.append(newScheduleView.getElement());
			}
		},
		
		showPrevScheduleView : function() {
			var $this = $(this);
			var data = $(this).data('ScheduleListViewer');
			if($.isEmptyObject(data)) {
				$.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
				return $this;
			}
			var newScheduleView = data.scheduleViewManager.getPrevScheduleView();
			if(newScheduleView !== null) {
				data.scheduleDetailContainer.html('');
				data.scheduleDetailContainer.append(newScheduleView.getElement());
			}
		}
	};
		
	$.fn.ScheduleListViewer = function(method) {
	    if(!this.attr('id')) {
			$.error('ScheduleListViewer: jQuery.ScheduleListViewer requires a container with a valid id attribute');
			return this;
	    }
		if (methods.hasOwnProperty(method)) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply(this, arguments);
		}
		else {
			$.error('ScheduleListViewer: Method ' +  method + ' does not exist on jQuery.ScheduleListViewer');
			return this;
		}
	};
}(jQuery));