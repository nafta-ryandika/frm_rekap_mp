$(document).ready(function() {
    // $("#inhpunobukti").mask("*****/******/*******");
    var dept = xdept();

    $.mask.definitions["*"] = null;
    $.mask.definitions["^"] = "[a-za-zA-Z0-9]";
    $("#inhpunobukti").mask("" + dept + "/^^^^^^/^^^^^^^");
    $("#frmloading_rekap").hide();
    $("#frmloading_barang").hide();

    $("input[name=intype]").bind("keydown", function(event) {
        if (event.which === 13) {
            event.stopPropagation();
            event.preventDefault();
            $(':input:eq(' + ($(':input').index(this) + 1) + ')').focus();
        }
    });
});

function change(id) {
    if (id == "transaksi") {
        if ($("#intransaksi").val() == 3 || $("#intransaksi").val() == 4) {
            var data = "<label style=\"width: 150px;\">Tujuan</label>\n\
                        <input id=\"intujuan\" class=\"textbox\" type=\"text\" name=\"intype\" onkeypress=\"get_autocomplete_rekap(this.id)\" style=\"width: 50px;\">\n\
                        <input id=\"innmtujuan\" class=\"textbox\" type=\"text\" name=\"intype\" onkeypress=\"\" style=\"width: 200px;\"><br/>\n\
                        <label style=\"width: 150px;\">Tgl. Rekap</label>\n\
                        <input id=\"intgl\" class=\"textbox\" type=\"text\" name=\"intype\" style=\"width: 70px;\" onkeydown=\"enter_rekap(event,this.id)\"><br/>";

            $("#param_transaksi").html(data);

            $("#intgl").mask("99/99/9999");

            $("#intgl").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true
            });
        } else {
            var data = "<label style=\"width: 150px;\">No. Bukti Rekap</label>\n\
                        <input id=\"inhpunobukti\" class=\"textbox\" type=\"text\" name=\"intype\" style=\"width: 180px;\" onclick=\"xdept()\" onfocus=\"xdept()\" onkeydown=\"enter_rekap(event,this.id)\"><br/>";

            $("#param_transaksi").html(data);

            // $("#inhpunobukti").mask("*****/******/*******");
            var dept = xdept();

            $.mask.definitions["*"] = null;
            $.mask.definitions["^"] = "[a-za-zA-Z0-9]";
            $("#inhpunobukti").mask("" + dept + "/^^^^^^/^^^^^^^");
        }

        if ($("#intransaksi").val() == 2) {
            $("#cmdsave").val("  Realisasi");
        } else {
            $("#cmdsave").val("  Cetak Rekap");
        }
    }
}

function cetak_rekap() {

    if (xtrans == 1) {
        $("#idx").val($("#inhpunobukti").val());
        $("#formexport").attr('action', 'frmviewpdf.php');
        $("#formexport").submit();
    } else if (xtrans == 2) {
        findclick_rekap();
    }

}


function findclick_rekap(xdata) {
    xdata = xdata.split("|");
    var nobukti = xdata[0];
    var kdbrg = xdata[1];

    $("#inkdbrg").val(kdbrg);

    $("#frmbody_rekap").slideUp('fast', function() {
        $("#frmloading_rekap").slideDown('fast', function() {
            $.ajax({
                url: "frmview_rekap.php",
                type: "POST",
                data: "nobukti=" + nobukti + "&kdbrg=" + kdbrg,
                cache: false,
                success: function(html) {
                    $("#frmcontent_rekap").fadeIn("fast");
                    $("#frmcontent_rekap").html(html);
                    $("#frmbody_rekap").slideDown('fast', function() {
                        $("#frmloading_rekap").slideUp('fast');
                    });
                }
            });
        });
    });
}

function findclick_barang() {
    var data = "inhpunobukti=" + $("#inhpunobukti").val() + "";

    $("#frmbody_barang").slideUp('fast', function() {
        $("#frmloading_barang").slideDown('fast', function() {
            $.ajax({
                url: "frmview_barang.php",
                type: "POST",
                data: data,
                cache: false,
                success: function(html) {
                    $("#frmcontent_barang").fadeIn("fast");
                    $("#frmcontent_barang").html(html);
                    $("#frmbody_barang").slideDown('fast', function() {
                        $("#frmloading_barang").slideUp('fast');
                    });
                }
            });
        });
    });
}

