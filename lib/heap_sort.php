<?php
require_once("helper.php");
/*
	Heap Sort algorithm, returns nothing, modifies the array in place,
	a comaparer has to be supplied that takes two parameters, and returns:
		< 0, if the first is smaller than the second
		= 0, if the two are the same
		> 0, if the first is greater than the second
		
	sorting respects key ordering.
	
	
	
	function heapSort(a, count) is
		input:  an unordered array a of length count
 
		(first place a in max-heap order)
		heapify(a, count)
 
		end := count-1 //in languages with zero-based arrays the children are 2*i+1 and 2*i+2
		while end > 0 do
			(swap the root(maximum value) of the heap with the last element of the heap)
			swap(a[end], a[0])
			(put the heap back in max-heap order)
			siftDown(a, 0, end-1)
			(decrease the size of the heap by one so that the previous max value will stay in its proper placement)
			end := end - 1
*/
function heap_sort(&$input_array, $comparer)
{
	if(!is_array($input_array))
	{
		throw new Exception(_("not an array"));
	}

	$count = count($input_array);	
	$input_keys = array_keys($input_array);
	
    heapify($input_array, $input_keys, $count, $comparer);
	
	$end = $count - 1;
	while ( $end > 0 )
	{
		swap($input_keys, $end, 0);
		siftDown($input_array, $input_keys, 0, $end - 1, $comparer);
		$end = $end - 1;
	}
	
	foreach($input_keys as $key)
	{
		$value = $input_array[$key];
		unset($input_array[$key]);
		$input_array[$key] = $value;
	}
}

/* 
	helper function
	function heapify(a, count) is
		(start is assigned the index in a of the last parent node)
		start := count / 2 - 1

		while start >= 0 do
			(sift down the node at index start to the proper place such that all nodes below
			the start index are in heap order)
			siftDown(a, start, count-1)
			start := start - 1
		(after sifting down the root all nodes/elements are in heap order)
*/
function heapify(&$input_array, &$input_keys, $count, $comparer)
{
	$start = $count / 2 - 1;
	
	while ($start >= 0)
	{
		siftDown($input_array, $input_keys, $start, $count - 1, $comparer);
		$start = $start - 1;
	}
}

/*
	helper function
	
	function siftDown(a, start, end) is
		input:  end represents the limit of how far down the heap to sift.
		root := start

		while root * 2 + 1 <= end do          (While the root has at least one child)
			child := root * 2 + 1        (root*2 + 1 points to the left child)
			swap := root        (keeps track of child to swap with)
			(check if root is smaller than left child)
			if a[swap] < a[child]
				swap := child
			(check if right child exists, and if it's bigger than what we're currently swapping with)
			if child < end and a[swap] < a[child+1]
				swap := child + 1
			(check if we need to swap at all)
			if swap != root
				swap(a[root], a[swap])
				root := swap          (repeat to continue sifting down the child now)
			else
				return
*/
function siftDown(&$input_array, &$input_keys, $start, $end, $comparer)
{
	$root = $start;
	
	while ( ($root * 2 + 1) <= $end )
	{
		$child = $root * 2 + 1;
		$swap = $root;
		if ( $comparer($input_array[$input_keys[$swap]], $input_array[$input_keys[$child]]) < 0 )
		{
			$swap = $child;
		}
		if ( ($child < $end) && ($comparer($input_array[$input_keys[$swap]], $input_array[$input_keys[$child+1]]) < 0) )
		{
			$swap = $child + 1;
		}
		if ( $swap != $root )
		{
			swap($input_keys, $root, $swap);
			$root = $swap;
		}
		else
		{
			return;
		}
	}
}
?>