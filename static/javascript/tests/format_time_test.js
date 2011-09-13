function formatTime(time) {
    var time = parseInt(time, 10);
    var hours = Math.floor(time / 100);
    var minutes = time % 100;
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

var times = ['000', '030', '100', '130', '200', '230', '300', '330', '400', '430', '500', '530', '600', '630', '700', '730', '800', '830', '900', '930', '1000', '1030', '1100', '1130', '1200', '1230', '1300', '1330', '1400', '1430', '1500', '1530', '1600', '1630', '1700', '1730', '1800', '1830', '1900', '1930', '2000', '2030', '2100', '2130', '2200', '2230', '2300', '2330'];
var timesFormatted = ['0:00a', '0:30a', '1:00a', '1:30a', '2:00a', '2:30a', '3:00a', '3:30a', '4:00a', '4:30a', '5:00a', '5:30a', '6:00a', '6:30a', '7:00a', '7:30a', '8:00a', '8:30a', '9:00a', '9:30a', '10:00a', '10:30a', '11:00a', '11:30a', '12:00p', '12:30p', '1:00p', '1:30p', '2:00p', '2:30p', '3:00p', '3:30p', '4:00p', '4:30p', '5:00p', '5:30p', '6:00p', '6:30p', '7:00p', '7:30p', '8:00p', '8:30p', '9:00p', '9:30p', '10:00p', '10:30p', '11:00p', '11:30p'];

var i = 0;
var formattedTime = '';

for (i = 0; i < times.length; i++) {
    formattedTime = formatTime(times[i]);
    if (formattedTime !== timesFormatted[i]) {
        print('Time Formatter Test Failed!');
        print(formattedTime + ' !== ' + timesFormatted[i]);
        quit();
    }
}

print('Time Formatter Test Passed!')
