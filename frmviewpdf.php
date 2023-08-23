<?php
include("../../configuration.php");
include("../../connection.php");
include("../../endec.php");

//Class For Pdf
require('../../mpdf/mpdf.php');

$xname = $_SESSION[$domainApp."_myname"];
$xgroup = $_SESSION[$domainApp."_mygroup"];
date_default_timezone_set("Asia/Bangkok");
$today = date("d/m/Y H:i:s");

//Cek Get Data
if(isset($_POST['idx'])){
  $idx = strtoupper($_POST['idx']);
}

$sql = "SELECT 
        hpunobukti, hpukdgdg, hpukdtrans, hputgl, hpukddept, hpunomp, hpukdpkj,
        DATE_FORMAT(hputgl, '%d/%m/%Y') AS tgl,
        (SELECT nmdept FROM kmmstdeptgdg WHERE kddept = hpukddept) AS nmdept 
        FROM clhambilbrg 
        WHERE hpunobukti = '".$idx."'
        GROUP BY hpunobukti";
$res = mysql_query($sql,$conn);
$data = mysql_fetch_array($res);

$tujuan = $data["nmdept"];
$tgl = $data["tgl"];

$header .=  "<p style='text-align:right;'>Printed : ".$_SESSION[$domainApp."_myname"]." ".$today."</p>
            <table border = '0' style = 'margin : 0px;'>
              <tr>
                <td>
                  <img src='img/logokmbs.jpg' style='height: 5%;'></img>
                </td>
                <td>
                  <b style='font-size: 15px; '>PT KARYAMITRA BUDISENTOSA</b><br/>
                  <b style='font-size: 11px;'>Daftar Rekap Pengambilan Barang Sementara</b>
                </td>
              </tr>
            </table>";

$header .= "<table border = '0' style = 'margin : 0px;'>
            <tr>
              <td>
                No. Bukti Pengambilan
              </td>
              <td>
                :
              </td>
              <td>
                ".$idx."
              </td>
            </tr>
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
                ".$tgl."
              </td>
            </tr>
          </table>";

$sql = "SELECT  
        dpunobukti, dpunomp, dpukdpkj, dpunopkj, dpusubpkj, dpunosubpkj, dpukdbrg, dpunmbrg, 
        dpuqty, dpusatuan, dpuqtyout, dpuqtylns, IFNULL(dpunomutasi, '') AS dpunomutasi, 
        IFNULL(dpunolunas, '') AS dpunolunas, dpucetak, access, komp, userby,
        (SELECT ket FROM kmmstpkj WHERE pkj = dpukdpkj) AS nmpkj,
        (SELECT LEFT(ket,12) FROM kmmstsubpkj WHERE subpkj = dpusubpkj) AS nmsubpkj
        FROM cldambilbrg 
        WHERE dpunobukti = '".$idx."' 
        GROUP BY dpukdbrg
        -- ORDER BY dpunmbrg, dpunomp, dpunosubpkj";
$res = mysql_query($sql,$conn);

$row = 0;

