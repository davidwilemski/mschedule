<?php

class boo
{}

class foo extends boo
{}

$foo = new foo;
var_dump(is_a($foo, "new"));
var_dump(is_a($foo, "boo"));
var_dump(is_a($foo, "foo"));
var_dump(is_a($foo, "object"));
?>