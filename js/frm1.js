$(document).ready(function() {
    $("#frmloading").hide();
    $("#tabelinput").hide();
    // addnewclick();
    // findclick();
});

function enterfind(event) {
    if (event.keyCode == 13) {
        findclick();
    } else {
        return;
    }
};

function findclick() {
    var n = $(".txtfield").length;
    var txtfield = '';
    var txtparameter = '';
    var txtdata = '';
    var data = '';

    if (n > 1) {
        $(".txtfield").each(function() {
            txtfield += $(this).val() + "|";
        });

        $(".txtparameter").each(function() {
            txtparameter += $(this).val() + "|";
        });

        $(".txtdata").each(function() {
            txtdata += $(this).val() + "|";
        });

        data = "txtpage=" + $("#txtpage").val() +
            "&txtperpage=" + $("#txtperpage").val() +
            "&txtfield=" + txtfield +
            "&txtparameter=" + txtparameter +
            "&txtdata=" + txtdata +
            "&all_data=" + $("#chk_all_data:checked").val() +
            "";
    } else {
        data = "txtpage=" + $("#txtpage").val() +
            "&txtperpage=" + $("#txtperpage").val() +
            "&txtfield=" + $(".txtfield").val() +
            "&txtparameter=" + $(".txtparameter").val() +
            "&txtdata=" + $(".txtdata").val() +
            "&all_data=" + $("#chk_all_data:checked").val() +
            "";
    }

    $("#frmbody").slideUp('fast', function() {
        $("#frmloading").slideDown('fast', function() {
            $.ajax({
                url: "frmview.php",
                type: "POST",
                data: data,
                cache: false,
                success: function(html) {
                    $("#frmcontent").html(html);
                    $("#frmbody").slideDown('fast', function() {
                        $("#frmloading").slideUp('fast');
                    });
                }
            });
        });
    });
};

function addnewclick() {
    showinput();
    clearinput();
    $("#intxtmode").val('add');
    $("#mode").text('Add New');
    $("#tabelview").fadeOut('slow',function() {
        $("#tabelinput").fadeIn('slow');
        $("#inhputgl").focus();
        $("#inhputgl").mask("99/99/9999");
    });
};

function add_rekap(){
    showinput_rekap();
    $("#intxtmode").val('add_rekap');
    $("#mode").text('Add Rekap');
    $("#tabelview").fadeOut(function() {
        $("#tabelinput").fadeIn();
        // $("#inhpunobukti").mask("*****/******/*******");
        var dept = xdept();

        $.mask.definitions["*"] = null;
        $.mask.definitions["^"] = "[a-za-zA-Z0-9]";
        $("#inhpunobukti").mask(""+dept+"/^^^^^^/^^^^^^^");
        $("#intransaksi").focus();
    })
}

function showinput() {
    var data = "";
    if (($("input:checked").val()) != "") {
        data = "inhpunobukti="+$("input:checked").val();    
    }
    
    $.ajax({
        url: "frminput.php",
        type: "POST",
        data: data,
        cache: false,
        success: function(html) {
            $("#areainput").html(html);
        }
    });
}

function showinput_rekap(){
    $.ajax({
        url: "frminput_rekap.php",
        type: "POST",
        cache: false,
        success: function(html) {
            $("#areainput").html(html);
        }
    })
}

function deleteclick() {
    var check = $("#chk:checked").length;
    $("input:checked").each(function() {
        var datax = ($(this).val()).split("|");
        var data = "intxtmode=delete&inhpunobukti="+datax[0]+"&inhpunomp="+datax[1]+"&inhpukdpkj="+datax[2];
        $.ajax({
            url: "actfrm.php",
            type: "POST",
            data: data,
            cache: false,
            success: function(data) {
                // alert(data);
            }
        });
    });
    alert(check + " data berhasil dihapus");
    findclick();
};

function editclick() {
    var datax = ($("input:checked").val()).split("|");
    var data = "intxtmode=getedit&inhpunobukti="+datax[0]+"";
    showinput();
    clearinput();
    $("#intxtmode").val('edit');
    $("#mode").text('Edit');
    $.ajax({
        url: "actfrm.php",
        type: "POST",
        data: data,
        cache: false,
        success: function(data) {
            $("#areaedit").html(data);
            setinput();
            $("#tabelview").fadeOut(function() {
                $("#tabelinput").fadeIn();
                disabled();
                $("#inhpunomp").focus();
            });
        }
    });
};

