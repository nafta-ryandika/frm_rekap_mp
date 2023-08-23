<?php
include("../../connection.php");

if(isset($_POST['nobukti'])){
  $nobukti = $_POST['nobukti'];
}

if(isset($_POST['kdbrg'])){
  $kdbrg = $_POST['kdbrg'];
}

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
          <div style="text-align: right; margin-right: 10px;">
            <input id="cmdsave_rekap" class="buttonsave" type="button" name="nmcmdsave" value="Simpan" onclick="simpan_rekap('',0)">
          </div>
        </td>
      </tr>
      <tr>
        <td>
         <div id="frmisi">
          <table id="myTable" class="table">
            <thead>
              <tr>
               <th align="center" style="width: 15%;">No. MP</th>
               <th align="center" style="width: 20%;">Pekerjaan</th>
               <th align="center" style="width: 20%;">Sub Pekerjaan</th>
               <th align="center" style="width: 10%;">Qty</th>
               <th align="center" style="width: 10%;">Satuan</th>
               <th align="center" style="width: 10%;">Sisa</th>
               <th align="center" style="width: 10%;">Realisasi</th>
               <th align="center" style="width: 5%;">Pelunasan<INPUT type="checkbox" onchange="checkAll(this)" name="chk[]" /></th>
             </tr>
           </thead>
           <tbody>
            <?php
              $sql = "SELECT  
                      dpunobukti, dpunomp, dpukdpkj, dpunopkj, dpusubpkj, dpunosubpkj, dpukdbrg, dpunmbrg, 
                      dpuqty, dpusatuan, 
                      IF(dpuqtyout > 0, dpuqtyout, '') AS dpuqtyout, 
                      dpuqtylns, IFNULL(dpunomutasi, '') AS dpunomutasi, 
                      IFNULL(dpunolunas, '') AS dpunolunas, dpucetak, access, komp, userby,
                      (SELECT ket FROM kmmstpkj WHERE pkj = dpukdpkj) AS nmpkj,
                      (SELECT ket FROM kmmstsubpkj WHERE subpkj = dpusubpkj) AS nmsubpkj
                      FROM cldambilbrg
                      WHERE dpunobukti = '".$nobukti."' AND dpukdbrg = '".$kdbrg."'
                      ORDER BY dpunomp, dpunosubpkj";
              $res = mysql_query($sql,$conn);
              $row = mysql_num_rows($res);
              $num = 0;

              if($row > 0){
                while ($data = mysql_fetch_array($res, MYSQL_BOTH)){
                  $num++;
                  $checked = "";
                  $dpunomp = strtoupper(trim($data["dpunomp"]));
                  $dpunopkj = strtoupper(trim($data["dpunopkj"]));
                  $dpukdpkj = strtoupper(trim($data["dpukdpkj"]));
                  $nmpkj = strtoupper(trim($data["nmpkj"]));
                  $dpunosubpkj = strtoupper(trim($data["dpunosubpkj"]));
                  $dpusubpkj = strtoupper(trim($data["dpusubpkj"]));
                  $nmsubpkj = strtoupper(trim($data["nmsubpkj"]));
                  $dpuqty = strtoupper(trim($data["dpuqty"])); 
                  $dpusatuan = strtoupper(trim($data["dpusatuan"]));
                  $dpuqtyout = trim($data["dpuqtyout"]);
                  $dpuqtylns = trim($data["dpuqtylns"]);
                  $dpunomutasi = trim($data["dpunomutasi"]);
                  $dpunolunas = trim($data["dpunolunas"]);

                  $sql1 = "SELECT mpno, nopkj, nosubpkj, ambil, backorder FROM clmpdet2 
                          WHERE mpno = '".$dpunomp."' AND nopkj = '".$dpunopkj."' AND nosubpkj = '".$dpunosubpkj."'";
                  $res1 = mysql_query($sql1,$conn);

                  $data1 = mysql_fetch_array($res1);
                  $ambil = $data1["ambil"];
                  $backorder = $data1["backorder"];

                  if ($dpunomutasi != "" && $dpunolunas != "") {
                    $sisa = $dpuqty - ($ambil + $backorder) - $dpuqtyout - $dpuqtylns;
                  }
                  else {
                    $x = 0;

                    if ($dpunomutasi == "" && $dpuqtyout > 0) {
                      $x += $dpuqtyout;
                    }

                    if ($dpunolunas == "" && $dpuqtylns > 0) {
                      $x += $dpuqtylns;
                    }

                    $sisa = $dpuqty - ($ambil + $backorder) - $x;
                  }

                  if ($dpuqtylns > 0) {
                    $cek = 1;
                  }
                  else {
                    $cek = 0;
                  }

                  if ($dpuqtyout > 0 && $dpunomutasi != "") {
                    $outx = $dpuqtyout;
                    $cssx = "  background-color: #87d37c;";
                  }
                  else if (($dpuqtyout > 0 && $dpunomutasi == "") || ($dpunolunas == "" && $dpuqtylns > 0)) {
                    $outx = $dpuqtyout;
                    $cssx = "  background-color: #feca57;";
                  }
                  else {
                    $outx = $sisa;
                    $cssx = " background-color: #ff6b6b;";
                  }

                  if ($dpuqtylns > 0) {
                    $checked = "checked";
                  }

            ?>
                <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                  <td><?=$dpunomp?></td>
                  <td style="text-align: left;"><?=$nmpkj?></td>
                  <td style="text-align: left;"><?=$nmsubpkj?></td>
                  <td style="text-align: right;"><?=$dpuqty?></td>
                  <td><?=$dpusatuan?></td>
                  <td style="text-align: right;"><span id="txtsisa<?=$num?>" class="txtsisa"><?=round($sisa,4)?></span></td>
                  <td >
                    <input id="inrealisasi<?=$num?>" class="inrealisasi" type="text" name="realisasi" style="width: 70px; text-align: right; font-weight: bold; <?=$cssx?>" 
                    onkeydown="number(event); enter_realisasi(event,<?=$num?>);" onclick="this.setSelectionRange(0, this.value.length)" value="<?=$outx?>" onkeyup="calc_sisa(<?=$num?>)">
                  </td>
                  <td align="center" nowrap>
                    <input type="hidden" class="indpunomp" value="<?=$dpunomp?>">
                    <input type="hidden" class="indpunopkj" value="<?=$dpunopkj?>">
                    <input type="hidden" class="indpukdpkj" value="<?=$dpukdpkj?>">
                    <input type="hidden" class="indpunosubpkj" value="<?=$dpunosubpkj?>">
                    <input type="hidden" class="indpusubpkj" value="<?=$dpusubpkj?>">
                    <input type="hidden" class="insisa" id="insisa<?=$num?>"value="<?=$sisa?>">
                    <?php echo "<input id='inpelunasan".$num."' type='checkbox' class='inpelunasan' value='1' onchange='set_zero(".$num.")' ".$checked.">"; ?>
                  </td>
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
