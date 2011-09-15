/* Requires jQuery, jQueryUI Effects Core, jQuery.ScrollTo, and mschedule_model.js */

var pixelsPerHour = 60;
var borderPixelsPerHour = 1;

//Source: http://jdsharp.us/jQuery/minute/calculate-scrollbar-width.php
function scrollbarWidth() {
    var div = $j('<div style="width:50px;height:50px;overflow:hidden;position:absolute;top:-200px;left:-200px;"><div style="height:100px;"></div>');
    $j('body').append(div);
    var w1 = $j('div', div).innerWidth();
    div.css('overflow-y', 'scroll');
    var w2 = $j('div', div).innerWidth();
    $j(div).remove();
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
	if(greater % 1.0 > 0.0) {
		greater = Math.floor(greater) + 0.5;
	}
	if(lesser % 1.0 > 0.0) {
		lesser = Math.floor(lesser) + 0.5;
	}
	
	return (greater - lesser);
}

//courseSchedule should be a CourseSchedule object
function CourseScheduleView(courseSchedule) {
	this.courseSchedule = courseSchedule;
	
	//create the scheduleElement, which can just be injected into the page
	var scheduleElement = $j('<div/>', { 'class' : 'schedule', 'id' : 'schedule_view_' + courseSchedule.scheduleId });
	this.getElement = function() {
		return scheduleElement;
	};
	
	function createEmptyScheduleElem(theClass, pixels) {
		return $j('<li/>', {
		'class' : theClass,
		'style' : 'height:' + pixels.toString() + 'px;'
		});
	}
	
	scheduleElement.append($j('<h1/>', {text : courseSchedule.title}));
	var weekList = $j('<ul/>', {'class' : 'schedule_week'});
	var day;
	for(day in this.courseSchedule.week) {
		if(this.courseSchedule.week.hasOwnProperty(day)) {
			var dayListElement = $j('<ul/>', {'class': 'schedule_day'});
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
							dayListElement.append(createEmptyScheduleElem('day_break', numPixels));
						}
					}
					else if((prevHourDiff = diffTimes(dayArr[section].startTime, this.courseSchedule.baseHour)) > 0) {
						numPixels = Math.ceil(pixelsPerHour * prevHourDiff);
						dayListElement.append(createEmptyScheduleElem('day_empty', numPixels));
					}
					
					courseSection = dayArr[section];
					
					numPixels = Math.ceil(pixelsPerHour * diffTimes(courseSection.endTime,courseSection.startTime));
					var courseTitle = $j('<h1/>', {text : courseSection.dept + ' ' + courseSection.number + '-' + courseSection.section});
					var coursePlace = $j('<h2/>', {text : courseSection.getPlace()});
					var courseTime = $j('<h3/>', {text : courseSection.getTimes()});
					var courseItemDiv = $j('<div/>', {'style' : 'height:' + (numPixels - 2).toString() + 'px;'}).append(courseTitle).append(coursePlace).append(courseTime);
					dayListElement.append($j('<li/>', {'style' : 'height:' + numPixels.toString() + 'px;'}).append(courseItemDiv));
				}
			}
			else {
				dayListElement.append(createEmptyScheduleElem('day_empty', 1));
			}
			
			var weekListItemElement = $j('<li/>');
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
	var listElement = $j('<ul/>', {'class' : 'schedule_item_list'});
	this.getElement	= function() {
		return listElement;
	};
	
	var item;
	var obj;
	var curLetter = '';
	for(item = 0; item < items.length; item++) {
		obj = items[item];
		var listItem = $j('<li/>');
		if(curLetter !== obj.getHeader().toUpperCase().charAt(0)) {
			curLetter = obj.getHeader().toUpperCase().charAt(0);
			this.anchors.push([item, curLetter]);
		}
		var aTag = $j('<a/>', {'href' : '#' + obj.getAction()});
		if(aClass !== undefined) {
			aTag.addClass(aClass);
		}
		if(aHTML !== undefined) {
			aTag.html(aHTML);
		}
		listItem.append(aTag);
		listItem.append($j('<h1/>', {text:obj.getHeader()}));
		listItem.append($j('<p/>', {text:obj.getDetail()}));
		listElement.append(listItem);
	}
	
	if(this.anchors.length === 1) {
		this.anchors[0][1] = 'Top';
	}
}