function check(id) {
    if (id == "nobukti") {
        var status = $("#instatus").val();

        if (($("#intransaksi").val() == 1) || ($("#intransaksi").val() == 2)) {
            var data = "intxtmode=check_" + id + "&inhpunobukti=" + $("#inhpunobukti").val();
            $.ajax({
                url: "actfrm.php",
                type: "POST",
                data: data,
                cache: false,
                success: function(data) {
                    xdata = data.split("#$");
                    if (xdata[0] == 0) {
                        alert("Data Tidak Ditemukan !");
                    } else if (xdata[0] == 1) {
                        $("#intransaksi").attr('disabled', true);
                        $("#inhpunobukti").attr('disabled', true);
                        var xtrans = $("#intransaksi").val();

                        if (xtrans == 1) {
                            if (xdata[1] != "") {
                                $("#intransaksi").attr('disabled', false);
                                $("#inhpunobukti").attr('disabled', false);
                                $.ajax({
                                    url: "frmModal_error_rekap.php",
                                    data: "xdata=" + xdata[1],
                                    type: "POST",
                                    cache: false,
                                    success: function(html) {
                                        $("#frmbody").slideDown("slow");
                                        $("#dialog-open").html(html);

                                        var lebar = 700;
                                        var tinggi = 300;

                                        $("#dialog-open").dialog({
                                            autoOpen: true,
                                            modal: true,
                                            height: tinggi,
                                            width: lebar,
                                            title: "View Error Data Tidak Ditemukan di Detail MP !",
                                            close: function(event) {
                                                $("#dialog-open").hide();
                                                $("#dialog-open").html("");
                                            }
                                        });
                                    }
                                });
                            } else {
                                $("#idx").val($("#inhpunobukti").val());
                                $("#formexport").attr('action', 'frmviewpdf.php');
                                $("#formexport").submit();

                                $("#intransaksi").attr('disabled', false);
                                $("#inhpunobukti").attr('disabled', false);
                            }
                        } else if (xtrans == 2) {
                            findclick_barang();
                            $("#instatus").val(1);
                        }
                    }
                }
            })
        }
        else if ($("#intransaksi").val() == 3) {
            var tujuan = $("#intujuan").val();
            var tgl = $("#intgl").val();

            $("#idx").val(tujuan+"|"+tgl);
            $("#formexport").attr('action', 'frmviewpdf1.php');
            $("#formexport").submit();

            $("#intransaksi").attr('disabled', false);
            $("#inhpunobukti").attr('disabled', false);
        }
        else if ($("#intransaksi").val() == 4) {
            var tujuan = $("#intujuan").val();
            var tgl = $("#intgl").val();

            $("#idx").val(tujuan+"|"+tgl);
            $("#formexport").attr('action', 'frmviewpdf2.php');
            $("#formexport").submit();

            $("#intransaksi").attr('disabled', false);
            $("#inhpunobukti").attr('disabled', false);
        }
    }
}

function enter_rekap(event, id) {
    if (event.keyCode == 13) {
        if (id == "inhpunobukti") {
            $("#cmdsave").focus();
        } 
        else if (id == "intransaksi") {
            if (($("#intransaksi").val()) == 1 || ($("#intransaksi").val()) == 2) {
                $("#inhpunobukti").focus();
            } else {
                $("#intujuan").focus();
            }
        }
        else if (id == "intgl") {
            $("#cmdsave").focus();
        }
    }
}

function clear_rekap() {
    $("#intransaksi").attr('disabled', false);
    $("#inhpunobukti").attr('disabled', false);
    $("#frmcontent_rekap").html("");
    $("#frmcontent_barang").html("");
    $("#instatus").val(0);
    $("#inkdbrg").val("");
    $("#inhpunobukti").val("");
}

function get_autocomplete_rekap(id){
   if(id == "intujuan") {
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
         if(id == "intujuan") {
            $("#innmtujuan").val(ui.item.nama);
            $("#intgl").focus();   
         }
    }
  });
}

function change_color(row) {
    // $("#myTable_barang tbody tr").css("background-color", "#FFFFFF");
    // row.style.backgroundColor = "#FFFF99";
    // $("#myTable_barang tbody tr").removeClass("highlight");
    $(row).addClass("color_green");
    $(row).css("border","2px red");
    // setTimeout(function () {$(row).removeClass("highlight")}, 2000);
}

