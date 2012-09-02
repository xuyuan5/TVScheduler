<?php

function swap(&$input_array, $index_a, $index_b)
{
	if(!is_array($input_array))
	{
		throw new Exception(_("not an array"));
	}
	$temp = $input_array[$index_a];
	$input_array[$index_a] = $input_array[$index_b];
	$input_array[$index_b] = $temp;
}

?>