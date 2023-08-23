<?php
include("../../connection.php");

$q = strtolower($_GET['term']);
$query = "SELECT trim(kdgdg), trim(nmgdg) FROM kmmstgdg WHERE trim(kdgdg) LIKE '%$q%' OR  trim(nmgdg) LIKE '%$q%'";
$query = mysql_query($query);
$num = mysql_num_rows($query);

if($num > 0){
  	while ($row = mysql_fetch_array($query)){
      $row_set[] = array('value' => stripslashes($row[0]),'label' => stripslashes($row[0]),'nama' => stripslashes($row[1]));
  	}
echo json_encode($row_set);
}
?>