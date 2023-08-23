<?php
  $xrdm = date("YmdHis");
  include("../../configuration.php");
  include("../../connection.php");
?>
<script type="text/javascript" src="js/frminput_rekap.js?version=<?=$xrdm?>"></script>

<fieldset class="info_fieldset"><legend>Form Input</legend>
  <table width="40%"  border="0" cellspacing="0" cellpadding="0"  style="display: inline-block;">
    <tr>
      <td>
        <label style="width: 150px;">Transaksi</label>
        <select class='txtfield' name="intype" id="intransaksi" onkeypress="enter(event,this.id)" style="margin-bottom: 5px;" onchange="change('transaksi')" onkeydown="enter_rekap(event,this.id)">
          <option value='1'>Cetak Rekap</option>
          <option value='2'>Realisasi Pengambilan</option>
          <option value='3'>Outstanding Rekap by Barang</option>
          <option value='4'>Outstanding Rekap by MP</option>
          ?>
        </select>
    </tr>
    <tr>
      <td>
        <div id="param_transaksi">
          <label style="width: 150px;">No. Bukti Rekap</label>
          <input id="inhpunobukti" class="textbox" type="text" name="intype" style="width: 180px;" onclick="xdept()" onfocus="xdept()" onkeydown="enter_rekap(event,this.id)"><br/>
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div align="center" style="margin: 10px;">
          <input id="instatus" type="hidden" value="0">
          <input id="inkdbrg" type="hidden" value="">
          <input id="cmdsave" class="buttonadd" type="button" name="nmcmdsave" value="  Cetak Rekap" onclick="check('nobukti')">
          <input id="cmdclear" class="buttonclear" type="button" name="nmcmdclear" value="  Clear" onclick="clear_rekap()">
          <input id="cmdcancel" class="buttondelete" type="button" name="nmcmdcancel" value="Cancel" onclick="cancelclick()">
          <input id="cmdcetak_bukti" class="buttondone" type="button" name="nmcmdcetak_bukti" value="  Cetak Bukti" onclick="cetak_bukti()" title="Cetak Bukti Mutasi Keluar Produksi">
        </div>
      </td>
    </tr>
  </table>
  <table width="60%" border="0" cellspacing="0" cellpadding="0" style="float: right;">
    <tr>
      <td>
        <fieldset class="info_fieldset"><legend>Form View Barang</legend>
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <div id="frmloading_barang" align="center">
                      <img src="img/ajax-loader.gif" />
                    </div>
                    <div id="frmbody_barang">
                      <div id="frmcontent_barang">
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </table>
          </fieldset>
      </td>
    </tr>
  </table>
</fieldset>
<br/>
<fieldset class="info_fieldset"><legend>Form View</legend>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <div id="frmloading_rekap" align="center">
          <img src="img/ajax-loader.gif" />
        </div>
        <div id="frmbody_rekap">
          <div id="frmcontent_rekap">
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>  
  </table>
</fieldset>