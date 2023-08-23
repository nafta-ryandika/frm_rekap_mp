<?php
include("../../configuration.php");
include("../../connection.php");
include("../../endec.php");

//Class For Pdf
require('../../mpdf/mpdf.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
ini_set('memory_limit','2048M');
set_time_limit(0);

$xkdgdg = str_replace("WH", "", $_SESSION[$domainApp."_myxdept"]);
$xname = $_SESSION[$domainApp."_myname"];
$xgroup = $_SESSION[$domainApp."_mygroup"];
date_default_timezone_set("Asia/Bangkok");
$today = date("d/m/Y H:i:s");

//Cek Get Data
if(isset($_POST['idx'])){
  $idx = strtoupper($_POST['idx']);

  $datax = explode("|", $idx);

  $tujuan = $datax[0];
  $xtgl = $datax[1];
  
  $tgl = strtr($xtgl, '/', '-');
  $tgl = strtoupper(htmlspecialchars(date("Y-m-d", strtotime($tgl))));
}

$sql = "SELECT hpunobukti,hpunomp,hpukdpkj,hputgl FROM clhambilbrg 
        WHERE hpukddept = '".$tujuan."' and hputgl = '".$tgl."' and hpukdgdg = '".$xkdgdg."'
        GROUP BY hpunomp";
$res = mysql_query($sql,$conn);
$row = mysql_num_rows($res);

$header .=  "<img src='img/logokmbs.jpg' style='height: 5%;'></img><br/>
            <b style='font-size: 15px; '>PT KARYAMITRA BUDISENTOSA</b><br/>
            <b style='font-size: 11px;'>Laporan Outstanding Rekap Pengambilan Sementara By Kode Barang</b><br/>";

$header .= "<table border = '0' style = 'margin : 0px;'>
            <tr>
              <td>
                Tujuan
              </td>
              <td>
                :
              </td>
              <td>
                ".$tujuan."
              </td>
            </tr>
            <tr>
              <td>
                Tanggal Pembuatan
              </td>
              <td>
                :
              </td>
              <td>
                ".$xtgl."
              </td>
            </tr>
          </table>";

if ($row == 0) {
  $content .= "<table id='myTable' class='table' width='100%' style='margin:0px; overflow: wrap;'>
                <thead>
                  <tr>
                    <th style='width: 8%;'>No. MP</th>
                    <th style='width: 12%;'>Sub Pekerjaan</th>
                    <th style='width: 14%;'>Customer</th>
                    <th style='width: 10%;'>Artikel</th>
                    <th style='width: 14%;'>Last</th>
                    <th style='width: 14%;'>Warna</th>
                    <th style='width: 7%;'>Order</th>
                    <th style='width: 7%;'>Qty</th>
                    <th style='width: 7%;'>Satuan</th>
                    <th style='width: 7%;'>Sisa</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan='10' style='text-align: center;'>Data Tidak Ditemukan !</td>
                  </tr>
                </tbody>
              </table>
                ";
}
else{
  while ($data = mysql_fetch_array($res)) {
    $hpunobukti .= "'".trim($data["hpunobukti"])."',"; 
    $hpunomp .= "'".trim($data["hpunomp"])."',";
  }

  $hpunobukti = rtrim($hpunobukti,",");
  $hpunomp = rtrim($hpunomp,",");

  $sql1 = "SELECT dpukdbrg, dpunmbrg 
          FROM cldambilbrg a 
          WHERE a.dpunobukti IN (".$hpunobukti.")
          GROUP BY dpukdbrg 
          ORDER BY dpukdbrg";

  $res1 = mysql_query($sql1,$conn);

  $no = 0;
  while ($data1 = mysql_fetch_array($res1)) {
    $no++;
    $dpukdbrg = strtoupper(trim($data1["dpukdbrg"]));
    $dpunmbrg = strtoupper(trim($data1["dpunmbrg"]));

    $sql2 = "SELECT *, 
            (dt3.qty - dt3.ambil - dt3.backorder) AS sisa, 
            (SELECT LEFT(ket,15) AS ket FROM kmmstsubpkj WHERE subpkj = dt3.subpkj) AS nmsubpkj,
            (SELECT LEFT(nama,15) AS nama FROM kmcustomer WHERE cust = dt2.cust) AS nmcust,
            dt2.colour AS colour
            FROM (
            SELECT dpunomp, dpunopkj, dpunosubpkj, dpukdbrg, dpunmbrg 
            FROM cldambilbrg a 
            WHERE a.dpunobukti IN (".$hpunobukti.") AND dpukdbrg = '".$dpukdbrg."'
            )dt1
            INNER JOIN 
            (SELECT mpno, cust, article, LEFT(`last`,17) AS `last`, LEFT(colour,17) AS colour, tot FROM clmphead WHERE mpno IN (".$hpunomp."))dt2
            ON dt1.dpunomp = dt2.mpno
            INNER JOIN 
            (SELECT * FROM clmpdet2 WHERE mpno IN (".$hpunomp.") AND materi = '".$dpukdbrg."')dt3
            ON dt1.dpunomp = dt3.mpno AND dt1.dpunopkj = dt3.nopkj AND dt1.dpunosubpkj = dt3.nosubpkj AND dt1.dpukdbrg = dt3.materi
            WHERE (dt3.qty - dt3.ambil - dt3.backorder) > 0
            ORDER BY dpunomp";

    $res2 = mysql_query($sql2,$conn);

    $xcontent = "";

    while ($data2 = mysql_fetch_array($res2)) {
      $dpunomp = strtoupper(trim($data2["dpunomp"]));
      $nmsubpkj = strtoupper(trim($data2["nmsubpkj"]));
      $nmcust = strtoupper(trim($data2["nmcust"]));
      $article = strtoupper(trim($data2["article"]));
      $last = strtoupper(trim($data2["last"]));
      $colour = strtoupper(trim($data2["colour"]));
      $tot = strtoupper(trim($data2["tot"]));
      $qty = strtoupper(trim($data2["qty"]));
      $nstn = strtoupper(trim($data2["nstn"]));
      $sisa = strtoupper(trim($data2["sisa"]));

      $content .= "<table id='myTable' class='table' width='100%' style='margin:0px; margin-top:-1px; overflow: wrap;'>
                    <tr style='background-color: lightgray;'>
                      <td style='width: 8%;'><b>No. MP</b></td>
                      <td style='width: 21%;'><b>Customer</b></td>
                      <td style='width: 21%;'><b>Art. Produksi</b></td>
                      <td style='width: 21%;'><b>Last</b></td>
                      <td style='width: 21%;'><b>Warna</b></td>
                      <td style='width: 8%;'><b>Order</b></td>
                    </tr>
                    <tr>
                      <td style='width: 8%;'>".$dpunomp."</td>
                      <td style='width: 21%; text-align:left;'>".$nmcust."</td>
                      <td style='width: 21%; text-align:left;'>".$article."</td>
                      <td style='width: 21%; text-align:left;'>".$last."</td>
                      <td style='width: 21%; text-align:left;'>".$colour."</td>
                      <td style='width: 8%;' text-align:right;>".(float) $qty."</td>
                    </tr>
                  </table>
                  <table id='myTable' class='table' width='100%' style='margin:0px; margin-top:-1px; overflow: wrap;'>
                    <tr style='background-color: lightgray;'>
                      <td style='width: 8%;'><b>Subpkj</b></td>
                      <td style='width: 68%;'><b>Kode Barang - Nama Barang</b></td>
                      <td style='width: 8%;'><b>Qty.</b></td>
                      <td style='width: 8%;'><b>Satuan</b></td>
                      <td style='width: 8%;'><b>Sisa</b></td>
                    </tr>
                    <tr>
                      <td style='width: 8%;'>".$nmsubpkj."</td>
                      <td style='width: 68%; text-align:left;'>".$dpukdbrg." - ".$dpunmbrg."</td>
                      <td style='width: 8%;'>".(float) $qty."</td>
                      <td style='width: 8%;'>".$nstn."</td>
                      <td style='width: 8%; text-align:right;'>".(float) $sisa."</td>
                    </tr>
                  </table>
                  ";
      $totsisa +=$sisa;

      $content .= "<table id='myTable' class='table' width='100%' style='margin:0px; margin-top:-1px; overflow: wrap;'>
                    <tr>
                      <td style='width: 84%;'><b>Total Qty. Outstanding</b></td>
                      <td style='width: 16%;'><b>".(float) $totsisa."</b></td>
                    </tr>
                  </table><br/>";
      unset($totsisa);
    }
  }
}


$footer = "Printed : ".$_SESSION[$domainApp."_myname"]." ".$today."";

//$mpdf=new mPDF('1mode','2format kertas','3font size','4font','5margin left','6margin right','7margin top','8margin bottom','9margin header','10margin footer','11orientasi kertas');
$mpdf=new mPDF('','A4','7','Arial','5','5','35','10','5','5');
$mpdf->simpleTables = true;
$mpdf->packTableData = true;
$keep_table_proportions = true;
$mpdf->shrink_tables_to_fit=1;
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);
$stylesheet = file_get_contents('css/table.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML($content);
 
//save the file put which location you need folder/filname
$mpdf->Output("Laporan Outstanding Rekap Pengambilan Sementara.pdf", 'I');
 
 
//out put in browser below output function
$mpdf->Output();

?>