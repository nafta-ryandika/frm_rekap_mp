$(document).ready(function() {
    $("#inhputgl").mask("99/99/9999");
    $("#inhputgl").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });

    $("input[name=intype]").bind("keydown", function(event) {
        if (event.which === 13) {
            event.stopPropagation();
            event.preventDefault();
            $(':input:eq(' + ($(':input').index(this) + 1) + ')').focus();
        }
    });

    // $("#table_mp").on("click", "tr", function(){ // Click event handler.
    //         var clickedRow = $(this).parent().index();
    //         clickedRow = clickedRow  + 1;
    //         var nomp = $('#table_mp tr:eq('+clickedRow+') td:eq(0)').text();
    //         var pkj = $('#table_mp tr:eq('+clickedRow+') td:eq(1)').text();
    //         get_detail_mp(nomp,pkj);
    //         // alert(clickedRow);
    //     });

    $("#table_mp").on( "click", "tr", function(){
        var nomp = $(this).children("td:eq(0)").text();
        var pkj = $(this).children("td:eq(1)").text();
        get_detail_mp(nomp,pkj);
    });
});

function check(event, id) {
    if (event.keyCode == 13 || event.keyCode == 9) {
        if (id == "check_transaksi") {
            var data = "intxtmode=check_transaksi&inhpukdtrans=" + $("#inhpukdtrans").val() + "";
            $.ajax({
                url: "actfrm.php",
                data: data,
                type: "POST",
                cache: false,
                success: function(data) {
                    if (data == 0) {
                        openDialog("transaksi");
                    }
                    else {
                        $("#innmtrans").val(data);
                        $("#inhpukdgdg").focus();
                    }
                }
            });
        }
        else if (id == "check_gudang") {
            var data = "intxtmode=check_gudang&inhpukdgdg=" + $("#inhpukdgdg").val() + "";
            $.ajax({
                url: "actfrm.php",
                data: data,
                type: "POST",
                cache: false,
                success: function(data) {
                    if (data == 0) {
                        openDialog("gudang");
                    }
                    else {
                        $("#innmgdg").val(data);
                        $("#inhpukddept").focus();
                    }
                }
            });
        }
        else if (id == "check_departemen") {
            var data = "intxtmode=check_departemen&inhpukddept=" + $("#inhpukddept").val() + "";
            $.ajax({
                url: "actfrm.php",
                data: data,
                type: "POST",
                cache: false,
                success: function(data) {
                    if (data == 0) {
                        openDialog("departemen");
                    }
                    else {
                        $("#innmdept").val(data);
                        $("#inhpukdpkj").focus();
                    }
                }
            });
        }
        else if (id == "check_mp") {
            if ($("#inhpunomp").val() == ""){
                alert("No. MP Kosong !");
            }
            else {
                var data = "intxtmode=check_mp&inhpunomp=" + $("#inhpunomp").val() + "&inhpukdpkj=" + $("#inhpukdpkj").val() + "&inhpunobukti=" + $("#inhpunobukti").val();
                $.ajax({
                    url: "actfrm.php",
                    data: data,
                    type: "POST",
                    cache: false,
                    success: function(data) {
                        if (data == 2) {
                            if (check("","check_table_mp") == 0){
                                var check_form = check("","check_form");
                                if(check_form == 0){
                                    add();
                                    get_detail_mp($("#inhpunomp").val(),$("#inhpukdpkj").val());
                                    disabled();
                                    $("#inhpunomp").val("");
                                }
                                else if (check_form == 2) {
                                    alert("Input Kode Transaksi Kosong !");
                                    $("#inhpukdtrans").focus();
                                }
                                else if (check_form == 3) {
                                    alert("Input Kode Gudang Kosong !");
                                    $("#inhpukdgdg").focus();
                                }
                                else if (check_form == 4) {
                                    alert("Input Tujuan Kosong !");
                                    $("#inhpukddept").focus();
                                }
                                else if (check_form == 5) {
                                    alert("Input Tujuan Kosong !");
                                    $("#inhputgl").focus();
                                }
                                else if (check_form == 6) {
                                    alert("Input Pekerjaan Kosong !");
                                    $("#inhpukdpkj").focus();
                                }

                            }
                            else{
                                alert("No. MP tersebut sudah ada !");
                                $("#inhpunomp").val("");
                            }
                        }
                        else if(data == 1){
                            alert("No. MP Tersebut Belum di Issued !");
                            $("#inhpunomp").val("");
                        }
                        else if(data == 3){
                            alert("Kode Pekerjaan di MP Tersebut Tidak Ada !");
                            $("#inhpunomp").val("");
                        }
                        else if(data == 4){
                            alert("No. MP tersebut sudah ada !");
                            $("#inhpunomp").val("");
                        }
                        else {
                            alert("No. MP Tersebut Tidak Ada !");
                            $("#inhpunomp").val("");
                        }
                    }
                });
            }
        }
    }
    else {
        if (id == "check_table_mp") {
            var row = 0;
            if ($(".nomp").length > 0) {
                $(".nomp").each(function() {
                    if (($("#inhpunomp").val()).toUpperCase() == ($(this).text()).toUpperCase()) {
                        row = 1;
                        return false;
                    }
                })
            }
            return row;
        }
        else if (id == "check_form"){
            var status = 0;
            if ($("#inhpukdtrans").val() == ""){
                status = 2;
            }
            else if ($("#inhpukdgdg").val() == ""){
                status = 3;
            }
            else if ($("#inhpukddept").val() == ""){
                status = 4;
            }
            else if ($("#inhputgl").val() == ""){
                status = 5;
            }
            else if ($("#inhpukdpkj").val() == ""){
                status = 6;
            }
            return status;
        }
        else if (id == "check_realisasi") {
           $.ajax({
                url: "actfrm.php",
                data: "intxtmode=check_realisasi&inhpunobukti=" + $("#inhpunobukti").val(),
                type: "POST",
                cache: false,
                success: function(data) {
                    return data;
                }
            }) 
        }
    }
}

