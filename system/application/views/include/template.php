<?php

$this->load->view('include/header');

$this->load->view($navigation);

$this->load->view($view_name, isset($ad) ? $ad : "");

$this->load->view('include/footer');

?>