function exportclick() {
    if ($("#txtSQL").val() == "") {
        alert("Search Data Terlebih Dahulu !");
    } else {
        var randomnumber = Math.floor(Math.random() * 11)
        var exptype = $("#exporttype").val();
        switch (exptype) {
            case 'grd':
                $("#formexport").attr('action', 'frmviewgrid.php');
                $("#formexport").submit();
                break;
            case 'pdf':
                $("#formexport").attr('action', 'frmviewpdf.php');
                $("#formexport").submit();
                break;
            case 'xls':
                $("#formexport").attr('action', 'frmviewxls.php');
                $("#formexport").submit();
                break;
            case 'csv':
                $("#formexport").attr('action', 'frmviewcsv.php');
                $("#formexport").submit();
                break;
            case 'txt':
                $("#formexport").attr('action', 'frmviewtxt.php');
                $("#formexport").submit();
                break;
            default:
                alert('Unidentyfication Type');
        }
    }
};

function setinput() {
    $("#inhpunobukti").val($("#gethpunobukti").text());
    $("#inhpukdtrans").val($("#gethpukdtrans").text());
    $("#inhpukdgdg").val($("#gethpukdgdg").text()); 
    $("#inhpukddept").val($("#gethpukddept").text());
    $("#inhputgl").val($("#gethputgl").text());
    $("#inhpukdpkj").val($("#gethpukdpkj").text()); 
    $("#innmtrans").val($("#getnmtrans").text());
    $("#innmgdg").val($("#getnmgdg").text());
    $("#innmdept").val($("#getnmdept").text());
};

function clearinput() {
    $("#areainput").html('');
};

function disabled() {
    $("#inhpukdtrans").attr('disabled', true);
    $("#inhpukdgdg").attr('disabled', true);
    $("#inhpukddept").attr('disabled', true);
    $("#inhputgl").attr('disabled', true);
    $("#inhpukdpkj").attr('disabled', true);
};

function enabled() {
    $("#inhpukdtrans").attr('disabled', false);
    $("#inhpukdgdg").attr('disabled', false);
    $("#inhpukddept").attr('disabled', false);
    $("#inhputgl").attr('disabled', false);
    $("#inhpukdpkj").attr('disabled', false);
};

function resetinput(id) {
    if (id == "all") {
        $("#inhpunobukti").val("");
        $("#inhpukdtrans").val("");
        $("#innmtrans").val("");
        $("#inhpukdgdg").val("");
        $("#innmgdg").val("");
        $("#inhpukddept").val("");
        $("#innmdept").val("");
        $("#inhputgl").val("");
        $("#inhpukdpkj").val("");
        $("#inhpunomp").val("");
        $("#intotmp").text("");
        $("#delete_mp").val("");
    } 
}

function saveclick() {
    $("#cmdsave").attr('disabed', 'disabled');

    var inhpunomp = "";
    if ($(".nomp").length > 0) {
        $(".nomp").each(function() {
            inhpunomp += $(this).text() + "|";
        })
    }

    var data =  "intxtmode=" + $("#intxtmode").val() +
                "&inhpunobukti=" + encodeURIComponent($("#inhpunobukti").val()) +
                "&inhpukdtrans=" + encodeURIComponent($("#inhpukdtrans").val()) +
                "&inhpukdgdg=" + encodeURIComponent($("#inhpukdgdg").val()) +
                "&inhpukddept=" + encodeURIComponent($("#inhpukddept").val()) +
                "&inhputgl=" + encodeURIComponent($("#inhputgl").val()) +
                "&inhpukdpkj=" + encodeURIComponent($("#inhpukdpkj").val()) +
                "&inhpunomp=" + encodeURIComponent(inhpunomp) +
                "&indelete_mp=" + encodeURIComponent($("#delete_mp").val()) +
                "";

    $.ajax({
        url: "actfrm.php",
        type: "POST",
        data: data,
        cache: false,
        success: function(data) {
            if ($("#intxtmode").val()=='edit'){
                alert(data);
                // cancelclick();
            }
            else {
                alert(data);
                resetinput("all");
                clearRow("table_mp");
                clearRow("table_mpdetail");
                enabled();
                $("#intotmp").val("0");
                $("#inhputgl").focus();
            }
            $("#cmdsave").attr('disabed', '');
        }
    });
};

