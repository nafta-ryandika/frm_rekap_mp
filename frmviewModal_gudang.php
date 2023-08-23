<?php
include("connection/connection.php");
include("../../endec.php");
include("actsearch.php");

if(isset($_POST['innoso'])){
  $innoso = $_POST['innoso'];
}

if(isset($_POST['txtpagemodal'])){
  $txtpage = $_POST['txtpagemodal'];
  $showPage = $txtpage;
  $noPage = $txtpage;
}
else{
  $txtpage = 1;
  $showPage = $txtpage;
  $noPage = $txtpage;
}

if(isset($_POST['txtperpagemodal'])){
  $txtperpage=$_POST['txtperpagemodal'];
}
else{
  $txtperpage=15;
}

$offset = ($txtpage - 1) * $txtperpage;
$sqlLIMIT = " LIMIT $offset, $txtperpage";
$sqlWHERE = " ";

if(isset($_POST['txtdatamodal'])){
  if ($_POST['txtdatamodal']!=''){
    $txtdata=$_POST['txtdatamodal'];
  }
}

if(isset($_POST['txtfield'])){
  if ($_POST['txtfield']!=''){
    $txtfield = $_POST['txtfield'];
  }
}
  $sqlWHERE = " AND trim(kdgdg) LIKE '%".$txtdata."%' OR  trim(nmgdg) LIKE '%".$txtdata."%' "; 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Form View</title>
</head>

<?php
$xrdm = date("YmdHis");
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css?verion=$xrdm\" />";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/frmstyle.css?version=$xrdm\" />";
?>
<link rel="stylesheet" href="css/table.css">

<script type="text/javascript">
    $("#table_gudang tbody tr").className = "";

    // var rows = document.getElementById("table_gudang").children[1].children;
    var rows = $("#table_gudang tbody tr");
    var selectedRow = 0;
    var baris = 1;
    document.body.onkeydown = function(e){
        rows[selectedRow].classList.remove("highlight");
        
        var table = document.getElementById("table_gudang");
        if(table){
          if(e.keyCode == 38){
            e.preventDefault();
            selectedRow--;
            baris--;
          } 
          else if(e.keyCode == 40){
            e.preventDefault();
            selectedRow++;
            baris++;
          }
          else if (e.keyCode == 13 && $("#txtdatamodal").focusout()){
            var kdtrans = $('#table_gudang tr:eq('+baris+') td:eq(1)').text();
            var nmtrans = $('#table_gudang tr:eq('+baris+') td:eq(2)').text();
            
            select(kdtrans,nmtrans,"gudang");
          }

          if(selectedRow >= rows.length){
              selectedRow = 0;
              baris = 1;
          } 
          else if(selectedRow < 0){
              selectedRow = rows.length-1;
              baris =  rows.length;
          }
          
          rows[selectedRow].className += "highlight";
      }
    };

    rows[0].className += "highlight";
</script>

<body>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <div id="frmisi">
          <table id="table_gudang" class="table">
            <thead>
              <tr>
                <th align="center">NO</th>
                <th align="center">Kode Gudang</th>
                <th align="center">Nama Gudang</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $sql = "SELECT kdgdg, nmgdg FROM kmmstgdg WHERE 1";
              $sqlCOUNT = "SELECT kdgdg, nmgdg FROM kmmstgdg WHERE 1";

              $sqlCOUNT = $sqlCOUNT.$sqlWHERE;
              $result_1 = mysql_query($sqlCOUNT,$conn);
              $data_1 = mysql_fetch_array($result_1);
              $count = mysql_num_rows($result_1);

              $sql = $sql.$sqlWHERE.$sqlLIMIT;
              $result = mysql_query($sql,$conn);
              $jumPage = ceil($count/$txtperpage);

              if($count>0){
                $row = $offset;
                while ($data = mysql_fetch_array($result)){
                  $row += 1;
                  $kdgdg = $data["kdgdg"];
                  $nmgdg = $data["nmgdg"];
            ?>

                  <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" onclick="select('<?=$kdgdg?>','<?=$nmgdg?>','gudang')" style="cursor: pointer;">
                    <td align="center" nowrap><?=$row?></td>
                    <td style="text-align: left;" nowrap><?=$kdgdg?></td>
                    <td style="text-align: left;" ><?=$nmgdg?></td>
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
    <tr>
      <td>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="info_fieldset">
          <tr>
            <td>
              <div align="left"><input id="jumpagemodal" name="nmjmlrow" type="hidden" value="<?php echo $jumPage; ?>"/>Records: <?php echo ($offset + 1); ?> / <?php echo $row; ?> of <?php echo $count; ?> 
            </div>
          </td>
          <td>
            <div align="right">
              <?php
              echo "Page [ ";
              if ($txtpage > 1) {
                $prevpage = $txtpage - 1; echo  "<a href='#' onClick='showpage_modal(".$prevpage.")'>&lt;&lt; Prev</a>";
              }

              for($page = 1; $page <= $jumPage; $page++){
                if ((($page >= $noPage - 10) && ($page <= $noPage + 10)) || ($page == 1) || ($page == $jumPage)){
                  if (($showPage == 1) && ($page != 2))  echo "...";
                  if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
                  if ($page == $noPage) echo " <b>".$page."</b> ";
                  else echo " <a href='#!' onClick='showpage_modal(".$page.")'>".$page."</a> ";
                  $showPage = $page;
                }
              }

              if ($txtpage < $jumPage) {$nextpage = $txtpage + 1; echo "<a href='#' onClick='showpage_modal(".$nextpage.")'>Next &gt;&gt;</a>";}

              echo " ] ";
              ?>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>

</html>
<?php
mysql_close($conn);
?>