(function( $j ){
	var methods = {
		init : function(options) {
			var $jthis = $j(this);
			return $jthis.each(function() {
				var settings = {
					width : '400px',
					height : '400px',
					breadCrumbsHeight: '20px',
					scrollListWidth: '15px',
					easing: 'easeInOutQuint',
					duration: 750
				};
				
				if (options !== undefined) { 
					$j.extend( settings, options );
				}
				var data = $jthis.data('ScheduleItemPicker');
				if($j.isEmptyObject(data)) {
					data = {
						slideContainer : $j('<div/>', {'style' : 'position:absolute; top:' + settings.breadCrumbsHeight + '; left:' + settings.scrollListWidth + ';'}),
						onScreen : $j('<div/>', {'style' : 'position:absolute; top:0;'}),
						offScreen : $j('<div/>', {'style' : 'position:absolute; top:0;'}),
						breadCrumbs : $j('<ul/>', {'class' : 'schedule_item_list_breadcrumbs', 'style' : 'position:absolute; top:0; left:' + settings.scrollListWidth + ';'}),
						scrollList : $j('<ul/>', {'class' : 'schedule_item_list_scrollList', 'style' : 'position:absolute; top:' + settings.breadCrumbsHeight + '; left:0;'}),
						listStack : (new FlexiStack()),
						settings : settings
					};
					$jthis.data('ScheduleItemPicker', data);
				}
				
				
				var fullHeight = parseInt(settings.breadCrumbsHeight, 10) + parseInt(settings.height, 10) + 'px';
				var fullWidth = parseInt(settings.scrollListWidth, 10) + parseInt(settings.width, 10) + 'px';
				$jthis.css('height', fullHeight);
				$jthis.css('width', fullWidth);
				$jthis.css('overflow', 'hidden');

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
				
				$jthis.append(data.breadCrumbs);
				$jthis.append(data.scrollList);
				$jthis.append(data.slideContainer);
				data.slideContainer.append(data.onScreen);
				data.slideContainer.append(data.offScreen);
				
				var scrollToOptions = {duration : data.settings.duration, easing : data.settings.easing, axis : 'y'};
				$j('#' + $jthis.attr('id')).delegate('ul.schedule_item_list_scrollList li a', 'click', function() {
					data.onScreen.scrollTo('li:eq(' + $j(this).attr('href').replace('#','') + ')', scrollToOptions);
					return false;
				});
				
				return $jthis;
			});
		},
		
		push : function(listView, reverse, breadCrumbsManaged) {
			var $jthis = $j(this);
			var data = $jthis.data('ScheduleItemPicker');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $jthis;
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
			var tempList = $j('<ul/>');
			for(i = 0; i < anchors.length; i++) {
				var listItem = $j('<li/>');
				listItem.append($j('<a/>', {href : '#' + anchors[i][0], text : anchors[i][1]}));
				tempList.append(listItem);
			}
			data.scrollList.html(tempList.html());
			
			data.listStack.push(listView);
			return $jthis;
		},
		
		goto : function(index) {
			var $jthis = $j(this);
			var data = $jthis.data('ScheduleItemPicker');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $jthis;
			}
			
			if(typeof index === 'string') {
				index = parseInt(index, 10);
			}
			
			if(index >= data.listStack.size() - 1 || index < 0) {
				$j.error('ScheduleItemPicker: Bad stack location');
				return $jthis;
			}
			if(index === 0) {
				return $jthis.ScheduleItemPicker('reset');
			} else {
				$j('#' + $jthis.attr('id') + ' ul.schedule_item_list_breadcrumbs li').slice(index).remove();
				var newTop = data.listStack.pop(index);
				return $jthis.ScheduleItemPicker('push', newTop, true, true);
			}
		},
		
		reset : function() {
			var $jthis = $j(this);
			var data = $jthis.data('ScheduleItemPicker');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $jthis;
			}
			data.breadCrumbs.html('');
			var first = data.listStack.pop(0);
			return $jthis.ScheduleItemPicker('push', first, true);
		},
		
		bindItem : function(type, callback) {
			var $jthis = $j(this);
			var data = $jthis.data('ScheduleItemPicker');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $jthis;
			}
			
			$j('#' + $jthis.attr('id')).undelegate('ul.schedule_item_list li a', type);
			$j('#' + $jthis.attr('id')).delegate('ul.schedule_item_list li a', type, function() {
				var courseObj = data.listStack.top().items[$j(this).parent().index()];
				callback.apply(this, [courseObj]);
				return false;
			});
			
			return $jthis;
		},
		
		bindBreadCrumb : function(type, callback) {
			var $jthis = $j(this);
			var data = $jthis.data('ScheduleItemPicker');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $jthis;
			}
			
			$j('#' + $jthis.attr('id')).undelegate('ul.schedule_item_list_breadcrumbs li a', type);
			$j('#' + $jthis.attr('id')).delegate('ul.schedule_item_list_breadcrumbs li a', type, callback);
			
			return $jthis;
		},
		
		stackSize : function() {
			var $jthis = $j(this);
			var data = $jthis.data('ScheduleItemPicker');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleItemPicker: jQuery.ScheduleItemPicker was not initialized');
				return $jthis;
			}
			
			return data.listStack.size();
		}
	};
		
	$j.fn.ScheduleItemPicker = function(method) {
	    if(!this.attr('id')) {
			$j.error('ScheduleItemPicker: jQuery.ScheduleItemPicker requires a container with a valid id attribute');
			return this;
	    }
		if (methods.hasOwnProperty(method)) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if ( typeof method === 'object' || !method ) {
			return methods.init.apply(this, arguments);
		}
		else {
			$j.error('ScheduleItemPicker: Method ' +  method + ' does not exist on jQuery.ScheduleItemPicker');
			return this;
		}
	};
}(jQuery));

