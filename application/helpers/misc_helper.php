<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function linkCSS($name)
{
	echo '<link href="' . base_url('resources/css/' . $name . '.css') . '" rel="stylesheet" type="text/css">';
}

function insertScript($name)
{
	echo '<script src="' . base_url('resources/js/' . $name . '.js') . '" type="text/javascript"></script>';
}
