<?php
  $xrdm = date("YmdHis");
  include("../../configuration.php");
  include("../../connection.php");

  if(isset($_POST['inhpunobukti'])){
    $inhpunobukti = strtoupper($_POST['inhpunobukti']);
    $inhpunobukti = explode("|",$inhpunobukti);
    $inhpunobukti = $inhpunobukti[0];
  }
// var_dump($inhpunobukti);

  if ($inhpunobukti != "") {
    $sql = "SELECT a.hpunomp, a.hpukdpkj 
            FROM clhambilbrg a 
            WHERE a.hpunobukti = '".$inhpunobukti."' 
            ORDER BY  a.hpunomp, a.access;";
    $res = mysql_query($sql,$conn);
    $row  = mysql_num_rows($res);
    $baris = 1;

    if ($row == 0) {
      $rowx = 0;
    }
    else {
      $rowx = $row - 1;
    }


    echo "<input type=\"hidden\" id=\"row_mp\" value=\"".$row."\">";
    echo "<input type=\"hidden\" id=\"row_id_mp\" value=\"".($row+1)."\">";
  }
?>
<script type="text/javascript" src="js/frminput.js?version=<?=$xrdm?>"></script>

<!-- DATA TABLE -->
<!-- <script type="text/javascript" src="DataTables/datatables.js"></script>
<link rel="stylesheet" href="DataTables/datatables.css?version=<?=$xrdm?>"/>

<script type="text/javascript">
  $(document).ready(function(){
    $('#table_mp').DataTable({"autoWidth": false,
                              "lengthChange": false,
                              "searching": false,
                              "pageLength": 20
                            });
  });
</script> -->

<fieldset class="info_fieldset"><legend>Form Input</legend>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td style="width: 50%;">
        <label style="width: 150px;">No. Bukti Rekap</label>
        <input id="inhpunobukti" class="textbox" type="text" name="intype" style="width: 250px;" disabled><br/>
      </td>
      <td style="width: 50%;">
        <label style="width: 150px;">Tanggal</label>
        <input id="inhputgl" class="textbox" type="text" name="intype" style="width: 80px;" maxlength="10" placeholder="dd/mm/YYYY" onkeydown=" "><br/>
      </td>
    </tr>
    <tr>
      <td style="width: 50%;">
        <label style="width: 150px;">Kode Transaksi</label>
        <input id="inhpukdtrans" class="textbox" type="text" name="intype" style="width: 80px" onkeydown="check(event,'check_transaksi')" onkeypress="get_autocomplete(this.id)" > 
        <input id="innmtrans" class="textbox" type="text" name="intype" style="width: 250px" disabled>
      </td>
      <td style="width: 50%;">
        <label style="width: 150px;">Pekerjaan</label>
        <select class='txtfield' name="intype" id="inhpukdpkj" onkeypress="enter(event,this.id)" style="margin-bottom: 5px;">
          <option value=''>-</option>
          <?php
          $sql = "SELECT pkj, ket FROM kmmstpkj ORDER BY pkj";
          $result = mysql_query($sql,$conn);
          while ($data = mysql_fetch_array($result)) {
          ?>
              <option value="<?php echo trim($data["pkj"])?>"><?php echo $data["ket"]?></option>;
          <?php
          }
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td style="width: 50%;">
        <label style="width: 150px;">Kode Gudang</label>
        <input id="inhpukdgdg" class="textbox" type="text" name="intype" style="width: 80px" onkeydown="check(event,'check_gudang')" onkeypress="get_autocomplete(this.id)"> 
        <input id="innmgdg" class="textbox" type="text" name="intype" style="width: 250px" disabled>
      </td>
      <td style="width: 50%;">
        <label style="width: 150px;">No. MP</label>
        <input id="inhpunomp" class="textbox" type="text" name="" style="width: 80px;" onkeypress="check(event,'check_mp')"><br/>
      </td>
    </tr>
    <tr>
      <td>
        <label style="width: 150px;">Tujuan</label>
        <input id="inhpukddept" class="textbox" type="text" name="intype" style="width: 80px" onkeydown="check(event,'check_departemen');" onkeypress="get_autocomplete(this.id)"> 
        <input id="innmdept" class="textbox" type="text" name="intype" style="width: 250px" disabled>
      </td>
      <td>
        <label style="width: 150px;">Total MP</label>
        <input id="intotmp" class="textbox" value="<?=0+($row)?>" type="text" name="intype" style="width: 80px; text-align: right;" disabled>
      </td>
    </tr>
  </table>
  <div align="center" style="margin: 10px;">
    <input id="cmdsave" class="buttonadd" type="button" name="nmcmdsave" value="Save" onclick="saveclick()">
    <input id="cmdcancel" class="buttondelete" type="button" name="nmcmdcancel" value="Cancel" onclick="cancelclick()">
  </div>
</fieldset>
<br/>
<fieldset class="info_fieldset"><legend>Form View</legend>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td style="width: 40%;" valign="top">
        <fieldset class="info_fieldset" style="vertical-align: top;"><legend>MP</legend>
          <input type="hidden" id="delete_mp" value="">
          <table id="table_mp" class="table" tabindex="0">
            <thead>
              <tr>
               <th align="center">No. Mp</th>
               <th align="center">Pekerjaan</th>
               <th align="center">...</th>
             </tr>
           </thead>
           <tbody>
            <?php
                if ($row > 0) {
                  while ($data = mysql_fetch_array($res)) {
                    // get_detail_mp('".$data["hpunomp"]."','".$data["hpukdpkj"]."')
                    echo "<tr onclick=\"\" onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\" style=\"cursor: pointer;\">";
                      echo "<td><span id=\"nomp".$baris."\" class=\"nomp\">".$data["hpunomp"]."</span></td>";
                      echo "<td><span id=\"pkj".$baris."\" class=\"pkj\">".$data["hpukdpkj"]."</span></td>";
                      echo "<td>
                              <img id=\"update".$baris."\" src=\"img/update1.png\" onclick=\"update_detail_mp('".$data["hpunomp"]."','".$data["hpukdpkj"]."')\" class=\"update\" style=\"cursor: pointer; vertical-align: center;\" title=\"Update Detail MP\" >
                              <img id=\"remove".$baris."\" src=\"img/delete.png\" onclick=\"remove(this)\" class=\"remove\" style=\"cursor: pointer; vertical-align: center;\" title=\"Delete Row\" >
                            </td>";
                    echo "</tr>";

                    $baris++;     
                  }
                }
            ?>
           </tbody>
          </table>
        </fieldset>
        <br/>
      </td>
      <td style="width: 60%;" valign="top">
        <fieldset class="info_fieldset" style="vertical-align: top;"><legend>Detail MP</legend>
          <input type="hidden" id="row_mpdetail" value="<?=0+($tambah)?>" size="3">
          <input type="hidden" id="row_id_mpdetail" value="<?=$count_1+1?>" size="3">
          <table id="table_mpdetail" class="table">
            <thead>
              <tr>
                <th>Nama Barang</th>
                <th>Sub Pekerjaan</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Sisa</th>
             </tr>
           </thead>
           <tbody>
             <tr>
             </tr>
           </tbody>
          </table>
        </fieldset>
        <br/>
      </td>
    </tr>
  </table>
</fieldset>