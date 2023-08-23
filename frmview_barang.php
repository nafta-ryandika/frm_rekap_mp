<?php
include("../../connection.php");

if(isset($_POST['inhpunobukti'])){
  $inhpunobukti = $_POST['inhpunobukti'];
}

$xrdm = date("YmdHis");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Form View</title>
</head>

<!-- DATA TABLE -->
<script type="text/javascript" src="DataTables/datatables.js"></script>
<link rel="stylesheet" href="DataTables/datatables.css?version=<?=$xrdm?>"/>

<?php
$xrdm = date("YmdHis");
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css?verion=$xrdm\" />";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/frmstyle.css?version=$xrdm\" />";
?>

 <script type="text/javascript">
  $(document).ready(function(){
    $('#myTable_barang').DataTable({"lengthMenu": [ [5, 10, 20, 50, -1], [5, 10, 20, 50, "All"] ],
                                    "autoWidth": false, 
                                    "columns": [{ "width": "5%" },{ "width": "30%" },{ "width": "65%" }],
                                    "ordering": false
                                  });
  });
  </script>


  <body>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
         <div id="frmisi">
          <table id="myTable_barang" class="table" style="">
            <thead>
              <tr>
                <th align="center" style="width: 5%;">No.</th>
                <th align="center" style="width: 30%;">Kode Barang</th>
                <th align="center" style="width: 65%;">Nama Barang</th>
             </tr>
           </thead>
           <tbody>
            <?php
              $sql = "SELECT  
                      dpukdbrg, dpunmbrg
                      FROM cldambilbrg 
                      WHERE dpunobukti = '".$inhpunobukti."'
                      GROUP BY dpukdbrg
                      -- ORDER BY dpunmbrg";
              $res = mysql_query($sql,$conn);
              $row = mysql_num_rows($res);
              $num = 0;

              if($row > 0){
                while ($data = mysql_fetch_array($res, MYSQL_BOTH)){
                  $num++;
                  $dpukdbrg = strtoupper(trim($data["dpukdbrg"]));
                  $dpunmbrg = strtoupper(trim($data["dpunmbrg"]));

            ?>
                <tr style="cursor: pointer;" onclick="findclick_rekap('<?=$inhpunobukti."|".$dpukdbrg?>'); change_color(this);">
                  <td><?=$num?></td>
                  <td style="text-align: left;"><?=$dpukdbrg?></td>
                  <td style="text-align: left;"><?=$dpunmbrg?></td>
                </tr>
                <?php
              }
              mysql_free_result($result);
            }
            ?>
          </tbody>
        </table>
      </div>
    </td>
  </tr>
</table>
</body>

</html>
<?php
mysql_close($conn);
?>
