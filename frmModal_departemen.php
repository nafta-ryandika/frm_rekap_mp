<?php
  include("../../connection.php");
  if (isset($_POST['data'])) {
    $data = $_POST['data'];
  }
?>
<script type="text/javascript">
  $(document).ready(function(){
    $("#frmloadingmodal").hide();
    $("#txtdatamodal").val('<?=$data?>');
    findclick_modal();
    }
  );
  
  function enterfindmodal(event){
    if(event.keyCode==13){
      findclick_modal();
    }
    else{
      return ;
    }
  };

  function showpage_modal(page) {
    $("#txtpagemodal").val(page);
    findclick_modal();
  }

  function prevpage_modal() {
    var n = eval($("#txtpagemodal").val()) - 1;
    if (n >= 1) {
      $("#txtpagemodal").val(n);
      findclick_modal();
    }
  }

  function nextpage_modal() {
    var n = eval($("#txtpagemodal").val()) + 1;
    if (eval(n) <= eval($("#jumpagemodal").val())) {
      $("#txtpagemodal").val(n);
      findclick_modal();
    }
  }
  
  function findclick_modal(){
    var id = '<?=$inid?>'
    var data = "txtpagemodal="+$("#txtpagemodal").val()+
               "&txtperpagemodal="+$("#txtperpagemodal").val()+
               "&txtdatamodal="+$("#txtdatamodal").val()+
               "&txtfield="+$("#txtfieldmodal").val()+
               "&inid="+id+
               "";
           
    $("#frmbodymodal").slideUp(function(){
    // $("#frmloadingmodal").slideDown(function(){
      $.ajax({
        url: "frmviewModal_departemen.php",
        type: "POST",
        data: data,
        cache: false,
        success: function (html) {
                  $("#frmcontentmodal").html(html);
                  $("#frmbodymodal").slideDown(function(){
                    // $("#frmloadingmodal").slideUp();
                  });
        }
      });
    // });
    });
  };
</script>

  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <div id="areasearch">
          <fieldset class="info_fieldset"><legend>Search</legend>
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign = "center" style="vertical-align: center;">
                  <td>
                    <label style="width: 30px" >Data</label>
                    <INPUT type="text" id="txtdatamodal" onkeypress="findclick_modal()" autofocus=""/>
                  </td>
                  <td>
                    <INPUT id="cmdfind_modal" class="buttongofind" type="button" name="cmdfind_modal" value="Find" onclick="findclick_modal()"/>
                  </td>
                    <INPUT id="txtperpagemodal" class="textbox" type="hidden" name="txtperpagemodal" value="15" onkeydown="enterfindmodal(event)"/>
                    <INPUT id="txtpagemodal" class="textbox" type="hidden" name="nmtxtpagemodal" value="1"/>
              </table>
          </fieldset>
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div id="frmloadingmodal" align="center">
          <img src="img/ajax-loader.gif" />
        </div>
        <div id="frmbodymodal">
          <div id="frmcontentmodal">
          </div>
        </div>
      </td>
    </tr>
  </table>