print("Starting Time Formatter Test");

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

var i = 0;

for (i = 0; i < times.length; i++) {
    print(times[i].toString() + ' == ' + formatTime(times[i]))
}

print('Ending Time Formatter Test')
