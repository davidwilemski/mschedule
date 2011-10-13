var MScheduleUtils = {
	convertNumBase : function (num, toBase) {
		return parseInt(num.toString(toBase), 10);
	},

	trim : function (str, trimStr) {
		if (trimStr === undefined) {
			//not all browsers support str.trim()
			if (typeof str.trim !== 'undefined') {
				return str.trim();
			}
			else {
				//javascriptkit.com
				return str.replace(/(^\s*)|(\s*$)/g, "");
			}
		}
		else {
			var trimCodes = '', i;
			
			for (i = 0; i < trimStr.length; ++i) {
				trimCodes += '\\' + MScheduleUtils.convertNumBase(trimStr.charCodeAt(i), 8).toString();
			}
			
			var trimReg = new RegExp('(^' + trimCodes + '*)|(' + trimCodes + '*$)','g');
			return str.replace(trimReg, '');
		}
	},

	//Source: http://jdsharp.us/jQuery/minute/calculate-scrollbar-width.php
	browserScrollbarWidth : function () {
	    var div = $j('<div style="width:50px;height:50px;overflow:hidden;position:absolute;top:-200px;left:-200px;"><div style="height:100px;"></div>');
	    $j('body').append(div);
	    var w1 = $j('div', div).innerWidth();
	    div.css('overflow-y', 'scroll');
	    var w2 = $j('div', div).innerWidth();
	    $j(div).remove();
		return (w1 - w2);
	},
	
	browserIsWebkit : function () {
		return (window.navigator.appVersion.toLowerCase().indexOf('webkit') >= 0);
	},
	
	getLocalhostMScheduleDir : function() {
		var dir = MScheduleUtils.trim(window.location.pathname, '/').split('/')[0];
		return (dir === 'index.php' ? '' : dir + '/');
	},
	
	urlForImageName : function (imageName) {
		var url = '';
		if (window.location.hostname.toLowerCase() === 'localhost') {
			url = '/' + MScheduleUtils.getLocalhostMScheduleDir();
		}
		return url + '/static/images/' + imageName;
	},
	
	preloadImage : function (imageName) {
		var img = new Image();
		img.src = MScheduleUtils.urlForImageName(imageName);
		return img;
	}
};