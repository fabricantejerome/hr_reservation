<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Array helper that converts multidimensional array to linear 
 *
 */
if ( ! function_exists('array_flatten'))
{
	function array_flatten($array, $return)
	{
		for($x = 0; $x < count($array); $x++)
		{
			if(is_array($array[$x]))
			{
				$return = array_flatten($array[$x], $return);
			}
			else
			{
				if(isset($array[$x]))
				{
					$return[] = $array[$x];
				}
			}
		}
		return $return;
	}
}

