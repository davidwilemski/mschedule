function init()
{
	populatefirst();
        optionTest = true;
        lgth = document.forms[0].course.options.length - 1;
        document.forms[0].course.options[lgth] = null;
        if (document.forms[0].course.options[lgth]) optionTest = false;
}


function populate()
{
        if (!optionTest) return;
        var box = document.forms[0].first;
        var number = box.options[box.selectedIndex].value;
        if (!number) return;
        var list = store[number];
        var box2 = document.forms[0].course;
        box2.options.length = 0;
        for(i=0;i<list.length;i+=2)
        {
                box2.options[i/2] = new Option(list[i],list[i+1]);
        }
}

function populatefirst()
{
        var list = depts;
        var box2 = document.forms[0].first;
        box2.options.length = 0;
	box2.options[0] = new Option('Choose a Department', '');
        for(i=0;i<list.length;i+=2)
        {
                box2.options[i/2 + 1] = new Option(list[i],list[i+1]);
        }
}
