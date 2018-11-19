<?
function money_to_str($v) {
	if($v == null)
		return "";

	$s = "";
	$s = ($v % 10).$s;
	$v = (int)($v/10);
	$s = ".".($v % 10).$s;
	$v = (int)($v/10);
	do {
		$s = ($v % 10).$s;
		$v = (int)($v/10);
	} while ($v > 0);
	return $s;
}

function money_out($v) {
	if($v == null)
		return "";

	return "<p align=\"right\">".money_to_str($v)."</p>";
}
?>
