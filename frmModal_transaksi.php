<?php
  include("../../connection.php");
?>
<script type="text/javascript">
    $(document).ready(function() {
      var rows = document.getElementById("table_transaksi").children[1].children;
      var selectedRow = 0;
      var baris = 1;
      $( "#table_transaksi" ).keydown(function(e) {
        e.preventDefault();
        rows[selectedRow].classList.remove("highlight");
        
        if(e.keyCode == 38){
          selectedRow--;
          baris--;
        } 
        else if(e.keyCode == 40){  
          selectedRow++;
          baris++;
        }
        else if (e.keyCode == 13){
          var kdtrans = $('#table_transaksi tr:eq('+baris+') td:eq(0)').text();
          var nmtrans = $('#table_transaksi tr:eq('+baris+') td:eq(1)').text();
          
          select(kdtrans,nmtrans,"transaksi");
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
      });
    rows[0].className += "highlight"; 
    });
</script>

<div>
  <fieldset class="info_fieldset"><legend>Search</legend>
    <table id="table_transaksi" class="table" tabindex="0">
      <thead>
        <tr>
          <th align="center">Kode Transaksi</th>
          <th align="center">Nama Transaksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT kdtrans, nmtrans FROM kmmstout";
          $res = mysql_query($sql,$conn);

          while ($data = mysql_fetch_array($res)) {
        ?>
          <tr onclick="select('<?=$data["kdtrans"]?>','<?=$data["nmtrans"]?>','transaksi')" onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" style="cursor: pointer;">
            <td><?=$data["kdtrans"]?></td>
            <td><?=$data["nmtrans"]?></td>
          </tr>
        <?php
          }
        ?>
      </tbody>
    </table>
  </fieldset>
</div>