<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * String helper for formating names
 *
 */
if ( ! function_exists('name_format'))
{
	function name_format($fullname)
	{
		$name = explode('-', strtolower($fullname));

		$name = join('-', array_map("ucwords", $name));

		return $name;
	}
}