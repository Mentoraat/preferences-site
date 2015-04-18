<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Insert a CSS file into the page.
 *
 * @param string $name The name of the css file inside resources/css/.
 * @return void
 */
function linkCSS($name)
{
	echo '<link href="' . base_url('resources/css/' . $name . '.css') . '" rel="stylesheet" type="text/css">';
}

/**
 * Insert a JS file into the page.
 *
 * @param string $name The name of the js file inside resources/js/.
 * @return void
 */
function insertScript($name)
{
	echo '<script src="' . base_url('resources/js/' . $name . '.js') . '" type="text/javascript"></script>';
}
