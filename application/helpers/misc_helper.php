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

$defaultMapper = function($item)
{
	return $item;
};

/**
 * Generates a list by applying $mapper to each item.
 *
 * @param array $list   The list to show
 * @param function $mapper The mapper that will show the actual content. Default value is the default mapper (simply returning the item).
 */
function showAsList($list, $mapper=NULL)
{
	if ($mapper === NULL)
	{
		$mapper = $defaultMapper;
	}

	echo '<ul>';

	foreach ($list as $item)
	{
		echo '<li>' . $mapper($item) . '</li>';
	}

	echo '</ul>';
}
