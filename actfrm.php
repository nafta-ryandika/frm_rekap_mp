<?php

include("../../configuration.php");
include("../../connection.php");
include("../../endec.php");

// get data
if(isset($_POST['intxtmode'])){
  $intxtmode = $_POST['intxtmode'];
}

if(isset($_POST['inhpunobukti'])){
  $inhpunobukti = strtoupper(htmlspecialchars(trim($_POST['inhpunobukti'])));
}

if(isset($_POST['inhpukdtrans'])){
  $inhpukdtrans = strtoupper(htmlspecialchars(trim($_POST['inhpukdtrans'])));
}

if(isset($_POST['inhpukdgdg'])){
  $inhpukdgdg = strtoupper(htmlspecialchars(trim($_POST['inhpukdgdg'])));
}

if(isset($_POST['inhpukddept'])){
  $inhpukddept = strtoupper(htmlspecialchars(trim($_POST['inhpukddept'])));
}

if(isset($_POST['inhputgl'])){
  $tgl = strtr($_POST['inhputgl'], '/', '-');
  $inhputgl = strtoupper(htmlspecialchars(date("Y-m-d", strtotime($tgl))));
}

if(isset($_POST['inhpukdpkj'])){
  $inhpukdpkj = strtoupper(htmlspecialchars(trim($_POST['inhpukdpkj'])));
}

if(isset($_POST['inhpunomp'])){
  $inhpunomp = strtoupper(htmlspecialchars(trim($_POST['inhpunomp'])));
}

if(isset($_POST['indelete_mp'])){
  $indelete_mp = strtoupper(htmlspecialchars(trim($_POST['indelete_mp'])));
}

if(isset($_POST['indpukdbrg'])){
  $indpukdbrg = strtoupper(htmlspecialchars(trim($_POST['indpukdbrg'])));
}

if(isset($_POST['indpunomp'])){
  $indpunomp = strtoupper(htmlspecialchars(trim($_POST['indpunomp'])));
}

if(isset($_POST['indpunopkj'])){
  $indpunopkj = strtoupper(htmlspecialchars(trim($_POST['indpunopkj'])));
}

if(isset($_POST['indpukdpkj'])){
  $indpukdpkj = strtoupper(htmlspecialchars(trim($_POST['indpukdpkj'])));
}

if(isset($_POST['indpunosubpkj'])){
  $indpunosubpkj = strtoupper(htmlspecialchars(trim($_POST['indpunosubpkj'])));
}

if(isset($_POST['indpusubpkj'])){
  $indpusubpkj = strtoupper(trim($_POST['indpusubpkj']));
}

if(isset($_POST['insisa'])){
  $insisa = strtoupper(htmlspecialchars(trim($_POST['insisa'])));
}

if(isset($_POST['inrealisasi'])){
  $inrealisasi = strtoupper(htmlspecialchars(trim($_POST['inrealisasi'])));
}

if(isset($_POST['inpelunasan'])){
  $inpelunasan = strtoupper(htmlspecialchars(trim($_POST['inpelunasan'])));
}

// function
function check($id,$xid,$conn){
  if ($id == "transaksi") {
    $sql = "SELECT kdtrans, nmtrans FROM kmmstout WHERE kdtrans = '".$xid."'";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    if ($row > 0) {
      $data = mysql_fetch_array($res);
      $nm = $data["nmtrans"];
      return $nm;
    }
    else {
      return 0;
    }
  }
  elseif ($id == "gudang") {
    $sql = "SELECT kdgdg, nmgdg FROM kmmstgdg WHERE kdgdg = '".$xid."'";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    if ($row > 0) {
      $data = mysql_fetch_array($res);
      $nm = $data["nmgdg"];
      return $nm;
    }
    else {
      return 0;
    }
  }
  elseif ($id == "departemen") {
    $sql = "SELECT kddept, nmdept FROM kmmstdeptgdg WHERE kddept = '".$xid."'";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    if ($row > 0) {
      $data = mysql_fetch_array($res);
      $nm = $data["nmdept"];
      return $nm;
    }
    else {
      return 0;
    }
  }
  elseif ($id == "mp") {
    $xdata = explode("|",$xid);
    $sql = "SELECT mpno, IF(dateiss IS NULL, 1 , 2) AS status 
            FROM clmphead 
            WHERE mpno = '".$xdata[0]."'";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    if ($row > 0) {
      $data = mysql_fetch_array($res);
      $status = $data["status"];

      if ($status == 2) {
        $sql1 = "SELECT a.nosubpkj 
                FROM clmpdet2 a 
                WHERE a.mpno = '".$xdata[0]."' AND a.nopkj = '".$xdata[1]."'";
        $res1 = mysql_query($sql1,$conn);
        $row1 = mysql_num_rows($res1);

        if ($row1 == 0) {
          $status = 3;
        }

        $sql2 = "SELECT hpunobukti FROM clhambilbrg WHERE hpunobukti = '".$xdata[2]."' AND hpunomp = '".$xdata[0]."'";
        $res2 = mysql_query($sql2,$conn);
        $row2 = mysql_num_rows($res2);

        if ($row2 > 0) {
          $status = 4;
        }
      }

      return $status;
    }
    else {
      return 0;
    }
  }
  elseif ($id == "nobukti") {
    $sql = "SELECT  
            dpunobukti, dpunomp, dpukdpkj, dpunopkj, dpusubpkj, dpunosubpkj, dpukdbrg, dpunmbrg, 
            dpuqty, dpusatuan, dpuqtyout, dpuqtylns, IFNULL(dpunomutasi, '') AS dpunomutasi, 
            IFNULL(dpunolunas, '') AS dpunolunas, dpucetak, access, komp, userby,
            (SELECT ket FROM kmmstpkj WHERE pkj = dpukdpkj) AS nmpkj,
            (SELECT ket FROM kmmstsubpkj WHERE subpkj = dpusubpkj) AS nmsubpkj
            FROM cldambilbrg 
            WHERE dpunobukti = '".$xid."'
            ORDER BY dpunomp, dpunosubpkj";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    $err = "";
    $status = 0;

    if ($row > 0) {
      while ($data = mysql_fetch_array($res)) {
        $dpunobukti = trim($data["dpunobukti"]);
        $dpunomp = trim($data["dpunomp"]);
        $dpukdpkj = trim($data["dpukdpkj"]);
        $dpunopkj = trim($data["dpunopkj"]);
        $dpusubpkj = trim($data["dpusubpkj"]);
        $dpunosubpkj = trim($data["dpunosubpkj"]);
        $dpukdbrg = trim($data["dpukdbrg"]);
        $dpunmbrg = trim($data["dpunmbrg"]);
        $dpuqty = trim($data["dpuqty"]);
        $dpusatuan = trim($data["dpusatuan"]);
        $dpuqtyout = trim($data["dpuqtyout"]);
        $dpuqtylns = trim($data["dpuqtylns"]);
        $dpunomutasi = trim($data["dpunomutasi"]);
        $dpunolunas = trim($data["dpunolunas"]);
        $nmpkj = trim($data["nmpkj"]);
        $nmsubpkj = trim($data["nmsubpkj"]);

          $sql1 = "SELECT ambil, backorder 
                   FROM clmpdet2 
                   WHERE mpno = '".$dpunomp."' AND nopkj = '".$dpunopkj."' AND nosubpkj = '".$dpunosubpkj."' 
                   AND subpkj = '".$dpusubpkj."' AND materi = '".$dpukdbrg."'";
          $res1 = mysql_query($sql1,$conn);
          $row1 = mysql_num_rows($res1);

          if ($row1 == 0) {
            $status = 1;
            $err .= $dpunomp."|".$dpunmbrg."|".$nmpkj."|".$nmsubpkj."#@";
          }
      }
      return "1#$".$err;
    }
    else{
      return "0#$";
    }
  }
  elseif ($id == "realisasi") {
    $status = 0;
    $sql = "SELECT max(dpuqtyout) AS dpuqtyout, max(dpunomutasi) AS dpunomutasi,
            if((SELECT kmtgl FROM kmparamstock) > (SELECT hputgl FROM clhambilbrg WHERE hpunobukti = dpunobukti GROUP BY hpunobukti), 1,0 ) AS stat
            FROM cldambilbrg 
            WHERE dpunobukti = '".$xid."'";
    $res = mysql_query($sql,$conn);
    $data = mysql_fetch_array($res);

    $dpuqtyout = $data["dpuqtyout"];
    $dpunomutasi = $data["dpunomutasi"];
    $stat = $data["stat"];

    if ($stat == 1) {
      $status = 1;
    }
    else {
      if ($dpuqtyout > 0 || $dpunomutasi != "") {
        $status = 2;
      }
    }

    return $status;
  }
  elseif ($id == "bukti") {
    $sql = "SELECT hpunobukti FROM clhambilbrg  WHERE hpunobukti = '".$xid."'";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    return $row;
  }
}