function get_autocomplete(id){
  if(id == "inhpukdtrans"){  
    var url = "get_transaksi.php";
  }
  else if(id == "inhpukdgdg") {
    var url = "get_gudang.php";
  }
  else if(id == "inhpukddept") {
    var url = "get_departemen.php";
  }

  $("#"+id).autocomplete({
    source: url,
    focus: function(event, ui) {
        event.preventDefault();
        $(this).val(ui.item.label);
    },
    select: function (event, ui) {
        // event.preventDefault();
        $("#id_"+id).val(ui.item.value);
          
        if(id == "inhpukdtrans"){
            $("#innmtrans").val(ui.item.nama);
            $("#inhpukdgdg").focus();
        }
        else if(id == "inhpukdgdg") {
            $("#innmgdg").val(ui.item.nama); 
            $("#inhpukddept").focus();  
        }
        else if(id == "inhpukddept") {
            $("#innmdept").val(ui.item.nama);
            $("#inhpukdpkj").focus();   
        }
    }
  });
}

function get_detail_mp(nomp,pkj){
    if (nomp == "") {
        var nomp =  $("#inhpunomp").val();
    }

    $("#row_mpdetail").val(0);
    $("#row_id_mpdetail").val(1);

    var data = "intxtmode=get_detail_mp&inhpunomp="+nomp+"&inhpukdpkj="+pkj+"";
    $.ajax({
        url: "actfrm.php",
        data: data,
        type: "POST",
        cache: false,
        success: function(data) {
            data = data.split("#@");

            var nmbrg = data[0];
            var ket = data[1];
            var qty = data[2];
            var satuan = data[3];
            var sisa = data[4];

            nmbrg = nmbrg.split("|");
            ket = ket.split("|");
            qty = qty.split("|");
            satuan = satuan.split("|");
            sisa = sisa.split("|");

            $("#table_mpdetail tbody tr").remove();

            for (var i = nmbrg.length - 2; i >= 0; i--) {
                var innmbrg = nmbrg[i];
                var inket = ket[i];
                var inqty = qty[i];
                var insatuan = satuan[i];
                var insisa = sisa[i];

                var table = document.getElementById('table_mpdetail').getElementsByTagName('tbody')[0];
                var row = table.insertRow(eval($("#row_mpdetail").val()));
                var data = $("#row_id_mpdetail").val();

                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);
                var cell5 = row.insertCell(4);

                cell1.style.textAlign = "left";
                cell2.style.textAlign = "left";
                cell3.style.textAlign = "right";
                cell4.style.textAlign = "center";
                cell5.style.textAlign = "right";

                cell1.innerHTML = "<span id=\"nmbrg" + data + "\" class=\"nmbrg\">" + innmbrg.toUpperCase() + "</span>";
                cell2.innerHTML = "<span id=\"ket" + data + "\" class=\"ket\">" + inket.toUpperCase() + "</span>";
                cell3.innerHTML = "<span id=\"qty" + data + "\" class=\"qty\">" + inqty.toUpperCase() + "</span>";
                cell4.innerHTML = "<span id=\"satuan" + data + "\" class=\"satuan\">" + insatuan.toUpperCase() + "</span>";
                cell5.innerHTML = "<span id=\"sisa" + data + "\" class=\"sisa\">" + insisa.toUpperCase() + "</span>";

                // row.addEventListener("mouseover", highlight);
                // row.addEventListener("mouseout", normal);
                
                $("#row_mpdetail").val(eval($("#row_mpdetail").val()) + 1);
                $("#row_id_mpdetail").val(eval($("#row_id_mpdetail").val()) + 1);
            }
        }
    });
}

function enter(event,id){
    if (event.keyCode == 13 || event.keyCode == 9) {
        if (id == "inhpukdpkj") {
            $("#inhpunomp").focus();
        }
    }
}

