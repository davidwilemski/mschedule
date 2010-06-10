<?php

define(ERROR, "There has been an error.");

function error($source, $message)
{
	print $message;
}

error("somewhere", ERROR);

?>