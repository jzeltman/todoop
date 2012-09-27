<?
	include("funcs.php");
	mysql_connect('localhost', 'root', 'admin');

	if (isset($_POST["add"])) {
		mysql_query("insert into workspace.Tasks (Summary,Benefit,Cost,Risk) values ('".$_POST["summary"]."',".$_POST["benefit"].",".$_POST["cost"].",".$_POST["risk"].")");
	
	}

	
	$a_all = mysql_query_list("select * from workspace.Tasks order by TaskID");
	

	
	
	function getPriority($item) {
		global $a_all;
		
		$a_benefit = array_extract("Benefit",$a_all);
		$a_cost    = array_extract("Cost",$a_all);
		$a_risk    = array_extract("Risk",$a_all);

		$sb = array_sum($a_benefit);
		$sc = array_sum($a_cost);
		$sr = array_sum($a_risk);	

		$p = (($item["Benefit"] / $sb) / (($item["Cost"] / $sc) + ($item["Risk"] / $sr)));
		return($p);
	}
	
	$a_sorted = $a_all;
	foreach ($a_sorted as $k => $a_item) {
		$a_sorted[$k]["Priority"] = getPriority($a_item);
	}
	$a_sorted = array_reverse(array_csort($a_sorted,"Priority"));
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
</head>
<body>

<form method="post" action="home.php">
	<fieldset>
		<legend>New task</legend>
		<dl>
			<dt><input type="text" name="summary" size="30" /></dt>
			<dt>Benefit: <input type="text" name="benefit" size="2" value="1" /></dt>
			<dt>Hours: <input type="text" name="cost" size="2" value="1" /></dt>
			<dt>Risk: <input type="text" name="risk" size="2" value="1" /></dt>
		</dl>
		<p>
			<input type="submit" name="add" value="Add task" />
		</p>
	</fieldset>
</form>
<h3>Simple</h3>
<ul>
<? foreach ($a_all as $a_item) { ?>
	<li><?= $a_item["Summary"]; ?> <?= $a_item["Benefit"]." / ".$a_item["Cost"]." / ".$a_item["Risk"]; ?></li>
<? } ?>
</ul>

<h3>Sorted</h3>
<ul>
<?
	foreach ($a_sorted as $a_item) { 
?>
	<li><?= $a_item["Summary"]; ?> <?= $a_item["Benefit"]." / ".$a_item["Cost"]." / ".$a_item["Risk"]." / ".$a_item["Priority"]; ?></li>
<? } ?>
</ul>

</body>
</html>