while ($data = mysql_fetch_array($res)){
  $dpunobukti = $data["dpunobukti"];
  $dpukdbrg = $data["dpukdbrg"];
  $dpunmbrg = $data["dpunmbrg"];
  $row++;

  $content .= "<table id='myTable' class='table' width='100%' style='margin:0px; overflow: wrap;'>
                <thead>
                  <tr>
                    <th colspan='11' style='text-align: left;' >".$row.".  Kode Barang : ".$dpukdbrg." - ".$dpunmbrg."</th>
                  </tr>
                  <tr style='background-color: #BABABA;'>
                    <th style='width: 7.5%;'>No. MP</th>
                    <th style='width: 16%;'>Sub Pekerjaan</th>
                    <th style='width: 13%;'>Customer</th>
                    <th style='width: 7%;'>Artikel</th>
                    <th style='width: 12%;'>Last</th>
                    <th style='width: 12%;'>Warna</th>
                    <th style='width: 6%;'>Order</th>
                    <th style='width: 7%;'>Qty</th>
                    <th style='width: 5.5%;'>Satuan</th>
                    <th style='width: 7%;'>Sisa</th>
                    <th style='width: 7%;'>Realisasi</th>
                  </tr>
                </thead>
                <tbody>
                ";

  $sql1 = "SELECT  
          dpunobukti, dpunomp, dpukdpkj, dpunopkj, dpusubpkj, dpunosubpkj, dpukdbrg, dpunmbrg, 
          dpuqty, dpusatuan, dpuqtyout, dpuqtylns, IFNULL(dpunomutasi, '') AS dpunomutasi, 
          IFNULL(dpunolunas, '') AS dpunolunas, dpucetak, access, komp, userby,
          (SELECT ket FROM kmmstpkj WHERE pkj = dpukdpkj) AS nmpkj,
          (SELECT ket FROM kmmstsubpkj WHERE subpkj = dpusubpkj) AS nmsubpkj
          FROM cldambilbrg 
          WHERE dpunobukti = '".$dpunobukti."' AND dpukdbrg = '".$dpukdbrg."'
          ORDER BY dpunmbrg, dpunomp, dpunosubpkj";
  $res1 = mysql_query($sql1,$conn);

  $xsisa = 0;
  $xdpuqtyout = 0;
  
  while ($data1 = mysql_fetch_array($res1)) {
    $dpunobukti = strtoupper(trim($data1["dpunobukti"]));
    $dpunomp = strtoupper(trim($data1["dpunomp"]));
    $dpukdpkj = strtoupper(trim($data1["dpukdpkj"]));
    $dpunopkj = strtoupper(trim($data1["dpunopkj"]));
    $dpusubpkj = strtoupper(trim($data1["dpusubpkj"]));
    $dpunosubpkj = strtoupper(trim($data1["dpunosubpkj"]));
    $dpukdbrg = strtoupper(trim($data1["dpukdbrg"]));
    $dpunmbrg = strtoupper(trim($data1["dpunmbrg"]));
    $dpuqty = strtoupper(trim($data1["dpuqty"]));
    $dpusatuan = strtoupper(trim($data1["dpusatuan"]));
    $dpuqtyout = strtoupper(trim($data1["dpuqtyout"]));
    $dpuqtylns = strtoupper(trim($data1["dpuqtylns"]));
    $dpunomutasi = strtoupper(trim($data1["dpunomutasi"]));
    $dpunolunas = strtoupper(trim($data1["dpunolunas"]));
    $nmpkj = strtoupper(trim($data1["nmpkj"]));
    $nmsubpkj = strtoupper(trim($data1["nmsubpkj"]));
    $empty = 0;

    $sql2 = "SELECT *, (SELECT nama FROM kmcustomer WHERE cust = dt2.cust) as nmcust
            FROM (
            SELECT a.mpno, a.ambil, a.backorder FROM clmpdet2 a
            WHERE a.mpno = '".$dpunomp."' AND a.nopkj = '".$dpunopkj."' AND a.subpkj = '".$dpusubpkj."' AND a.materi = '".$dpukdbrg."')dt1
            INNER JOIN (
            SELECT b.mpno, b.cust, b.article, LEFT(b.`last`,15) AS `last`, LEFT(b.colour,15) AS colour, 
            b.tot FROM clmphead b 
            WHERE b.mpno = '".$dpunomp."')dt2
            ON dt1.mpno = dt2.mpno ";
    $res2 = mysql_query($sql2,$conn);
    $row2 = mysql_num_rows($res2);
     // $content .= "<tr><td colspan = '11'>".$sql2."</td></tr>";
    if ($row2 > 0) {
      while ($data2 = mysql_fetch_array($res2)) {
        $ambil = trim($data2["ambil"]);
        $backorder = trim($data2["backorder"]);
        $article = trim($data2["article"]);
        $last = trim($data2["last"]);
        $colour = trim($data2["colour"]);
        $tot = trim($data2["tot"]);
        $nmcust = trim($data2["nmcust"]);

        if ($dpunomutasi != "" && $dpunolunas != "") {
          $sisa = $dpuqty - ($ambil + $backorder) - $dpuqtyout - $dpuqtylns;
        }
        else {
          $sisa = $dpuqty - ($ambil + $backorder);
        }

        if ($dpuqtylns > 0) {
          $cek = 1;
        }
        else {
          $cek = 0;
        }

        if ($dpuqtyout == 0) {
          $dpuqtyout = "";
        }


        $content .= "<tr>";
        $content .= " <td style='padding: 2px;'>".$dpunomp."</td>";
        $content .= " <td style='text-align: left; padding: 2px;'>".$nmsubpkj."</td>";
        $content .= " <td style='text-align: left; padding: 2px;'>".$nmcust."</td>";
        $content .= " <td style='text-align: left; padding: 2px;'>".$article."</td>";
        $content .= " <td style='text-align: left; padding: 2px;'>".$last."</td>";
        $content .= " <td style='text-align: left; padding: 2px;'>".$colour."</td>";
        $content .= " <td style='text-align: right; padding: 2px;'>".(float) $tot."</td>";
        $content .= " <td style='text-align: right; padding: 2px;'>".$dpuqty."</td>";
        $content .= " <td style='padding: 2px;'>".$dpusatuan."</td>";
        $content .= " <td style='text-align: right; padding: 2px;'>".round($sisa,4)."</td>";
        $content .= " <td style='text-align: right; padding: 2px;'>".$dpuqtyout."</td>";
        $content .= "</tr>";

        $xsisa += $sisa;
        $xdpuqtyout += $dpuqtyout;
      }
    }
    else {
      $empty = 1;
    }

  }

  if ($empty == 1) {
    $content .= "<tr><td colspan='11'>Data Tidak Ditemukan</td></tr>";
  }
  else{
    if ($xdpuqtyout > 0) {
      $xambil = ($xdpuqtyout - $xsisa);
      $xambil = "- ".$xambil;
    }
    else {
      $xambil = ($xsisa - $xdpuqtyout);
    }

    if ($xdpuqtyout == 0) {
      $xdpuqtyout = "";
    }

    $content .= "<tr>
                  <td colspan='5' style='text-align: left;'><b>Total yang Harus di Ambil : ".$xsisa."</b></td>
                  <td colspan='6' style='text-align: left;'><b>Total Qty Realisasi : ".$xdpuqtyout."</b></td>
                 </tr>";
  }

  $content .= "   </tbody>
                </table>
                <br/>";
}




// $footer = "Printed : ".$_SESSION[$domainApp."_myname"]." ".$today."";

//$mpdf=new mPDF('1mode','2format kertas','3font size','4font','5margin left','6margin right','7margin top','8margin bottom','9margin header','10margin footer','11orientasi kertas');
$mpdf=new mPDF('','A4','7.5','Arial Narrow','4','4','36','10','4','4');
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
$mpdf->Output("Rekap-".$idx.".pdf", 'I');
 
 
//out put in browser below output function
$mpdf->Output();

?>