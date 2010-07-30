<?

// This is for Pacific time
function WAOpen()
{
	//Wolverine Access, to these scripts should always appeear closed, 
	//because the database is loaded separately
	//reason 1: the new WolverineAccess is so slow
	//reason 2: the only method I've found to access WA is using lynx which I don't have on a computer with php
	return false;
	/*
    $data = getDate();
    $day = $data['wday'];
    $hours = $data['hours'];

    switch ($day)
    {
	case 0:
	    if ($hours < 9 || $hours > 21) return false;
	    break;
	case 1: case 2: case 3: case 4: case 5:
	    if ($hours < 4 || $hours > 23) return false;
            break;
	case 6:
	    if ($hours < 4 || $hours > 16) return false;
	    break;
    }
    return true;
	*/
    
}

?>