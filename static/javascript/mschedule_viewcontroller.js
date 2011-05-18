/* Requires jQuery, jQueryUI Effects Core, and mschedule_model.js */

var pixelsPerHour = 40;

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
	var scheduleElement = $('<div/>', { 'class' : 'schedule', 'id' : '#' + courseSchedule.scheduleId });
	this.getElement = function() {
		return scheduleElement;
	};
	
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

//courseScheduleList should be a CourseScheduleList object
function CourseScheduleViewManager(courseScheduleList) {
	this.courseScheduleViewMap = {};
	this.courseScheduleList = courseScheduleList;
	
	function getCourseScheduleViewCached(schedule) {
		if(schedule === null) {
			return null;
		}
		if(this.courseScheduleViewMap.hasOwnProperty(schedule.scheduleId)) {
			return this.courseScheduleViewMap[schedule.scheduleId];
		} else {
			var scheduleView = new CourseScheduleView(schedule);
			this.courseScheduleViewMap[schedule.scheduleId] = scheduleView;
			return scheduleView;
		}
	}
	
	this.size = function() {
		return this.courseScheduleList.size();
	};
	
	this.getScheduleView = function(key) {
		return getCourseScheduleViewCached(this.courseScheduleList.getSchedule(key));
	};
	
	this.getNextScheduleView = function() {
		return getCourseScheduleViewCached(this.courseScheduleList.getNextSchedule());
	};
	
	this.getCurrentScheduleView = function() {
		return getCourseScheduleViewCached(this.courseScheduleList.getCurrentSchedule());
	};
	
	this.getPrevScheduleView = function() {
		return getCourseScheduleViewCached(this.courseScheduleList.getPrevSchedule());
	};
}

//course should be a sequential array of objects that support getHeader(), getDetail(), getAction()
function ScheduleItemListView(items, breadCrumbText) {
	this.items = items;
	this.breadCrumbText = breadCrumbText;
	var listElement = $('<ul/>', {'class' : 'schedule_item_list'});
	this.getElement	= function() {
		return listElement;
	};
	
	var item;
	var obj;
	for(item = 0; item < items.length; item++) {
		obj = items[item];
		var listItem = $('<li/>');
		listItem.append($('<a/>', {'href' : '#' + obj.getAction()}));
		listItem.append($('<h1/>', {text:obj.getHeader()}));
		listItem.append($('<p/>', {text:obj.getDetail()}));
		listElement.append(listItem);
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
					breadCrumbsHeight: '20px'
				};
				
				if (options !== undefined) { 
					$.extend( settings, options );
				}
				var data = $this.data('ScheduleItemPicker');
				if($.isEmptyObject(data)) {
					data = {
						slideContainer : $('<div/>', {'style' : 'position:absolute; top:20px;'}),
						onScreen : $('<div/>', {'style' : 'position:absolute; top:0;'}),
						offScreen : $('<div/>', {'style' : 'position:absolute; top:0;'}),
						breadCrumbs : $('<ul/>', {'class' : 'schedule_item_list_breadcrumbs', 'style' : 'position:absolute; top:0px;'}),
						listStack : (new FlexiStack()),
						settings : settings
					};
					$this.data('ScheduleItemPicker', data);
				}
				
				
				var fullHeight = parseInt(settings.breadCrumbsHeight, 10) + parseInt(settings.height, 10) + 'px';
				$this.css('height', fullHeight);
				$this.css('width', settings.width);
				$this.css('position', 'relative');
				
				data.slideContainer.css('overflow-x', 'hidden');
				data.slideContainer.css('overflow-y', 'scroll');
				data.slideContainer.css('overflow-y', 'scroll');
				data.slideContainer.css('height', settings.height);
				data.slideContainer.css('width', settings.width);
				
				data.onScreen.css('width', settings.width);
				data.onScreen.css('height', settings.height);
				data.onScreen.css('left', 0);
				
				
				data.offScreen.css('width', settings.width);
				data.offScreen.css('height', settings.height);
				data.offScreen.css('left', settings.width);
				
				data.breadCrumbs.css('left', 0);
				
				$this.append(data.breadCrumbs);
				$this.append(data.slideContainer);
				data.slideContainer.append(data.onScreen);
				data.slideContainer.append(data.offScreen);
				
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
				var temp = data.offScreen;
				data.offScreen = data.onScreen;
				data.onScreen = temp;
				data.offScreen.html('');
			}
			
			data.offScreen.html('').append(listView.getElement());
			
			if(reverse === undefined || reverse === false) {
				data.offScreen.css('left', data.settings.width);
				data.onScreen.animate({left:'-' + data.settings.width}, 750, 'easeOutQuart', resetOffScreen);
			} else {
				data.offScreen.css('left', '-' + data.settings.width);
				data.onScreen.animate({left:data.settings.width}, 750, 'easeOutQuart', resetOffScreen);
			}
			
			data.offScreen.animate({left:'0'}, 750, 'easeOutQuart');
			
			if(breadCrumbsManaged === undefined || breadCrumbsManaged === false) {
				if(data.listStack.size()) {
					data.breadCrumbs.append('<li> > <a href="#' + (data.listStack.size() - 1) + '">' + data.listStack.top().breadCrumbText + '</a></li>');
				}
			}
			
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
			
			$('#' + $this.attr('id') + ' ul.schedule_item_list li a').die(type);
			$('#' + $this.attr('id') + ' ul.schedule_item_list li a').live(type, callback);
			
			return $this;
		},
		
		bindBreadCrumb : function(type, callback) {
			var $this = $(this);
			var data = $this.data('ScheduleItemPicker');
			if($.isEmptyObject(data)) {
				$.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $this;
			}
			
			$('#' + $this.attr('id') + ' ul.schedule_item_list_breadcrumbs li a').die(type);
			$('#' + $this.attr('id') + ' ul.schedule_item_list_breadcrumbs li a').live(type, callback);
			
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
						scheduleDetailContainer : $('<div/>', {'id' : '#schedule_master'}),
						scheduleMasterContainer : $('<ul/>', {'id' : '#schedule_detail'})
					};
					$this.data('ScheduleListViewer', data);
				}
				
				var i;
				var scheduleListSize = scheduleList.size();
				var schedule;
				for(i = 0; i < scheduleListSize; i++) {
					schedule = scheduleList.getSchedule(i);
					data.scheduleMasterContainer.append($('<li><a href-"#' + schedule.scheduleId + '"></a>Schedule ' + i + '</li>'));
				}
				
				var superContainerId = $this.attr('id');
				$('#schedule_detail li a').click(function() {
					$this.toggleClass('hidden');
					$(superContainerId).ScheduleListViewer('toggleScheduleView', $this.attr('href'));
					return false;
				});
				
				return $this;
			});
		},
		
		toggleScheduleView : function(id) {
			var $this = $(this);
			var data = $this.data('ScheduleListViewer');
			if($.isEmptyObject(data)) {
				$.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
				return $this;
			}
			var schedule = $(id);
			if(schedule.length) {
				schedule.remove();
			}
			else {
				schedule = data.scheduleViewManager.getScheduleView(schedule.attr('id'));
				if(schedule === null) {
					$.error('ScheduleListViewer: jQuery.ScheduleListViewer could not toggle id' + id);
				} else {
					data.scheduleDetailContainer.append(schedule);
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
				data.scheduleDetailContainer.append(newScheduleView);
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
				data.scheduleDetailContainer.append(newScheduleView);
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