function add(){
    if ($("#inhpunobukti").val() == "") {
        generate_id();
    }

    var inhpunobukti = $("#inhpunobukti").val();
    var inhpukdtrans = $("#inhpukdtrans").val();
    var innmtrans = $("#innmtrans").val();
    var inhpukdgdg = $("#inhpukdgdg").val();
    var innmgdg = $("#innmgdg").val();
    var inhpukddept = $("#inhpukddept").val();
    var innmdept = $("#innmdept").val();
    var inhputgl = $("#inhputgl").val();
    var inhpukdpkj = $("#inhpukdpkj").val();
    var inhpunomp = $("#inhpunomp").val();

    var table = document.getElementById('table_mp').getElementsByTagName('tbody')[0];
    var row = table.insertRow(eval($("#row_mp").val()));
    var data = $("#row_id_mp").val();

    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);

    cell1.style.textAlign = "center";
    cell2.style.textAlign = "center";
    cell3.style.textAlign = "center";

    cell1.innerHTML = "<span id=\"nomp" + data + "\" class=\"nomp\">" + inhpunomp.toUpperCase() + "</span>";
    cell2.innerHTML = "<span id=\"pkj" + data + "\" class=\"pkj\">" + inhpukdpkj.toUpperCase() + "</span>";
    cell3.innerHTML = "<img id=\"remove" +data+ "\" src=\"img/delete.png\" onclick=\"remove(this)\" class=\"remove\" style=\"cursor: pointer; vertical-align: center;\" title=\"Delete Row\" >";

    // cell1.addEventListener('click', click);
    // cell2.addEventListener('click', click);
    // row.addEventListener('click', click);

    // row.addEventListener("mouseover", highlight);
    // row.addEventListener("mouseout", normal);
    
    // console.log(eval($("#row_mp").val()));

    $("#table_mp tbody tr").each(function(){
    $(this)
        .attr("class", "normal")
        .attr("onMouseOver", "this.className='highlight'")
        .attr("onMouseOut", "this.className='normal'")
        .attr("style", "cursor: pointer");
    });

    $("#row_mp").val(eval($("#row_mp").val()) + 1);
    $("#row_id_mp").val(eval($("#row_id_mp").val()) + 1);
    $("#intotmp").val(eval($("#intotmp").val()) + 1);
}

function remove(row){
  if (confirm("Delete Data MP?")){
    // if (($(".nomp").length == 1) && ($("#intxtmode").val() == "edit")) {
    //     alert("Detail Data tinggal 1 !");
    // }
    // else {
        var idx = (row.id).split("remove");
        var nomp = $("#nomp"+idx[1]).text();

        if (nomp != "") {
            var datax = $("#delete_mp").val();
            var removex = datax+nomp.trim()+"|";
            $("#delete_mp").val(removex);

            $("#table_mpdetail tbody tr").remove();
        }

        var row = row.parentNode.parentNode;
        row.parentNode.removeChild(row);
        $("#row_mp").val(eval($("#row_mp").val())-1);
        $("#intotmp").val(eval($("#intotmp").val()) - 1);
    // }
  }
}

function update_detail_mp(nomp,kdpkj){
     $.ajax({
        url: "actfrm.php",
        data: "intxtmode=update_detail_mp&inhpunobukti="+$("#inhpunobukti").val()+"&indpunomp="+nomp+"&inhpukdpkj="+kdpkj+"&inhpukdtrans="+$("#inhpukdtrans").val(),
        type: "POST",
        cache: false,
        success: function(data) {
                if (data == 1) {
                    alert("Data Detail MP Berhasil di Update !");
                    get_detail_mp(nomp,kdpkj);
                }
                else{
                    alert(data);
                }
        }
    });
}

function click(row){
    var nomp = $(this).children("td:eq(0)").text();
    var pkj = $(this).children("td:eq(1)").text();
    // console.log(clickedRow);
    // clickedRow = clickedRow  + 1;
    // var nomp = $('#table_mp tr:eq('+clickedRow+') td:eq(0)').text();
    // var pkj = $('#table_mp tr:eq('+clickedRow+') td:eq(1)').text();
    get_detail_mp(nomp,pkj);
}

function highlight(row){
    $(this).addClass('highlight');
}

function normal(row){
    $(this).removeClass('highlight');
}

function generate_id(){
    $.ajax({
        url: "actfrm.php",
        data: "intxtmode=generate_id&inhputgl="+$("#inhputgl").val(),
        type: "POST",
        cache: false,
        success: function(data) {
                $("#inhpunobukti").val(data);
        }
    });
}

function generate_year(){
    var input  = $("#inhputgl").val();
    input = input.split("/");

    if (input[0].includes("_") == false && input[1].includes("_") == false){
        var year = new Date().getFullYear();
        var tgl = input[0]+"/"+input[1]+"/"+year;
        $("#inhputgl").val(tgl);
    }
}