function simpan_rekap(event, id) {
    if (id == 0) {
        var indpunomp = "";
        var indpunopkj = "";
        var indpukdpkj = "";
        var indpunosubpkj = "";
        var indpusubpkj = "";
        var insisa = "";
        var inrealisasi = "";
        var inpelunasan = "";

        if ($(".indpunomp").length > 0) {
            $(".indpunomp").each(function() {
                indpunomp += $(this).val() + "|";
            })
        }

        if ($(".indpunopkj").length > 0) {
            $(".indpunopkj").each(function() {
                indpunopkj += $(this).val() + "|";
            })
        }

        if ($(".indpukdpkj").length > 0) {
            $(".indpukdpkj").each(function() {
                indpukdpkj += $(this).val() + "|";
            })
        }

        if ($(".indpunosubpkj").length > 0) {
            $(".indpunosubpkj").each(function() {
                indpunosubpkj += $(this).val() + "|";
            })
        }

        if ($(".indpusubpkj").length > 0) {
            $(".indpusubpkj").each(function() {
                indpusubpkj += $(this).val() + "|";
            })
        }

        if ($(".insisa").length > 0) {
            $(".insisa").each(function() {
                insisa += $(this).val() + "|";
            })
        }

        if ($(".inrealisasi").length > 0) {
            $(".inrealisasi").each(function() {
                inrealisasi += $(this).val() + "|";
            })
        }

        if ($(".inpelunasan").length > 0) {
            $(".inpelunasan").each(function() {
                if ($(this).is(":checked")) {
                    inpelunasan += $(this).val() + "|";
                } else {
                    inpelunasan += "|";
                }
            })
        }

        var data = "intxtmode=simpan_rekap&inhpunobukti=" + $("#inhpunobukti").val() +
            "&indpukdbrg=" + encodeURIComponent($("#inkdbrg").val()) +
            "&indpunomp=" + encodeURIComponent(indpunomp) +
            "&indpunopkj=" + encodeURIComponent(indpunopkj) +
            "&indpukdpkj=" + encodeURIComponent(indpukdpkj) +
            "&indpunosubpkj=" + encodeURIComponent(indpunosubpkj) +
            "&indpusubpkj=" + encodeURIComponent(indpusubpkj) +
            "&insisa=" + insisa +
            "&inrealisasi=" + inrealisasi +
            "&inpelunasan=" + inpelunasan;

        $.ajax({
            url: "actfrm.php",
            data: data,
            type: "POST",
            cache: false,
            success: function(data) {
                if (data == 1) {
                    alert("Data Berhasil Disimpan");
                    findclick_rekap($("#inhpunobukti").val()+"|"+$("#inkdbrg").val());
                } else {
                    alert(data);
                }
            }
        })
    } else if (id == 1) {
        if (event.keyCode == 13) {
            var x = $(this).val();
            // console.log(x);
        }
    }
}

function check_bukti() {
    var xdata;
    $.ajax({
        url: "actfrm.php",
        type: "POST",
        async: false,
        data: "intxtmode=check_bukti&inhpunobukti=" + $("#inhpunobukti").val(),
        cache: false,
        success: function(data) {
            xdata = data;
        }
    })
    return xdata;
}

function cetak_bukti() {
    if (check_bukti() == 0) {
        alert("Data Tidak Ditemukan !");
        $("#inhpunobukti").focus();
    } else if (check_bukti() > 0) {
        if (confirm("Apakah Anda Sudah Memasukkan Semua Data Realisasi Rekap Pengambilan Barang Untuk No. Bukti Tersebut ? \n Cetak Bukti Mutasi Keluar !")) {
            var data = "intxtmode=cetak_bukti&inhpunobukti="+$("#inhpunobukti").val();
            $.ajax({
                url: "actfrm.php",
                type: "POST",
                data: data,
                cache: false,
                success: function(data) {
                  alert(data);
                }
            })
        }
    }
}

function number(e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if (
        $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl/cmd+A
        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: Ctrl/cmd+C
        (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: Ctrl/cmd+V
        (e.keyCode == 86 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: Ctrl/cmd+X
        (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)
    ) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if (
        (e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) &&
        (e.keyCode < 96 || e.keyCode > 105)
    ) {
        e.preventDefault();
    }
}

function checkAll(ele) {
    var checkboxes = document.getElementsByTagName('input');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
        $(".inrealisasi").val(0);
        $(".txtsisa").text(0);
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }
}

function set_zero(row){
    if (($("#txtsisa"+row).text()) == ($("#inrealisasi"+row).val())) {
        $("#inrealisasi"+row).val(0);
        $("#txtsisa"+row).text(0);
    }
}

function calc_sisa(row){
    var xsisa = $("#insisa"+row).val();
    if (xsisa > 0) {
        var txtsisa = xsisa - ($("#inrealisasi"+row).val());
        $("#txtsisa"+row).text(txtsisa);
    }
}

function enter_realisasi(event,row){
    row = row + 1;
    if (event.keyCode == 13 || event.keyCode == 9) {
        $("#inrealisasi"+row).focus();
        $("#inrealisasi"+row).select();
    }
}