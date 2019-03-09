$(document).ready(function() {
	//$('.fancybox').fancybox();
	$(".tablesorter").tablesorter(
		{sortList: [[0,1]],
		headers: {
		  1: {
			sorter: "nums"
		  },
		  2: {
			sorter: "digit"
		  },
		  3: {
			sorter: "digit"
		  }
		}}
	);
});

