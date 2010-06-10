<?php

function parseSubject($subject){
	preg_match('/^(.*) \(([^()]*)\)/', $subject, $matches);
	return array('name' => $matches[1], 'abbr' => $matches[2]);
}