function get($id,$data,$conn){
  $month = date("m");
  $year = date("Y");
  
  if ($id == "generate_id") {
    $xdata = explode("|",$data);
    $month = $xdata[2];
    $year = $xdata[3];

    $sql = "SELECT ccounter FROM rlcounter WHERE ckode = '".$xdata[0]."' AND cbultah = '".$month.$year."'";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    if ($row == 0) {
      $sql1 = "INSERT INTO rlcounter 
              VALUES ('".$xdata[0]."', 'PENGAMBILAN BARANG SEMENTARA', '".$month.$year."', 1, curdate(),'".$xdata[1]."',curtime(), NULL)";
      
      if (!mysql_query($sql1,$conn)){
        die('Error (Insert Counter): ' . mysql_error());
      }

      $ccounter = 1;
    }
    else {
      $data = mysql_fetch_array($res);
      $ccounter = $data["ccounter"];
      $ccounter = $ccounter + 1;
    
      $sql2 = "UPDATE rlcounter SET
            ccounter = '".$ccounter."',
            access = now(),
            userby = '".$xdata[1]."',
            entry = (SELECT curtime())
            WHERE
            ckode = '".$xdata[0]."' AND cbultah = '".$month.$year."'";
    
      if (!mysql_query($sql2,$conn)){
        die('Error (Update Counter): ' . mysql_error());
      }
    }

    //create nobukti
    $nobukti = $xdata[0]."/".$month.$year."/".sprintf("%07s", $ccounter);
    return $nobukti;
  }
  elseif ($id == "get_detail_mp") {
    $xdata = explode("|",$data);
    $sql = "SELECT dt1.*, 
            (SELECT nmbrg FROM kmmstbhnbaku WHERE kdbrg = dt1.materi) AS nmbrg, 
            (SELECT ket FROM kmmstsubpkj WHERE subpkj = dt1.subpkj) AS ket
            FROM (
            SELECT a.mpno, a.nopkj, a.nosubpkj, a.subpkj, a.materi, a.qty,a.nstn, a.ambil, a.backorder, (a.qty - a.ambil - a.backorder) AS sisa
            FROM clmpdet2 a
            WHERE a.mpno = '".$xdata[0]."' AND a.nopkj = '".$xdata[1]."')dt1";
    $res = mysql_query($sql,$conn);
    while ($data = mysql_fetch_array($res)) {
      $nmbrg .= $data["nmbrg"]."|";
      $ket .= $data["ket"]."|";
      $qty .= $data["qty"]."|";
      $satuan .= $data["nstn"]."|";
      $sisa .= $data["sisa"]."|";
    }
    return ($nmbrg."#@".$ket."#@".$qty."#@".$satuan."#@".$sisa);
  }
  elseif ($id == "mutasi_keluar") {
    $xdata = explode("|",$data);

    $sql = "SELECT * FROM kmcounter 
            WHERE ckddept = '".$xdata[0]."' AND ckdgdg = '".$xdata[1]."' AND ckdtrans = '".$xdata[2]."' AND cbultah = '".$month.$year."'";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    if ($xdata[2] == "PRD") {
      $ctransaksi = "MUTASI KELUAR KE PRODUKSI";
    }
    else if ($xdata[2] == "JOB") {
      $ctransaksi = "MUTASI KELUAR JOB OUT";
    }
    else if ($xdata[2] == "REC") {
      $ctransaksi = "MUTASI KELUAR RECUT";
    }
    else if ($xdata[2] == "ADD") {
      $ctransaksi = "MUTASI KELUAR ADDITIONAL";
    }

    if ($row == 0) {
      $sql1 = "INSERT INTO kmcounter 
               VALUES ('".$xdata[0]."', '".$xdata[1]."', '".$xdata[2]."', '".$ctransaksi."','".$month.$year."', 1, now(),'".$xdata[3]."', '".$xdata[4]."', 0)";
      
      if (!mysql_query($sql1,$conn)){
        die('Error (Insert Counter): ' . mysql_error());
      }

      $ccounter = 1;
    }
    else {
      $data = mysql_fetch_array($res);
      $ccounter = $data["ccounter"];
      $ccounter = $ccounter + 1;
    
      $sql2 = "UPDATE kmcounter SET
            ccounter = '".$ccounter."',
            access = now(),
            komp = '".$xdata[3]."',
            userby = '".$xdata[4]."'
            WHERE
            ckddept = '".$xdata[0]."' AND ckdgdg = '".$xdata[1]."' AND ckdtrans = '".$xdata[2]."' AND cbultah = '".$month.$year."'";
    
      if (!mysql_query($sql2,$conn)){
        die('Error (Update Counter): ' . mysql_error());
      }
    }

    //create nobukti
    $nobukti = $xdata[1]."/".$xdata[2]."/".$month.$year."/".sprintf("%05s", $ccounter);
    return $nobukti;
  }
  elseif ($id == "pelunasan_mp") {
    $xdata = explode("|",$data);

    $sql = "SELECT * FROM kmcounter 
            WHERE ckddept = '".$xdata[0]."' AND ckdgdg = '".$xdata[1]."' AND ckdtrans = '".$xdata[2]."' AND cbultah = '".$month.$year."'";
    $res = mysql_query($sql,$conn);
    $row = mysql_num_rows($res);

    if ($row == 0) {
      $sql1 = "INSERT INTO kmcounter 
               VALUES ('".$xdata[0]."', '".$xdata[1]."', '".$xdata[2]."', 'PELUNASAN MP','".$month.$year."', 1, now(),'".$xdata[3]."', '".$xdata[4]."', 0)";
      
      if (!mysql_query($sql1,$conn)){
        die('Error (Insert Counter): ' . mysql_error());
      }

      $ccounter = 1;
    }
    else {
      $data = mysql_fetch_array($res);
      $ccounter = $data["ccounter"];
      $ccounter = $ccounter + 1;
    
      $sql2 = "UPDATE kmcounter SET
            ccounter = '".$ccounter."',
            access = now(),
            komp = '".$xdata[3]."',
            userby = '".$xdata[4]."'
            WHERE
            ckddept = '".$xdata[0]."' AND ckdgdg = '".$xdata[1]."' AND ckdtrans = '".$xdata[2]."' AND cbultah = '".$month.$year."'";
    
      if (!mysql_query($sql2,$conn)){
        die('Error (Update Counter): ' . mysql_error());
      }
    }

    //create nobukti
    $nobukti = $xdata[1]."/".$xdata[2]."/".$month.$year."/".sprintf("%05s", $ccounter);
    return $nobukti;
  }
}