function cancelclick() {
    clearinput();
    $("#intxtmode").val('');
    $("#mode").text('');
    $("#tabelinput").fadeOut("slow", function() {
        $("#tabelview").fadeIn("slow");
    });
    $("#frmcontent").html("");
};

function getAutocomplete(id) {
    if (id == "insubpkj") {
        var url = "get_subpkj.php";
    } else if (id == "innmbrg_lama" || id == "innmbrg_baru") {
        var url = "get_nmbrg.php";
    } else if (id == "innmsupp_lama" || id == "innmsupp_baru") {
        var url = "get_nmsupp.php";
    }

    $("#" + id).autocomplete({
        source: url,
        focus: function(event, ui) {
            event.preventDefault();
            $("#" + id).val(ui.item.label);

            if (id == "innmbrg_lama") {
                $("#innmbrgx_lama").val(ui.item.value);
                $("#show_nmbrgx_lama").text(ui.item.value);
                $("#insatuan_lama").val(ui.item.satuan);
            } else if (id == "innmbrg_baru") {
                $("#innmbrgx_baru").val(ui.item.value);
                $("#show_nmbrgx_baru").text(ui.item.value);
                $("#insatuan_baru").val(ui.item.satuan);
            } else if (id == "innmsupp_lama") {
                $("#innmsuppx_lama").val(ui.item.value);
                $("#show_nmsuppx_lama").text(ui.item.value);
            } else if (id == "innmsupp_baru") {
                $("#innmsuppx_baru").val(ui.item.value);
                $("#show_nmsuppx_baru").text(ui.item.value);
            } else {
                $("#" + id + "x").val(ui.item.value);
            }
        },
        select: function(event, ui) {
            event.preventDefault();
            $("#" + id).val(ui.item.label);

            if (id == "innmbrg_lama") {
                $("#innmbrgx_lama").val(ui.item.value);
                $("#show_nmbrgx_lama").text(ui.item.value);
                $("#insatuan_lama").val(ui.item.satuan);
            } else if (id == "innmbrg_baru") {
                $("#innmbrgx_baru").val(ui.item.value);
                $("#show_nmbrgx_baru").text(ui.item.value);
                $("#insatuan_baru").val(ui.item.satuan);
            } else if (id == "innmsupp_lama") {
                $("#innmsuppx_lama").val(ui.item.value);
                $("#show_nmsuppx_lama").text(ui.item.value);
            } else if (id == "innmsupp_baru") {
                $("#innmsuppx_baru").val(ui.item.value);
                $("#show_nmsuppx_baru").text(ui.item.value);
            } else {
                $("#" + id + "x").val(ui.item.value);
            }
        }
    });
}

function checkmp() {
    if ($("#innomp").val() == "") {
        alert("Input No MP Kosong !");
    } else {
        var data = "intxtmode=checkmp&innomp=" + $("#innomp").val();
        $.ajax({
            url: "actfrm.php",
            data: data,
            type: "POST",
            dataType: "html",
            success: function(data) {
                if (data == "clmphead") {
                    alert("Data Header No MP " + $("#innomp").val() + " Tidak Ada !");
                    $("#innomp").val("");
                } else {
                    openDialog("mp");
                }
            }
        });
    }
}

function check_realisasi(xid){
    $.ajax({
        url: "actfrm.php",
        data: "intxtmode=check_realisasi&inhpunobukti=" + xid,
        type: "POST",
        cache: false,
        success: function(data) {
            return data;
        }
    }) 
}

function check_edit(){
    var n = $("input:checked").length;
    if (n > 1) {
        alert('Maksimal pilih 1 data');
    } else if (n == 0) {
        alert('Pilih data untuk mengubah');
    } else {
        var datax = ($("input:checked").val()).split("|");
        $.ajax({
            url: "actfrm.php",
            data: "intxtmode=check_realisasi&inhpunobukti=" + datax[0],
            type: "POST",
            cache: false,
            success: function(data) {
                if (data == 1) {
                alert("No Bukti Rekap Tersebut Sudah Terproses Akhir Bulan !");
                }
                else if (data == 2) {
                    alert("No Bukti Rekap Tersebut Sudah Ada Realisasi !");
                }
                else {
                    editclick();
                }
            }
        })
    } 
}

