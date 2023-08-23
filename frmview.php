<?php
include("../../configuration.php");
include("../../connection.php");
include("actsearch.php");

date_default_timezone_set("Asia/Bangkok");

// Cek Get Data
if(isset($_POST['txtpage'])){
  $txtpage = $_POST['txtpage'];
  $showPage = $txtpage;
  $noPage = $txtpage;
}else{
  $txtpage = 1;
  $showPage = $txtpage;
  $noPage = $txtpage;
}
if(isset($_POST['txtperpage'])){
  $txtperpage=$_POST['txtperpage'];
}else{
  $txtperpage=10;
}

$offset = ($txtpage - 1) * $txtperpage;
$sqlLIMIT = " LIMIT $offset, $txtperpage";
$sqlWHERE = " ";
$sqlWHERE_1 = " ";

if(isset($_POST['txtfield'])){
  if($_POST['txtfield']!=''){
    $txtfield = $_POST['txtfield'];

    if(isset($_POST['txtparameter'])){
      if ($_POST['txtparameter']!=''){
        $txtparameter = $_POST['txtparameter'];
      }
    }

    if(isset($_POST['txtdata'])){
      if ($_POST['txtdata']!=''){
        $txtdata = $_POST['txtdata'];
      }
    }

    $txtfieldx = explode("|",rtrim($txtfield,'|'));
    $txtparameterx = explode("|",rtrim($txtparameter,'|'));
    $txtdatax = explode("|",rtrim($txtdata,'|'));

    for($a=0;$a<count($txtfieldx);$a++){
      if ($txtfieldx[$a] == "nmdept") {
        $sqlWHERE_1 .= multisearch('kmmstdeptgdg',$txtfieldx[$a],$txtparameterx[$a],$txtdatax[$a]);
      }
      else {
        $sqlWHERE .= multisearch('clhambilbrg',$txtfieldx[$a],$txtparameterx[$a],$txtdatax[$a]);
      }
    }
  }
}


$hpukdgdg = str_replace("WH", "", $_SESSION[$domainApp."_myxdept"]);
if(isset($_POST['all_data'])){
  $all_data = trim($_POST['all_data']);

  if ($all_data != 1) {
    $year = date("Y");
    $all_data = " AND YEAR(a.hputgl) =".$year;
  }
  else {
    $all_data = " ";
  }
}

