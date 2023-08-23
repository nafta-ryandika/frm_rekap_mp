<?php
  include("../../connection.php");

  if(isset($_POST['xdata'])){
    $xdata = $_POST['xdata'];
  }

  $xdata = explode("#@",rtrim($xdata,'#@'));

?>
<div>
  <fieldset class="info_fieldset"><legend>Search</legend>
    <table id="table_error_rekap" class="table" tabindex="0">
      <thead>
        <tr>
          <th align="center">No. MP</th>
          <th align="center">Nama Barang</th>
          <th align="center">Nama Pekerjaan</th>
          <th align="center">Nama Subpekerjaan</th>
        </tr>
      </thead>
      <tbody>
        <?php
          for ($i=0; $i < count($xdata); $i++) {
            $ydata = explode("|", $xdata[$i]);
            $nomp = $ydata[0];
            $nmbrg = $ydata[1];
            $nmpkj = $ydata[2];
            $nmsubpkj = $ydata[3];

            echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">
                    <td>".$nomp."</td>
                    <td>".$nmbrg."</td>
                    <td>".$nmpkj."</td>
                    <td>".$nmsubpkj."</td>
                  </tr>";
          }
        ?>
      </tbody>
    </table>
  </fieldset>
</div>