function check_delete(){
    var n = $("input:checked").length;
    if (n == 0) {
        alert('Pilih data untuk menghapus');
    } else if (confirm("Hapus Data ?")) {
        var datax = ($("input:checked").val()).split("|");
        $.ajax({
            url: "actfrm.php",
            data: "intxtmode=check_realisasi&inhpunobukti=" + datax[0],
            type: "POST",
            cache: false,
            success: function(data) {
                if (data == 1) {
                alert("No Bukti Rekap Tersebut Sudah Terproses Akhir Bulan !");
                }
                else if (data == 2) {
                    alert("No Bukti Rekap Tersebut Sudah Ada Realisasi !");
                }
                else {
                    deleteclick();
                }
            }
        })
    } 
}

function process() {
    var data = "intxtmode=process&innobukti=" + $("#inbukti").val();
    $.ajax({
        url: "actfrm.php",
        data: data,
        type: "POST",
        dataType: "html",
        success: function(data) {
            if (data == 0) {
                alert("Proses Berhasil !");
            } else {
                var datax = data.split("|");

                if (datax[0] == 1) {
                    alert(datax[1]);
                } else if (datax[0] == 2) {
                    alert(datax[1]);
                } else {
                    alert(data);
                }
            }
            $("#inbukti").val("");
        }
    })
}

function checknobukti() {
    if ($("#inbukti").val() == "") {
        alert("Input No Bukti Kosong !");
    } else {
        var data = "intxtmode=checknobukti&innobukti=" + $("#inbukti").val();
        $.ajax({
            url: "actfrm.php",
            type: "POST",
            data: data,
            cache: false,
            success: function(data) {
                if (data == 0) {
                    alert("No. Bukti Tidak Ada !")
                } else {
                    process();
                }
            }
        });
    }
}

function getnobukti() {
    var data = "intxtmode=getnobukti";
    $.ajax({
        url: "actfrm.php",
        type: "POST",
        data: data,
        cache: false,
        success: function(data) {
            $("#innobukti").val(data);
        }
    });
}

function check(datax) {
    var row = 0;
    if ($(".nomp").length > 0) {
        $(".nomp").each(function() {
            if (datax.toUpperCase() == $(this).text()) {
                row = 1;
                return false;
            }
        })
    }
    return row;
}

function removeRow(row, id) {
    if (confirm("Delete Data?")) {
        var row = row.parentNode.parentNode;
        row.parentNode.removeChild(row);
        $("#row_content").val(eval($("#row_content").val()) - 1);
    }
}

function clearRow(id) {
    if(id == "table_mp"){
        $("#table_mp tbody tr").remove();
        $("#row_mp").val(0);
        $("#row_id_mp").val(1);
    }
    else if (id == "table_mpdetail") {
        $("#table_mpdetail tbody tr").remove();
        $("#row_mpdetail").val(0);
        $("#row_id_mpdetail").val(1);
    }
}

function openDialog(id) {
    if (id == "transaksi") {
        $.ajax({
          url: "frmModal_transaksi.php",
          type: "POST",
          cache: false,
          success: function(html) {
            $("#frmbody").slideDown("slow");
            $("#dialog-open").html(html);

            var lebar = 600;
            var tinggi = 200;

            $("#dialog-open").dialog({
              autoOpen: true,
              modal: true,
              height: tinggi,
              width: lebar,
              title: "View Transaksi",
              close: function(event) {
                $("#dialog-open").hide();
                $("#dialog-open").html("");
              }
            });
          }
        })
    }
    else if (id == "gudang") {
        var data = "data="+$("#inhpukdgdg").val();
        $.ajax({
          url: "frmModal_gudang.php",
          data: data, 
          type: "POST",
          cache: false,
          success: function(html) {
            $("#frmbody").slideDown("slow");
            $("#dialog-open").html(html);

            var width = screen.width;
            var height = screen.height;
            var lebar = 600;
            var tinggi = 450;

            $("#dialog-open").dialog({
              autoOpen: true,
              modal: true,
              height: tinggi,
              width: lebar,
              title: "View Gudang",
              close: function(event) {
                $("#dialog-open").hide();
                $("#dialog-open").html("");
              }
            });
          }
        })
    }
    else if (id == "departemen") {
        var data = "data="+$("#inhpukddept").val();
        $.ajax({
          url: "frmModal_departemen.php",
          data: data, 
          type: "POST",
          cache: false,
          success: function(html) {
            $("#frmbody").slideDown("slow");
            $("#dialog-open").html(html);

            var width = screen.width;
            var height = screen.height;
            var lebar = 600;
            var tinggi = 450;

            $("#dialog-open").dialog({
              autoOpen: true,
              modal: true,
              height: tinggi,
              width: lebar,
              title: "View Departemen",
              close: function(event) {
                $("#dialog-open").hide();
                $("#dialog-open").html("");
              }
            });
          }
        })
    }
}