if($intxtmode == "check_transaksi"){
  $data = check("transaksi",$inhpukdtrans,$conn);
  echo($data);
}
elseif($intxtmode == "check_gudang"){
  $data = check("gudang",$inhpukdgdg,$conn);
  echo($data);
}
elseif($intxtmode == "check_departemen"){
  $data = check("departemen",$inhpukddept,$conn);
  echo($data);
}
elseif($intxtmode == "generate_id"){
  $dept = str_replace("WH", "PS", $_SESSION[$domainApp."_myxdept"]);
  $name = $_SESSION[$domainApp."_myname"];
  $month = date("m",strtotime($inhputgl));
  $year = date("Y",strtotime($inhputgl));
  $data = $dept."|".$name."|".$month."|".$year;
  $data = get("generate_id",$data,$conn);
  echo($data);
}
elseif($intxtmode == "check_mp"){
  if ($inhpukdpkj == "ASSEMBLY") {
    $nopkj = 1;
  }
  elseif ($inhpukdpkj == "BOTTOM"){
    $nopkj = 2;
  }
  elseif ($inhpukdpkj == "CUTTING L"){
    $nopkj = 3;
  }
  elseif ($inhpukdpkj == "CUTTING NL"){
    $nopkj = 4;
  }
  elseif ($inhpukdpkj == "STITCHING"){
    $nopkj = 5;
  }

  $data = $inhpunomp."|".$nopkj."|".$inhpunobukti;
  $data = check("mp",$data,$conn);
  echo($data);
}
elseif($intxtmode == "get_detail_mp"){
  if ($inhpukdpkj == "ASSEMBLY") {
    $nopkj = 1;
  }
  elseif ($inhpukdpkj == "BOTTOM"){
    $nopkj = 2;
  }
  elseif ($inhpukdpkj == "CUTTING L"){
    $nopkj = 3;
  }
  elseif ($inhpukdpkj == "CUTTING NL"){
    $nopkj = 4;
  }
  elseif ($inhpukdpkj == "STITCHING"){
    $nopkj = 5;
  }

  $data = $inhpunomp."|".$nopkj;
  $data = get("get_detail_mp",$data,$conn);
  echo($data);
}
elseif($intxtmode == "add"){
  $inhpunomp = explode("|",rtrim($inhpunomp,'|'));

  if ($inhpukdpkj == "ASSEMBLY") {
    $nopkj = 1;
  }
  elseif ($inhpukdpkj == "BOTTOM"){
    $nopkj = 2;
  }
  elseif ($inhpukdpkj == "CUTTING L"){
    $nopkj = 3;
  }
  elseif ($inhpukdpkj == "CUTTING NL"){
    $nopkj = 4;
  }
  elseif ($inhpukdpkj == "STITCHING"){
    $nopkj = 5;
  }

  for ($i=0; $i < count($inhpunomp); $i++) {
    // insert header 
    $sql = "INSERT INTO clhambilbrg 
            (hpunobukti, 
            hpukdgdg, 
            hpukdtrans, 
            hputgl, 
            hpukddept, 
            hpunomp,
            hpukdpkj, 
            access, 
            komp, 
            userby) 
            VALUES 
            ('".$inhpunobukti."', 
            '".$inhpukdgdg."', 
            '".$inhpukdtrans."', 
            '".$inhputgl."', 
            '".$inhpukddept."', 
            '".$inhpunomp[$i]."', 
            '".$inhpukdpkj."', 
            now(), 
            '".$_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"]."',
            '".$_SESSION[$domainApp."_myname"]."')";

    if (!mysql_query($sql,$conn)){
      die('Error (Insert): ' . mysql_error());
    }

    // insert detail
    $sql1   = "SELECT 
              dt1.*, dt2.*, (dt2.qty - dt2.ambil - dt2.backorder) AS sisa, 
              (SELECT nmbrg FROM kmmstbhnbaku WHERE kdbrg = dt2.materi) as nmbrg
              FROM 
              (SELECT a.cust, a.mpno, a.article, a.`last`, a.noso, a.colour, a.tot, a.ket 
              FROM clmphead a 
              WHERE a.mpno = '".$inhpunomp[$i]."')dt1
              INNER JOIN 
              (SELECT b.mpno, b.nopkj, b.nosubpkj, b.subpkj, b.materi, b.calc, b.qty, b.nstn, b.ambil, b.backorder 
              FROM clmpdet2 b 
              WHERE b.mpno = '".$inhpunomp[$i]."' AND nopkj = '".$nopkj."')dt2
              ON dt1.mpno = dt2.mpno";
    $res1 = mysql_query($sql1,$conn);
    $row1 = mysql_num_rows($res1);

    if ($row1 == 0) {
      $err .= $inhpunomp[$i]."|";
    }
    else {
      while ($data1 = mysql_fetch_array($res1)) {
          $materi = $data1["materi"];
          $nopkj = $data1["nopkj"];
          $nosubpkj = $data1["nosubpkj"];
          $subpkj = $data1["subpkj"];
          $qty = $data1["qty"];
          $nstn = $data1["nstn"];
          $sisa = $data1["sisa"];
          $nmbrg = mysql_real_escape_string($data1["nmbrg"]);

        if ($hpukdtrans == "ADD" || $hpukdtrans == "REC") {
          $sql2   = "SELECT a.dpunobukti 
                    FROM cldambilbrg a 
                    WHERE a.dpunobukti = '".$inhpunobukti."' AND a.dpunomp = '".$inhpunomp[$i]."' 
                    AND a.dpukdbrg = '".$materi."' AND a.dpunopkj = '".$nopkj."' 
                    AND a.dpunosubpkj = '".$nosubpkj."'";
          $res2 = mysql_query($sql2,$conn);
          $row2 = mysql_num_rows($res2);

          if ($row2 == 0 ) {
            $sql3   = "INSERT INTO cldambilbrg 
                      (dpunobukti,
                      dpunomp,
                      dpukdpkj,
                      dpunopkj,
                      dpusubpkj,
                      dpunosubpkj,
                      dpukdbrg,
                      dpunmbrg,
                      dpuqty,
                      dpusatuan,
                      access,
                      komp,
                      userby) 
                      VALUES 
                      ('".$inhpunobukti."',
                      '".$inhpunomp[$i]."',
                      '".$inhpukdpkj."',
                      '".$nopkj."',
                      '".$subpkj."',
                      '".$nosubpkj."',
                      '".$materi."',
                      '".$nmbrg."',
                      '".$qty."',
                      '".$nstn."',
                      now(), 
                      '".$_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"]."',
                      '".$_SESSION[$domainApp."_myname"]."')";

            if (!mysql_query($sql3,$conn)){
              die('Error (Insert): ' . mysql_error());
            }
          }
        }
        else {
          if ($sisa > 0) {
            $sql4   = "SELECT a.dpunobukti 
                      FROM cldambilbrg a 
                      WHERE a.dpunobukti = '".$inhpunobukti."' AND a.dpunomp = '".$inhpunomp[$i]."' 
                      AND a.dpukdbrg = '".$materi."' AND a.dpunopkj = '".$nopkj."' 
                      AND a.dpunosubpkj = '".$nosubpkj."'";
            $res4 = mysql_query($sql4,$conn);
            $row4 = mysql_num_rows($res4);

            if ($row4 == 0 ) {
              $sql5   = "INSERT INTO cldambilbrg 
                        (dpunobukti,
                        dpunomp,
                        dpukdpkj,
                        dpunopkj,
                        dpusubpkj,
                        dpunosubpkj,
                        dpukdbrg,
                        dpunmbrg,
                        dpuqty,
                        dpusatuan,
                        access,
                        komp,
                        userby) 
                        VALUES 
                        ('".$inhpunobukti."',
                        '".$inhpunomp[$i]."',
                        '".$inhpukdpkj."',
                        '".$nopkj."',
                        '".$subpkj."',
                        '".$nosubpkj."',
                        '".$materi."',
                        '".$nmbrg."',
                        '".$qty."',
                        '".$nstn."',
                        now(), 
                        '".$_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"]."',
                        '".$_SESSION[$domainApp."_myname"]."')";

              if (!mysql_query($sql5,$conn)){
                die('Error (Insert): ' . mysql_error());
              }
            }
          }
        }
      }
    }
  }

  echo("Data Berhasil Disimpan");
}
elseif($intxtmode == "getedit") {
  $sql = "SELECT a.hpunobukti, a.hpukdtrans, a.hpukdgdg, a.hpukddept, DATE_FORMAT(a.hputgl,'%d/%m/%Y') AS hputgl, a.hpukdpkj, a.hpunomp,
          (SELECT nmtrans FROM kmmstout WHERE kdtrans = a.hpukdtrans) AS nmtrans,
          (SELECT nmgdg FROM kmmstgdg WHERE kdgdg = a.hpukdgdg) AS nmgdg,
          (SELECT nmdept FROM kmmstdeptgdg WHERE kddept = a.hpukddept) AS nmdept
          FROM clhambilbrg a 
          WHERE a.hpunobukti = '".$inhpunobukti."' 
          ORDER BY  a.hpunomp, a.access
          LIMIT 1";
  $res = mysql_query($sql,$conn);

  while ($data = mysql_fetch_array($res)) {
    $hpunobukti = $data["hpunobukti"]; 
    $hpukdtrans = $data["hpukdtrans"]; 
    $hpukdgdg = $data["hpukdgdg"]; 
    $hpukddept = $data["hpukddept"]; 
    $hputgl = $data["hputgl"]; 
    $hpukdpkj = $data["hpukdpkj"]; 
    $hpunomp = $data["hpunomp"];
    $nmtrans = $data["nmtrans"];
    $nmgdg = $data["nmgdg"];
    $nmdept = $data["nmdept"];

    echo "<span id='gethpunobukti'>".$hpunobukti."</span>"; 
    echo "<span id='gethpukdtrans'>".$hpukdtrans."</span>"; 
    echo "<span id='gethpukdgdg'>".$hpukdgdg."</span>"; 
    echo "<span id='gethpukddept'>".$hpukddept."</span>"; 
    echo "<span id='gethputgl'>".$hputgl."</span>"; 
    echo "<span id='gethpukdpkj'>".trim($hpukdpkj)."</span>"; 
    echo "<span id='gethpunomp'>".$hpunomp."</span>";
    echo "<span id='getnmtrans'>".$nmtrans."</span>";
    echo "<span id='getnmgdg'>".$nmgdg."</span>";
    echo "<span id='getnmdept'>".$nmdept."</span>";
  }
  mysql_free_result($res);
}
elseif($intxtmode == "edit") {
  $inhpunomp = explode("|",rtrim($inhpunomp,'|'));

  if ($inhpukdpkj == "ASSEMBLY") {
    $nopkj = 1;
  }
  elseif ($inhpukdpkj == "BOTTOM"){
    $nopkj = 2;
  }
  elseif ($inhpukdpkj == "CUTTING L"){
    $nopkj = 3;
  }
  elseif ($inhpukdpkj == "CUTTING NL"){
    $nopkj = 4;
  }
  elseif ($inhpukdpkj == "STITCHING"){
    $nopkj = 5;
  }

  for ($i=0; $i < count($inhpunomp); $i++) {
    // insert header 
    $sql0 = "SELECT * 
            FROM clhambilbrg
            WHERE
            hpunobukti = '".$inhpunobukti."' AND 
            hpunomp = '".$inhpunomp[$i]."' AND
            hpukdpkj = '".$inhpukdpkj."'";
    $res0 = mysql_query($sql0,$conn);
    $row0 = mysql_num_rows($res0);

    if ($row0 == 0) {
      $sql = "INSERT INTO clhambilbrg 
              (hpunobukti, 
              hpukdgdg, 
              hpukdtrans, 
              hputgl, 
              hpukddept, 
              hpunomp,
              hpukdpkj, 
              access, 
              komp, 
              userby) 
              VALUES 
              ('".$inhpunobukti."', 
              '".$inhpukdgdg."', 
              '".$inhpukdtrans."', 
              '".$inhputgl."', 
              '".$inhpukddept."', 
              '".$inhpunomp[$i]."', 
              '".$inhpukdpkj."', 
              now(), 
              '".$_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"]."',
              '".$_SESSION[$domainApp."_myname"]."')";

      if (!mysql_query($sql,$conn)){
        die('Error (Insert): ' . mysql_error());
      }
    }

    // insert detail
    $sql1   = "SELECT 
              dt1.*, dt2.*, (dt2.qty - dt2.ambil - dt2.backorder) AS sisa, 
              (SELECT nmbrg FROM kmmstbhnbaku WHERE kdbrg = dt2.materi) as nmbrg
              FROM 
              (SELECT a.cust, a.mpno, a.article, a.`last`, a.noso, a.colour, a.tot, a.ket 
              FROM clmphead a 
              WHERE a.mpno = '".$inhpunomp[$i]."')dt1
              INNER JOIN 
              (SELECT b.mpno, b.nopkj, b.nosubpkj, b.subpkj, b.materi, b.calc, b.qty, b.nstn, b.ambil, b.backorder 
              FROM clmpdet2 b 
              WHERE b.mpno = '".$inhpunomp[$i]."' AND nopkj = '".$nopkj."')dt2
              ON dt1.mpno = dt2.mpno";
    $res1 = mysql_query($sql1,$conn);
    $row1 = mysql_num_rows($res1);

    if ($row1 == 0) {
      $err .= $inhpunomp[$i]."|";
    }
    else {
      while ($data1 = mysql_fetch_array($res1)) {
          $materi = $data1["materi"];
          $nopkj = $data1["nopkj"];
          $nosubpkj = $data1["nosubpkj"];
          $subpkj = $data1["subpkj"];
          $qty = $data1["qty"];
          $nstn = $data1["nstn"];
          $sisa = $data1["sisa"];
          $nmbrg = mysql_real_escape_string($data1["nmbrg"]);

        if ($hpukdtrans == "ADD" || $hpukdtrans == "REC") {
          $sql2   = "SELECT a.dpunobukti 
                    FROM cldambilbrg a 
                    WHERE a.dpunobukti = '".$inhpunobukti."' AND a.dpunomp = '".$inhpunomp[$i]."' 
                    AND a.dpukdbrg = '".$materi."' AND a.dpunopkj = '".$nopkj."' 
                    AND a.dpunosubpkj = '".$nosubpkj."'";
          $res2 = mysql_query($sql2,$conn);
          $row2 = mysql_num_rows($res2);

          if ($row2 == 0 ) {
            $sql3   = "INSERT INTO cldambilbrg 
                      (dpunobukti,
                      dpunomp,
                      dpukdpkj,
                      dpunopkj,
                      dpusubpkj,
                      dpunosubpkj,
                      dpukdbrg,
                      dpunmbrg,
                      dpuqty,
                      dpusatuan,
                      access,
                      komp,
                      userby) 
                      VALUES 
                      ('".$inhpunobukti."',
                      '".$inhpunomp[$i]."',
                      '".$inhpukdpkj."',
                      '".$nopkj."',
                      '".$subpkj."',
                      '".$nosubpkj."',
                      '".$materi."',
                      '".$nmbrg."',
                      '".$qty."',
                      '".$nstn."',
                      now(), 
                      '".$_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"]."',
                      '".$_SESSION[$domainApp."_myname"]."')";

            if (!mysql_query($sql3,$conn)){
              die('Error (Insert): ' . mysql_error());
            }
          }
        }
        else {
          if ($sisa > 0) {
            $sql4   = "SELECT a.dpunobukti 
                      FROM cldambilbrg a 
                      WHERE a.dpunobukti = '".$inhpunobukti."' AND a.dpunomp = '".$inhpunomp[$i]."' 
                      AND a.dpukdbrg = '".$materi."' AND a.dpunopkj = '".$nopkj."' 
                      AND a.dpunosubpkj = '".$nosubpkj."'";
            $res4 = mysql_query($sql4,$conn);
            $row4 = mysql_num_rows($res4);

            if ($row4 == 0 ) {
              $sql5   = "INSERT INTO cldambilbrg 
                        (dpunobukti,
                        dpunomp,
                        dpukdpkj,
                        dpunopkj,
                        dpusubpkj,
                        dpunosubpkj,
                        dpukdbrg,
                        dpunmbrg,
                        dpuqty,
                        dpusatuan,
                        access,
                        komp,
                        userby) 
                        VALUES 
                        ('".$inhpunobukti."',
                        '".$inhpunomp[$i]."',
                        '".$inhpukdpkj."',
                        '".$nopkj."',
                        '".$subpkj."',
                        '".$nosubpkj."',
                        '".$materi."',
                        '".$nmbrg."',
                        '".$qty."',
                        '".$nstn."',
                        now(), 
                        '".$_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"]."',
                        '".$_SESSION[$domainApp."_myname"]."')";

              if (!mysql_query($sql5,$conn)){
                die('Error (Insert): ' . mysql_error());
              }
            }
          }
        }
      }
    }
  }
  
  // delete mp 
  if (trim($indelete_mp) != "") {
    $indelete_mp = explode("|",rtrim($indelete_mp,"|"));
    for ($i=0; $i < count($indelete_mp); $i++) {
      $sql = "DELETE FROM clhambilbrg
              WHERE 
              hpunobukti = '".$inhpunobukti."' AND 
              hpunomp = '".$indelete_mp[$i]."' AND
              hpukdpkj = '".$inhpukdpkj."'
              ";

      if (!mysql_query($sql,$conn)){
        die('Error (Delete): ' . mysql_error());
      }

      $sql1 = "DELETE FROM cldambilbrg
              WHERE 
              dpunobukti = '".$inhpunobukti."' AND 
              dpunomp = '".$indelete_mp[$i]."' AND
              dpukdpkj = '".$inhpukdpkj."'";
      
      if (!mysql_query($sql1,$conn)){
        die('Error (Delete): ' . mysql_error());
      }
    }
  }
  echo("Data Berhasil Disimpan");
}
elseif($intxtmode == "delete") {
  $sql = "DELETE FROM clhambilbrg
          WHERE 
          hpunobukti = '".$inhpunobukti."'";

  if (!mysql_query($sql,$conn)){
    die('Error (Delete): ' . mysql_error());
  }

  $sql1 = "DELETE FROM cldambilbrg
          WHERE 
          dpunobukti = '".$inhpunobukti."'";
  
  if (!mysql_query($sql1,$conn)){
    die('Error (Delete): ' . mysql_error());
  }
}
elseif($intxtmode == "check_nobukti") {
  $data = check("nobukti",$inhpunobukti,$conn);
  echo($data);
}
elseif($intxtmode == "xdept") {
  $data = str_replace("WH", "PS", $_SESSION[$domainApp."_myxdept"]);
  echo ($data);
}
elseif($intxtmode == "check_realisasi") {
  $data = check("realisasi",$inhpunobukti,$conn);
  echo($data);
}
elseif($intxtmode == "simpan_rekap") {
  $indpunomp = explode("|",rtrim($indpunomp,'|'));
  $indpunopkj = explode("|",rtrim($indpunopkj,'|'));
  $indpukdpkj = explode("|",rtrim($indpukdpkj,'|'));
  $indpunosubpkj = explode("|",rtrim($indpunosubpkj,'|'));
  $indpusubpkj = explode("|",rtrim($indpusubpkj,'|'));
  $insisa = explode("|",rtrim($insisa,'|'));
  $inrealisasi = explode("|",rtrim($inrealisasi,'|'));
  $inpelunasan = explode("|",rtrim($inpelunasan,'|'));
  
  for ($i=0; $i < count($inrealisasi); $i++) { 
    $sql = "UPDATE cldambilbrg SET 
            dpuqtyout = '".$inrealisasi[$i]."'
            WHERE 
            dpunobukti = '".$inhpunobukti."' AND
            dpunomp = '".$indpunomp[$i]."' AND
            dpunopkj = '".$indpunopkj[$i]."' AND
            dpukdpkj = '".$indpukdpkj[$i]."' AND
            dpunosubpkj = '".$indpunosubpkj[$i]."' AND
            dpusubpkj = '".$indpusubpkj[$i]."' AND
            dpukdbrg = '".$indpukdbrg."'
            ";

    if (!mysql_query($sql,$conn)){
      die('Error (Update realisasi): ' . mysql_error());
    }

    // $x .= $sql;

    if ($inpelunasan[$i] == 1) {
      $sql1 = "UPDATE cldambilbrg SET 
              dpuqtylns = '".($insisa[$i] - $inrealisasi[$i])."'
              WHERE 
              dpunobukti = '".$inhpunobukti."' AND
              dpunomp = '".$indpunomp[$i]."' AND
              dpunopkj = '".$indpunopkj[$i]."' AND
              dpukdpkj = '".$indpukdpkj[$i]."' AND
              dpunosubpkj = '".$indpunosubpkj[$i]."' AND
              dpusubpkj = '".$indpusubpkj[$i]."' AND
              dpukdbrg = '".$indpukdbrg."'
              ";
    }
    else {
      $sql1 = "UPDATE cldambilbrg SET 
              dpuqtylns = 0
              WHERE 
              dpunobukti = '".$inhpunobukti."' AND
              dpunomp = '".$indpunomp[$i]."' AND
              dpunopkj = '".$indpunopkj[$i]."' AND
              dpukdpkj = '".$indpukdpkj[$i]."' AND
              dpunosubpkj = '".$indpunosubpkj[$i]."' AND
              dpusubpkj = '".$indpusubpkj[$i]."' AND
              dpukdbrg = '".$indpukdbrg."'
              ";
    }

    if (!mysql_query($sql1,$conn)){
      die('Error (Update realisasi): ' . mysql_error());
    }
  }
  echo(1);
  // echo($x);
}
elseif($intxtmode == "check_bukti") {
  $data = check("bukti",$inhpunobukti,$conn);
  echo($data);
}
elseif($intxtmode == "cetak_bukti") {
  $xstatus = "";
  $ckddept = $_SESSION[$domainApp."_myxdept"];
  $komp = $_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"];
  $userby = $_SESSION[$domainApp."_myname"];
  $month = sprintf("%02s", date("m"));
  $year = date("Y");

  $sql = "SELECT hpunobukti, hpukdgdg, hpukdtrans, hputgl, hpukddept, hpunomp, hpukdpkj
          FROM clhambilbrg 
          WHERE hpunobukti = '".$inhpunobukti."'
          GROUP BY hpunomp 
          ORDER BY hpunomp";
  $res = mysql_query($sql,$conn);

  while ($data = mysql_fetch_array($res)) {
      $hpunomp = strtoupper(trim($data["hpunomp"]));
      $hpukdtrans = strtoupper(trim($data["hpukdtrans"]));
      $hpukdgdg = strtoupper(trim($data["hpukdgdg"]));
      $hpukddept = strtoupper(trim($data["hpukddept"]));
      $hpukdpkj = strtoupper(trim($data["hpukdpkj"]));

      // ambil data transaksi rekap yang qty realisasinya lebih besar dari nol dan belum ada no bukti mutasinya
      // tambah dpunolunas kosong
      $sql1 = "SELECT * FROM cldambilbrg 
               WHERE dpunobukti = '".$inhpunobukti."' AND dpunomp = '".$hpunomp."' AND ISNULL(dpunomutasi) AND dpuqtyout > 0
               -- AND ISNULL(dpunolunas) 
               ORDER BY dpunopkj,dpunosubpkj";
      $res1 = mysql_query($sql1,$conn);
      $row1 = mysql_num_rows($res1);

      if ($row1 > 0) {
        $sql2 = "SELECT * FROM kmmstout WHERE kdtrans = '".$hpukdtrans."'";
        $res2 = mysql_query($sql2,$conn);

        $data2 = mysql_fetch_array($res2);

        $hdrtabel = strtolower(trim($data2["hdrtabel"]));
        $dettabel = strtolower(trim($data2["dettabel"]));
        $saldotabel = strtolower(trim($data2["saldotabel"]));
        $rektabel = strtolower(trim($data2["rektabel"]));
        $nmtrans = strtolower(trim($data2["nmtrans"]));

        //get counter number
        $datax = $ckddept."|".$hpukdgdg."|".$hpukdtrans."|".$komp."|".$userby;
        $nobukti = get("mutasi_keluar",$datax,$conn);

        //insert header
        $sql3 = "INSERT INTO ".$hdrtabel." (hnobukti,htgl,hkdtrans,hkdgdg,hnomp,hket,hkddept) 
                 VALUES 
                 ('".$nobukti."',
                 curdate(), 
                 '".$hpukdtrans."',
                 '".$hpukdgdg."',
                 '".$hpunomp."',
                 '".$inhpunobukti."',
                 '".$hpukddept."')";

        if (!mysql_query($sql3,$conn)){
          die('Error (Insert header): ' . mysql_error());
        }

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
          $dpucetak = strtoupper(trim($data1["dpucetak"]));

          $no_bukti = 0;
          $no_lnsbukti = 0;

          if ($dpuqtyout > 0) {
            if ($no_bukti == 0) {
              //insert detail
              $sql4 = "INSERT INTO ".$dettabel." (dnobukti,dtgl,dnomp,dkdbrg,dnopkj,dpkj,dnosubpkj,dsubpkj,dqty,dsatuan,access,komp,userby) 
                      VALUES 
                      ('".$nobukti."',
                      curdate(),
                      '".$dpunomp."',
                      '".$dpukdbrg."',
                      '".$dpunopkj."',
                      '".$dpukdpkj."',
                      '".$dpunosubpkj."',
                      '".$dpusubpkj."',
                      '".$dpuqtyout."',
                      '".$dpusatuan."',
                      now(),
                      '".$komp."',
                      '".$userby."')";
              
              if (!mysql_query($sql4,$conn)){
                die('Error (Insert detail): ' . mysql_error());
              }

              // tambah saldo mutasi keluar
              $sql5 = "SELECT * FROM ".$saldotabel." 
                      WHERE bltahun = '".$year."' AND blgdg = '".$hpukdgdg."' AND blkdbrg = '".$dpukdbrg."' AND blsatuan = '".$dpusatuan."'";
              $res5 = mysql_query($sql5,$conn);
              $row5 = mysql_num_rows($res5);

              if ($row5 > 0) {
                $sql6 = "UPDATE ".$saldotabel." SET 
                        bl".$month." = bl".$month." + ".$dpuqtyout." 
                        WHERE bltahun = '".$year."' AND blgdg = '".$hpukdgdg."' AND blkdbrg = '".$dpukdbrg."' AND blsatuan = '".$dpusatuan."'";
              }
              else {
                $sql6 = "INSERT INTO ".$saldotabel." (bltahun,blgdg,blkdbrg,blsatuan,bl".$month.") VALUES 
                        ('".$year."',
                        '".$hpukdgdg."',
                        '".$dpukdbrg."',
                        '".$dpusatuan."',
                        '".$dpuqtyout."')";
              }

              if (!mysql_query($sql6,$conn)){
                die('Error (Saldo mutasi keluar): ' . mysql_error());
              }

              // insert kartu stock
              $sql7 = "INSERT INTO kmkartustk (ksnobukti,kstgl,ksnomp,ksnopkj,kspkj,ksnosubpkj,kssubpkj,kskdgdg,kskdtrans,ksmutasi,
                      kskdbrg,ksqty,kssat,kskddept,ksket) VALUES 
                      ('".$nobukti."',
                      curdate(),
                      '".$dpunomp."',
                      '".$dpunopkj."',
                      '".$dpukdpkj."',
                      '".$dpunosubpkj."',
                      '".$dpusubpkj."',
                      '".$hpukdgdg."',
                      '".$hpukdtrans."',
                      'K',
                      '".$dpukdbrg."',
                      '".$dpuqtyout."',
                      '".$dpusatuan."',
                      '".$hpukddept."',
                      '".$inhpunobukti."')";

              if (!mysql_query($sql7,$conn)){
                die('Error (Insert kartu stock): ' . mysql_error());
              }

              // update kmmstbrg
              $sql8 = "UPDATE kmmstbrg SET 
                      sakhir = sakhir - ".$dpuqtyout." 
                      WHERE kdbrg = '".$dpuqtyout."' AND satuan = '".$dpusatuan."' and kdgdg = '".$dpukdbrg."'";
              
              if (!mysql_query($sql8,$conn)){
                die('Error (Update master barang): ' . mysql_error());
              }

              // update detail mp
              $sql9 = "UPDATE clmpdet2 SET ambil = ambil + (".$dpuqtyout.") 
                      WHERE mpno = '".$dpunomp."' AND materi = '".$dpukdbrg."' AND nopkj = '".$dpunopkj."' and subpkj = '".$dpusubpkj."'";

              if (!mysql_query($sql9,$conn)){
                die('Error (Update detail MP): ' . mysql_error());
              }

              // update data rekap pengambilan barang
              $sql10 = "UPDATE cldambilbrg SET 
                        dpunomutasi = '".$nobukti."'
                        -- , dpucetak = 1 
                        WHERE dpunobukti = '".$dpunobukti."' AND dpunomp = '".$dpunomp."' AND dpunopkj = '".$dpunopkj."' AND 
                        dpukdpkj = '".$dpukdpkj."' AND dpunosubpkj = '".$dpunosubpkj."' AND dpusubpkj = '".$dpusubpkj."' AND 
                        dpukdbrg = '".$dpukdbrg."'";

              if (!mysql_query($sql10,$conn)){
                die('Error (Update rekap pengambilan barang): ' . mysql_error());
              }

              // tambah data di rekap penerimaan mp untuk matus mp
              $sql11 = "SELECT hpunomp, hpukdpkj FROM kmmatusmp 
                        WHERE hpukdgdg = '".$hpukdgdg."' and hpunomp = '".$hpunomp."' and hpukdpkj = '".$hpukdpkj."'";
              $res11 = mysql_query($sql11,$conn);
              $row11 = mysql_num_rows($res11);

              if ($row11 == 0) {
                $sql12 = "INSERT INTO kmmatusmp (hpukdgdg,hputgltrm,hpukddept,hpunomp,hpukdpkj,access,komp,userby) 
                          VALUES 
                          ('".$hpukdgdg."',
                          curdate(),
                          '".$hpukddept."',
                          '".$hpunomp."',
                          '".$hpukdpkj."',
                          now(),
                          '".$komp."',
                          '".$userby."')";

                if (!mysql_query($sql12,$conn)){
                  die('Error (Insert matus MP): ' . mysql_error());
                }
              }
            }
          }
          else{
            $xstatus = 2;
          }
        }
      }
      else {
        $xstatus = 1;
      }

      // membuka lock mutasi
      $sql13 = "UPDATE kmcounter SET 
                lockbuk = 0 
                WHERE ckddept = '".$ckddept."' AND ckdgdg = '".$hpukdgdg."' AND ckdtrans = '".$hpukdtrans."' AND cbultah = '".$month.$year."'";

      if (!mysql_query($sql13,$conn)){
        die('Error (Update lockbuk): ' . mysql_error());
      }

      // pelunasan
      $sql14 = "SELECT * FROM cldambilbrg 
                WHERE dpunobukti = '".$inhpunobukti."' AND dpunomp = '".$hpunomp."' AND dpuqtylns > 0 AND ISNULL(dpunolunas) 
                ORDER BY dpunopkj,dpunosubpkj";
      $res14 = mysql_query($sql14,$conn);

      while ($data14 = mysql_fetch_array($res14)) {
        $dpukdbrg = strtoupper(trim($data14["dpukdbrg"]));
        $dpunmbrg = strtoupper(trim($data14["dpunmbrg"]));
        $dpunopkj = strtoupper(trim($data14["dpunopkj"]));
        $dpukdpkj = strtoupper(trim($data14["dpukdpkj"]));
        $dpunosubpkj = $data14["dpunosubpkj"];
        $dpusubpkj = strtoupper(trim($data14["dpusubpkj"]));
        $dpuqty = $data14["dpuqty"];
        $dpuqtyout = $data14["dpuqtyout"];
        $dpusatuan = $data14["dpusatuan"];
        $dpuqtylns = $data14["dpuqtylns"];

        $datax = $ckddept."|".$hpukdgdg."|LNS|".$komp."|".$userby;
        $nobukti_lns = get("pelunasan_mp",$datax,$conn);

        // update detail rekap
        $sql15 = "UPDATE cldambilbrg SET 
                  dpunolunas = '".$nobukti_lns."' 
                  WHERE dpunobukti = '".$inhpunobukti."' AND dpunomp = '".$hpunomp."' AND dpunopkj = '".$dpunopkj."' AND 
                  dpukdpkj = '".$dpukdpkj."' AND dpunosubpkj = '".$dpunosubpkj."' AND dpusubpkj = '".$dpusubpkj."' AND 
                  dpukdbrg = '".$dpukdbrg."'";

        if (!mysql_query($sql15,$conn)){
          die('Error (Update cldambilbrg): ' . mysql_error());
        }

        $sql16 = "UPDATE clmpdet2 
                  SET backorder = backorder + (".$dpuqtylns.") 
                  WHERE mpno = '".$hpunomp."' AND materi = '".$dpukdbrg."' and nopkj = '".$dpunopkj."' AND 
                  nosubpkj = '".$dpunosubpkj."' and subpkj = '".$dpusubpkj."'";

        if (!mysql_query($sql16,$conn)){
          die('Error (Update clmpdet2): ' . mysql_error());
        }

        $sql17 = "INSERT INTO kmlunasmp (lnnobukti,lntgl,lnnomp,lnkdpkj,lnnopkj,lnsubpkj,lnnosubpkj,lnkdbrg,lnqty,lnsatuan,lnambil,lnsisa,
                  lnrekapmp,access,komp,userby) 
                  VALUES 
                  ('".$nobukti_lns."',
                  curdate(),
                  '".$hpunomp."',
                  '".$dpukdpkj."',
                  '".$dpunopkj."',
                  '".$dpusubpkj."',
                  '".$dpunosubpkj."',
                  '".$dpukdbrg."',
                  '".$dpuqty."',
                  '".$dpusatuan."',
                  '".$dpuqtyout."',
                  '".$dpuqtylns."',
                  '".$inhpunobukti."',
                  now(),
                  '".$komp."',
                  '".$userby."')";

        if (!mysql_query($sql17,$conn)){
          die('Error (Update lockbuk): ' . mysql_error());
        }
      }

  }

  if ($xstatus == "") {
    echo "Cetak Bukti Sukses"; 
  }
  elseif ($xstatus == 1) {
    echo "Data Realisasi Sudah dilakukan Cetak Bukti Mutasi";
  }
  elseif ($xstatus == 2) {
    echo "Data Realisasi Masih Ada yang kosong !";
  }
}
elseif ($intxtmode == "update_detail_mp") {
  if ($inhpukdpkj == "ASSEMBLY") {
    $nopkj = 1;
  }
  elseif ($inhpukdpkj == "BOTTOM"){
    $nopkj = 2;
  }
  elseif ($inhpukdpkj == "CUTTING L"){
    $nopkj = 3;
  }
  elseif ($inhpukdpkj == "CUTTING NL"){
    $nopkj = 4;
  }
  elseif ($inhpukdpkj == "STITCHING"){
    $nopkj = 5;
  }

  // delete detail cldambilbrg
  $sql = "DELETE FROM cldambilbrg
          WHERE 
          dpunobukti = '".$inhpunobukti."' AND dpunomp = '".$indpunomp."' AND dpukdpkj = '".$inhpukdpkj."'";
  if (!mysql_query($sql,$conn)){
    die('Error (Delete cldambilbrg): ' . mysql_error());
  }

  $sql1 = "SELECT 
          dt1.*, dt2.*, (dt2.qty - dt2.ambil - dt2.backorder) AS sisa, 
          (SELECT nmbrg FROM kmmstbhnbaku WHERE kdbrg = dt2.materi) as nmbrg
          FROM 
          (SELECT a.cust, a.mpno, a.article, a.`last`, a.noso, a.colour, a.tot, a.ket 
          FROM clmphead a 
          WHERE a.mpno = '".$indpunomp."')dt1
          INNER JOIN 
          (SELECT b.mpno, b.nopkj, b.nosubpkj, b.subpkj, b.materi, b.calc, b.qty, b.nstn, b.ambil, b.backorder 
          FROM clmpdet2 b 
          WHERE b.mpno = '".$indpunomp."' AND nopkj = '".$nopkj."')dt2
          ON dt1.mpno = dt2.mpno";
  $res1 = mysql_query($sql1,$conn);
  $row1 = mysql_num_rows($res1);

  if ($row1 > 0) {
    while ($data1 = mysql_fetch_array($res1)) {
      $materi = $data1["materi"];
      $nopkj = $data1["nopkj"];
      $nosubpkj = $data1["nosubpkj"];
      $subpkj = $data1["subpkj"];
      $qty = $data1["qty"];
      $nstn = $data1["nstn"];
      $sisa = $data1["sisa"];
      $nmbrg = mysql_real_escape_string($data1["nmbrg"]);

      if ($inhpukdtrans == "ADD" || $inhpukdtrans == "REC") {
        $sql2   = "SELECT a.dpunobukti 
                  FROM cldambilbrg a 
                  WHERE a.dpunobukti = '".$inhpunobukti."' AND a.dpunomp = '".$indpunomp."' 
                  AND a.dpukdbrg = '".$materi."' AND a.dpunopkj = '".$nopkj."' 
                  AND a.dpunosubpkj = '".$nosubpkj."'";
        $res2 = mysql_query($sql2,$conn);
        $row2 = mysql_num_rows($res2);

        if ($row2 == 0 ) {
          $sql3   = "INSERT INTO cldambilbrg 
                    (dpunobukti,
                    dpunomp,
                    dpukdpkj,
                    dpunopkj,
                    dpusubpkj,
                    dpunosubpkj,
                    dpukdbrg,
                    dpunmbrg,
                    dpuqty,
                    dpusatuan,
                    access,
                    komp,
                    userby) 
                    VALUES 
                    ('".$inhpunobukti."',
                    '".$indpunomp."',
                    '".$inhpukdpkj."',
                    '".$nopkj."',
                    '".$subpkj."',
                    '".$nosubpkj."',
                    '".$materi."',
                    '".$nmbrg."',
                    '".$qty."',
                    '".$nstn."',
                    now(), 
                    '".$_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"]."',
                    '".$_SESSION[$domainApp."_myname"]."')";

          if (!mysql_query($sql3,$conn)){
            die('Error (Insert): ' . mysql_error());
          }
        }
      }
      else {
        if ($sisa > 0) {
          $sql4   = "SELECT a.dpunobukti 
                    FROM cldambilbrg a 
                    WHERE a.dpunobukti = '".$inhpunobukti."' AND a.dpunomp = '".$indpunomp."' 
                    AND a.dpukdbrg = '".$materi."' AND a.dpunopkj = '".$nopkj."' 
                    AND a.dpunosubpkj = '".$nosubpkj."'";
          $res4 = mysql_query($sql4,$conn);
          $row4 = mysql_num_rows($res4);

          if ($row4 == 0 ) {
            $sql5   = "INSERT INTO cldambilbrg 
                      (dpunobukti,
                      dpunomp,
                      dpukdpkj,
                      dpunopkj,
                      dpusubpkj,
                      dpunosubpkj,
                      dpukdbrg,
                      dpunmbrg,
                      dpuqty,
                      dpusatuan,
                      access,
                      komp,
                      userby) 
                      VALUES 
                      ('".$inhpunobukti."',
                      '".$indpunomp."',
                      '".$inhpukdpkj."',
                      '".$nopkj."',
                      '".$subpkj."',
                      '".$nosubpkj."',
                      '".$materi."',
                      '".$nmbrg."',
                      '".$qty."',
                      '".$nstn."',
                      now(), 
                      '".$_SESSION[$domainApp."_mygroup"]." # ".$_SESSION[$domainApp."_mylevel"]."',
                      '".$_SESSION[$domainApp."_myname"]."')";

            if (!mysql_query($sql5,$conn)){
              die('Error (Insert): ' . mysql_error());
            }
          }
        }
      }
    }
  }
  echo(1);
}
mysql_close($conn);
?>