var checkMarkSymbolEntity = '&#10004;';

(function( $j ){
	var methods = {
		//scheduleList should be a CourseScheduleList object
		init : function(scheduleList) {
			var $jthis = $j(this);
			return $jthis.each(function() {
				if(scheduleList === undefined) {
					$j.error('ScheduleListViewer: jQuery.ScheduleListViewer requires a valid scheduleList argument');
					return $jthis;
				}
				
				var data = $jthis.data('ScheduleListViewer');
				if($j.isEmptyObject(data)) {
					data = {
						scheduleViewManager : {},
						scheduleDetailContainer : $j('<div/>', {'id' : 'schedule_detail'}),
						scheduleMasterContainer : $j('<ul/>', {'id' : 'schedule_master'})
					};
					$jthis.data('ScheduleListViewer', data);
				}
				
				return $jthis.ScheduleListViewer('setScheduleList', scheduleList);
			});
		},
		
		setScheduleList : function(scheduleList) {
			var $jthis = $j(this);
			if(scheduleList === undefined) {
				$j.error('ScheduleListViewer: jQuery.ScheduleListViewer requires a valid scheduleList argument');
				return $jthis;
			}
			
			if(scheduleList.size()) {		
				var data = $jthis.data('ScheduleListViewer');
				if($j.isEmptyObject(data)) {
					$j.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
					return $jthis;
				}
				
				$jthis.text('');
				$jthis.css('text-align', '');
				$jthis.css('font-size', '');
				$jthis.css('color', '');
				$jthis.css('height', '');
				$jthis.css('line-height', '');
				
				data.scheduleViewManager = new CourseScheduleViewManager(scheduleList);
				data.scheduleDetailContainer.html('')
				data.scheduleMasterContainer.html('');
				
				var i;
				var scheduleListSize = scheduleList.size();
				var schedule;
				for(i = 0; i < scheduleListSize; i++) {
					schedule = scheduleList.getSchedule(i);
					data.scheduleMasterContainer.append($j('<li/>', {text : 'Schedule ' + i.toString()}).prepend($j('<a/>', {'href' : '#' + schedule.scheduleId})));
				}
				
				data.scheduleMasterContainer.find('li a').click(function() {
					if($j(this).html().trim()) {
						$j(this).html('');
					}
					else {
						$j(this).html(checkMarkSymbolEntity);
					}
					$j(this).toggleClass('schedule_on');
					$jthis.ScheduleListViewer('toggleScheduleView', $j(this).attr('href').replace('#',''));
					return false;
				});
				
				$jthis.append(data.scheduleDetailContainer);
				$jthis.append(data.scheduleMasterContainer);
				
				data.scheduleMasterContainer.find('li a:first').toggleClass('schedule_on').html(checkMarkSymbolEntity);
				return $jthis.ScheduleListViewer('toggleScheduleView', scheduleList.getSchedule(0).scheduleId);
			}
			else {
				$jthis.text("Sorry. There aren't any schedules that match your course list.");
				$jthis.css('text-align', 'center');
				$jthis.css('font-size', '25px');
				$jthis.css('color', '#777');
				$jthis.css('height', '450px');
				$jthis.css('line-height', '450px');
				return $jthis;
			}
		},
		
		toggleScheduleView : function(id) {
			var $jthis = $j(this);
			var data = $jthis.data('ScheduleListViewer');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
				return $jthis;
			}
			var fullId = '#schedule_view_' + id;
			var schedule = $j(fullId);
			if(schedule.length) {
				schedule.remove();
			}
			else {
				schedule = data.scheduleViewManager.getScheduleView(id);
				if(schedule === null) {
					$j.error('ScheduleListViewer: jQuery.ScheduleListViewer could not toggle id' + id);
				} else {
					data.scheduleDetailContainer.append(schedule.getElement());
				}
			}
			return $jthis;
		},
		
		showNextScheduleView : function() {
			var $jthis = $j(this);
			var data = $jthis.data('ScheduleListViewer');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
				return $jthis;
			}
			var newScheduleView = data.scheduleViewManager.getNextScheduleView();
			if(newScheduleView !== null) {
				data.scheduleDetailContainer.html('');
				data.scheduleDetailContainer.append(newScheduleView.getElement());
			}
		},
		
		showPrevScheduleView : function() {
			var $jthis = $j(this);
			var data = $j(this).data('ScheduleListViewer');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
				return $jthis;
			}
			var newScheduleView = data.scheduleViewManager.getPrevScheduleView();
			if(newScheduleView !== null) {
				data.scheduleDetailContainer.html('');
				data.scheduleDetailContainer.append(newScheduleView.getElement());
			}
		},
		
		hasSchedules : function() {
			var $jthis = $j(this);
			var data = $j(this).data('ScheduleListViewer');
			if($j.isEmptyObject(data)) {
				$j.error('ScheduleListViewer: jQuery.ScheduleListViewer was not initialized');
				return $jthis;
			}
			
			return (!$j.isEmptyObject(data.scheduleViewManager) && data.scheduleViewManager.size() > 0);
		}
	};
		
	$j.fn.ScheduleListViewer = function(method) {
	    if(!this.attr('id')) {
			$j.error('ScheduleListViewer: jQuery.ScheduleListViewer requires a container with a valid id attribute');
			return this;
	    }
		if (methods.hasOwnProperty(method)) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply(this, arguments);
		}
		else {
			$j.error('ScheduleListViewer: Method ' +  method + ' does not exist on jQuery.ScheduleListViewer');
			return this;
		}
	};
}(jQuery));