function select(kd, nm, id) {
    if (id == "transaksi") {
        $("#inhpukdtrans").val(kd);
        $("#innmtrans").val(nm);
        $("#inhpukdgdg").focus();
    }
    else if (id == "gudang") {
        $("#inhpukdgdg").val(kd);
        $("#innmgdg").val(nm);
        $("#inhpukddept").focus();
    }
    else if (id == "departemen") {
        $("#inhpukddept").val(kd);
        $("#innmdept").val(nm);
        $("#inhpukdpkj").focus();
    }
    $("#dialog-open").dialog("close");
}

function search(id) {
    var data = $("#txtdatamodal").val();
    
    if (id == "gudang") {
        var $rows = $('#table_gudang tbody tr');
        $rows.show().filter(function() {
          var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
          return !~text.indexOf(data);
        }).hide();
    }
}


function change(id) {
    if (id == "insize") {
        if ($("#insize").val() == 2) {
            $("#qty_size").show();
        } else {
            $("#qty_size").hide();
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

function searchclick() {
    if ($("#areasearch").is(":hidden")) {
        $("#areasearch").slideDown("slow");
    } else {
        $("#areasearch").slideUp("slow");
    }
};

// ******************************* START JS MULTISEARCH ***************************************
var xrow = 1;

function addSearch() {
    var table = document.getElementById("tblSearch");

    // Create an empty <tr> element and add it to the 1st position of the table:
    var row = table.insertRow(xrow);

    // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    var cell5 = row.insertCell(4);

    //  cell2.className = 'txtmultisearch';

    // Add some text to the new cells:
    cell1.innerHTML = "Field : \n\
          <select class='txtfield' id='txtfield" + xrow + "' onchange=\"setFilterData(" + xrow + ")\">\n\
          <option value=''>-</option>\n\
          <option value='hpunobukti'>No. Bukti</option>\n\
          <option value='hpunomp'>No. MP</option>\n\
          <option value=\"DATE_FORMAT(hputgl,'%d/%m/%Y')\">Tanggal</option>\n\
          <option value='hpukdtrans'>Transaksi</option>\n\
          </select>";
    cell2.innerHTML = "<select class='txtparameter'>\n\
              <option value='like'>like</option>\n\
              <option value='equal'>equal</option>\n\
              <option value='notequal'>not equal</option>\n\
              <option value='less'>less</option>\n\
              <option value='lessorequal'>less or equal</option>\n\
              <option value='greater'>greater</option>\n\
              <option value='greaterorequal'>greater or equal</option>\n\
              <option value='isnull'>is null</option>\n\
              <option value='isnotnull'>is not null</option>\n\
              <option value='isnotnull'>is not null</option>\n\
              <option value='isin'>is in</option>\n\
              <option value='isnotin'>is not in</option>\n\
          </select>";
    cell3.innerHTML = "<div id='filter_data" + xrow + "'>Data : <input type='text' class='txtdata' onkeydown='enterfind(event)'></div>";
    cell4.innerHTML = "<input type='button' value='[+]' onclick='addSearch()'>";
    cell5.innerHTML = "<input type='button' value='remove' onclick=\"deleteRow(this)\" style='cursor:pointer;'>";

    xrow++;
}

function deleteRow(btn) {
    if (btn == "rmv1") {
        $("#txtfield0").val("");
        $("#txtparameter0").val("equal");

        var data_select =
            "Data : <input type='text' class='txtdata' onkeydown='enterfind(event)'>";

        $("#filter_data0").html(data_select);
        $("#txtdata0").val("");
    } else {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
        xrow--;
    }
}

function setFilterData(rowx) {
    if ($("#txtfield" + rowx).val() == "DATE_FORMAT(hputgl,'%d/%m/%Y')") {
        var data_select =
            "Data : <input type='text' class='txtdata' id='txtdata" + rowx + "' onkeydown='enterfind(event)'>";

        $("#filter_data" + rowx).html(data_select);

        $("#txtdata" + rowx).mask("99/99/9999");

        $("#txtdata" + rowx).datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
        // console.log("#txtfield" + rowx);
    } 
    else if ($("#txtfield" + rowx).val() == "hpunobukti") {
        var data_select =
            "Data : <input type='text' class='txtdata' id='txtdata" + rowx + "' onkeydown='enterfind(event)'>";

        $("#filter_data" + rowx).html(data_select);

        // $("#txtdata" + rowx).mask("99/99/9999");
        var dept = xdept();

        $.mask.definitions["*"] = null;
        $.mask.definitions["^"] = "[a-za-zA-Z0-9]";
        $("#txtdata" + rowx).mask(""+dept+"/^^^^^^/^^^^^^^");
    }
    else {
        var data_select =
            "Data : <input type='text' class='txtdata' id='txtdata" + rowx + "' onkeydown='enterfind(event)'>";

        $("#filter_data" + rowx).html(data_select);

        $("#txtdata" + rowx).unmask("99/99/9999");

        var dept = xdept();
        $.mask.definitions["*"] = null;
        $.mask.definitions["^"] = "[a-za-zA-Z0-9]";
        $("#txtdata" + rowx).unmask(""+dept+"/^^^^^^/^^^^^^^");
    }
}

function xdept(){
    var xdata;
    $.ajax({
        url: "actfrm.php",
        type: "POST",
        async: false,
        data: "intxtmode=xdept",
        cache: false,
        success: function(data) {
            xdata = data;
        }
    })
    return xdata;
}

// ******************************* END JS MULTISEARCH ***************************************

function showpage(page) {
    $("#txtpage").val(page);
    findclick();
}

function prevpage() {
    var n = eval($("#txtpage").val()) - 1;
    if (n >= 1) {
        $("#txtpage").val(n);
        findclick();
    }
}

function nextpage() {
    var n = eval($("#txtpage").val()) + 1;
    if (eval(n) <= eval($("#jumpage").val())) {
        $("#txtpage").val(n);
        findclick();
    }
}

$(function() {
    $("#tglmasuk").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });
    $("#tglkontrak").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });
    $("#intxttglmasuk").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });
    $("#intxttglkontrak").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });
});


