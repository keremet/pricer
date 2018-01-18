<?php
class oftTable{
	static function init($name,$id = null){
		echo "<center><font size=\"4\"><b>$name</b></font><table ".(($id)?"id=\"$id\" ":"")."border=\"1\" bordercolor=\"#000000\" cellpadding=\"1\" cellspacing=\"0\">";
	}
	static function header($h){
		echo "<tr>";
		foreach ($h as $i => $value) {
		    echo "<td><i><p align=\"CENTER\"><font size=\"2\">$value</font></p></i>";
		}
		echo "</tr>";
	}
	static function row($r){
		echo "<tr>";
		foreach ($r as $i => $value) {
		    echo "<td>$value</td>";
		}
		echo "</tr>";
	}
	static function end(){
		echo "</table>";
	}

	static function addCol($a,$num,$value){
		for($i=count($a);$i>$num;$i--){
			$a[$i]=$a[$i-1];
		}
		$a[$num]=$value;
		return $a;
	}
};	

?>

<script type="text/javascript">

	function oftTableHeader(table) {
		
		var n = arguments.length;
		var r = table.insertRow();
		for(var i = 1; i < n; i++) {
			var cell = r.insertCell();
			cell.innerHTML =  "<i><p align=\"CENTER\"><font size=\"2\">" + arguments[i] + "</font></p></i>";
		}
	}

</script>