$sqlWHEREx = "AND hpukdgdg = '".$hpukdgdg."' ".$all_data;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Form View</title>
</head>
<?php
$xrdm = date("YmdHis");
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css?verion=$xrdm\" />";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/frmstyle.css?version=$xrdm\" />";
?>

  <script type="text/javascript">

  </script>


  <body>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
         <div id="frmisi">
          <table id="myTable" class="table">
            <thead>
              <tr>
               <th align="center">No.</th>
               <th align="center">...</th>
               <th align="center">No. Bukti</th>
               <th align="center">Kode Gudang</th>
               <th align="center">Kode Transaksi</th>
               <th align="center">Tanggal</th>
               <th align="center">Departemen</th>
               <th align="center">No. MP</th>
               <th align="center">Kode Pekerjaan</th>
               <!-- <th align="center">Realisasi</th> -->
             </tr>
           </thead>
           <tbody>
            <?php
            $sqlORDERBY = "ORDER BY a.hputgl desc, a.access DESC";

            $sql = "SELECT a.hpunobukti, a.hpukdgdg, a.hpukdtrans, a.hpukddept, a.hpunomp, a.hpukdpkj,
                    DATE_FORMAT(a.hputgl,'%d/%m/%Y') AS hputgl,
                    (SELECT nmgdg FROM kmmstgdg WHERE kdgdg = a.hpukdgdg) AS nmgdg,
                    (SELECT nmdept FROM kmmstdeptgdg WHERE kddept = a.hpukddept) AS nmdept
                    FROM clhambilbrg a 
                    WHERE 1 ".$sqlWHEREx." ".$sqlWHERE."
                    ORDER BY a.access DESC";

            $sqlCOUNT = "SELECT count(a.hpunobukti) AS jumlah 
                         FROM clhambilbrg a 
                         WHERE 1 ".$sqlWHEREx." ".$sqlWHERE."";

            // echo($sql);
 
            $result_1=mysql_query($sqlCOUNT,$conn);
            $data_1 = mysql_fetch_array($result_1);
            $count = $data_1["jumlah"];

            $sql=$sql.$sqlLIMIT;
            $result=mysql_query($sql,$conn);

            $jumPage = ceil($count/$txtperpage);
            // if($count>0){
              $row = $offset;
              while ($data = mysql_fetch_array($result, MYSQL_BOTH)){
                $row += 1;
                ?>
                <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                  <td><?php echo $row; ?></td>
                  <td align="center" nowrap><?php echo "<input id='chk'".$data['hpunobukti']." type='checkbox' name='chk'".$data['hpunobukti']." 
                    value='".$data["hpunobukti"]."|".$data["hpunomp"]."|".$data["hpukdpkj"]."' >"; ?>
                  </td>
                  <td style="text-align: left;"><?php echo $data["hpunobukti"]; ?></td>
                  <td><?php echo $data["hpukdgdg"]; ?></td>
                  <td><?php echo $data["hpukdtrans"]; ?></td>
                  <td><?php echo $data["hputgl"]; ?></td>
                  <td style="text-align: left;"><?=$data["nmdept"]?></td>
                  <td><?=$data["hpunomp"]?></td>
                  <td><?=$data["hpukdpkj"]?></td>
                  <!-- <td><img src="img/ok1.png" style="cursor: pointer;" title="Realisasi Pengambilan" onclick="add_rekap('<?=$data["hpunobukti"]?>')"></td> -->
                </tr>
                <?php
              }
              mysql_free_result($result);
            // }
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
          <td><div align="left"><input id="jumpage" name="nmjmlrow" type="hidden" value="<?php echo $jumPage; ?>">Records: <?php echo ($offset + 1); ?> / <?php echo $row; ?> of <?php echo $count; ?> </div></td>
          <td>
            <div align="right">
              <?php

              echo "Page [ ";

// menampilkan link previous

              if ($txtpage > 1) {$prevpage = $txtpage - 1; echo  "<a href='#' onClick='showpage(".$prevpage.")'>&lt;&lt; Prev</a>";}

// memunculkan nomor halaman dan linknya

              for($page = 1; $page <= $jumPage; $page++)
              {
               if ((($page >= $noPage - 10) && ($page <= $noPage + 10)) || ($page == 1) || ($page == $jumPage))
               {
                if (($showPage == 1) && ($page != 2))  echo "...";
                if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
                if ($page == $noPage) echo " <b>".$page."</b> ";
                else echo " <a href='#' onClick='showpage(".$page.")'>".$page."</a> ";
                $showPage = $page;
              }

//    echo " <a href='#' onClick='showpage(".$page.")'>".$page."</a> ";

            }

// menampilkan link next

            if ($txtpage < $jumPage) {$nextpage = $txtpage + 1; echo "<a href='#' onClick='showpage(".$nextpage.")'>Next &gt;&gt;</a>";}

            echo " ] ";

            ?>
          </div>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>
<FORM id="formexport" name="nmformexport" action="export.php" method="post" onsubmit="window.open ('', 'NewFormInfo', 'scrollbars,width=730,height=500')" target="NewFormInfo">
  <input id="txtSQL" name="nmSQL" type="hidden" value="<?php echo $sql; ?>">
  <input id="txtData" name="nmData" type="hidden" value="<?php echo $txtdata; ?>"/>
  <input id="txtField" name="nmField" type="hidden" value="<?php echo $txtfield; ?>"/>
  <input id="txtParameter" name="nmParameter" type="hidden" value="<?php echo $txtparameter; ?>"/>
</FORM>
</body>

</html>
<?php
mysql_close($conn);
?>