function MyValidDate(dateString) {
    var validformat = /^\d{1,2}\/\d{1,2}\/\d{4}$/ //Basic check for format validity
    if (!validformat.test(dateString)) {
        return ''
    } else { //Detailed check for valid date ranges
        var dayfield = dateString.substring(0, 2);
        var monthfield = dateString.substring(3, 5);
        var yearfield = dateString.substring(6, 10);
        var MyNewDate = monthfield + "/" + dayfield + "/" + yearfield;

        if (checkValidDate(MyNewDate) == true) {
            var SQLNewDate = yearfield + "/" + monthfield + "/" + dayfield;
            return SQLNewDate;
        } else {
            return '';
        }
    }
}

function checkValidDate(dateStr) {
    // dateStr must be of format month day year with either slashes
    // or dashes separating the parts. Some minor changes would have
    // to be made to use day month year or another format.
    // This function returns True if the date is valid.
    var slash1 = dateStr.indexOf("/");
    if (slash1 == -1) { slash1 = dateStr.indexOf("-"); }
    // if no slashes or dashes, invalid date
    if (slash1 == -1) { return false; }
    var dateMonth = dateStr.substring(0, slash1)
    var dateMonthAndYear = dateStr.substring(slash1 + 1, dateStr.length);
    var slash2 = dateMonthAndYear.indexOf("/");
    if (slash2 == -1) { slash2 = dateMonthAndYear.indexOf("-"); }
    // if not a second slash or dash, invalid date
    if (slash2 == -1) { return false; }
    var dateDay = dateMonthAndYear.substring(0, slash2);
    var dateYear = dateMonthAndYear.substring(slash2 + 1, dateMonthAndYear.length);
    if ((dateMonth == "") || (dateDay == "") || (dateYear == "")) { return false; }
    // if any non-digits in the month, invalid date
    for (var x = 0; x < dateMonth.length; x++) {
        var digit = dateMonth.substring(x, x + 1);
        if ((digit < "0") || (digit > "9")) { return false; }
    }
    // convert the text month to a number
    var numMonth = 0;
    for (var x = 0; x < dateMonth.length; x++) {
        digit = dateMonth.substring(x, x + 1);
        numMonth *= 10;
        numMonth += parseInt(digit);
    }
    if ((numMonth <= 0) || (numMonth > 12)) { return false; }
    // if any non-digits in the day, invalid date
    for (var x = 0; x < dateDay.length; x++) {
        digit = dateDay.substring(x, x + 1);
        if ((digit < "0") || (digit > "9")) { return false; }
    }
    // convert the text day to a number
    var numDay = 0;
    for (var x = 0; x < dateDay.length; x++) {
        digit = dateDay.substring(x, x + 1);
        numDay *= 10;
        numDay += parseInt(digit);
    }
    if ((numDay <= 0) || (numDay > 31)) { return false; }
    // February can't be greater than 29 (leap year calculation comes later)
    if ((numMonth == 2) && (numDay > 29)) { return false; }
    // check for months with only 30 days
    if ((numMonth == 4) || (numMonth == 6) || (numMonth == 9) || (numMonth == 11)) {
        if (numDay > 30) { return false; }
    }
    // if any non-digits in the year, invalid date
    for (var x = 0; x < dateYear.length; x++) {
        digit = dateYear.substring(x, x + 1);
        if ((digit < "0") || (digit > "9")) { return false; }
    }
    // convert the text year to a number
    var numYear = 0;
    for (var x = 0; x < dateYear.length; x++) {
        digit = dateYear.substring(x, x + 1);
        numYear *= 10;
        numYear += parseInt(digit);
    }
    // Year must be a 2-digit year or a 4-digit year
    if ((dateYear.length != 2) && (dateYear.length != 4)) { return false; }
    // if 2-digit year, use 50 as a pivot date
    if ((numYear < 50) && (dateYear.length == 2)) { numYear += 2000; }
    if ((numYear < 100) && (dateYear.length == 2)) { numYear += 1900; }
    if ((numYear <= 0) || (numYear > 9999)) { return false; }
    // check for leap year if the month and day is Feb 29
    if ((numMonth == 2) && (numDay == 29)) {
        var div4 = numYear % 4;
        var div100 = numYear % 100;
        var div400 = numYear % 400;
        // if not divisible by 4, then not a leap year so Feb 29 is invalid
        if (div4 != 0) { return false; }
        // at this point, year is divisible by 4. So if year is divisible by
        // 100 and not 400, then it's not a leap year so Feb 29 is invalid
        if ((div100 == 0) && (div400 != 0)) { return false; }
    }
    // date is valid
    return true;
}

// arrow table
var addEvent = (function(window, document) {
    if (document.addEventListener) {
        return function(elem, type, cb) {
            if ((elem && !elem.length) || elem === window) {
                elem.addEventListener(type, cb, false);
            } else if (elem && elem.length) {
                var len = elem.length;
                for (var i = 0; i < len; i++) {
                    addEvent(elem[i], type, cb);
                }
            }
        };
    } else if (document.attachEvent) {
        return function(elem, type, cb) {
            if ((elem && !elem.length) || elem === window) {
                elem.attachEvent('on' + type, function() { return cb.call(elem, window.event) });
            } else if (elem.length) {
                var len = elem.length;
                for (var i = 0; i < len; i++) {
                    addEvent(elem[i], type, cb);
                }
            }
        };
    }
})(this, document);

//derived from: http://stackoverflow.com/a/10924150/402706
function getpreviousSibling(element) {
    var p = element;
    do p = p.previousSibling;
    while (p && p.nodeType != 1);
    return p;
}

//derived from: http://stackoverflow.com/a/10924150/402706
function getnextSibling(element) {
    var p = element;
    do p = p.nextSibling;
    while (p && p.nodeType != 1);
    return p;
};