/*
must include:
- jquery.number.js
- bootstrap-datepicker.js
*/



/*render dotnet json*/
var fnBayar = function(pembayaran,total_belanja)
					{
						var kembalian = parseInt(pembayaran-total_belanja);
						console.log(kembalian);
						//kembalian = kembalian || 0;
						if(kembalian >= 0)
						{
							fnSetAutoNumeric('#total-kembalian');
							fnSetMoney('#Mtotal-kembalian',kembalian);
						}
						//
					}
					
var _fnRenderEquipmentTime = function (data, type, row) {
    
    if (type == 'display') {
        return data.Hours + ":" + data.Minutes;
    }
    return data;
}

var _fnRenderNetDate = function (data, type, row) {
    
    if (type == 'display') {
        var dtStart = new Date(parseInt(data.substr(6)));
        var dtStartWrapper = $.datepicker.formatDate('dd-mm-yy', dtStart);

        return dtStartWrapper;
    }
    return data;
}

var _fnRenderNetTime = function (data, type, row) {
    if (type == 'display') {
        var dtStart = new Date(parseInt(data.substr(6)));
        var h = dtStart.getHours();
        var m = dtStart.getMinutes();
        h = h < 10 ? "0" + h : h;
        m = m < 10 ? "0" + m : m;

        var dtStartWrapper = h + ':' + m;

        return dtStartWrapper;
    }
    return data;
}

var _fnRenderNetDateTime = function (data, type, row) {
    if (type == 'display') {
        return _fnRenderNetDate(data, type, row) + " " + _fnRenderNetTime(data, type, row);
    }
    return data;
}
/*render integer value*/

var _fnRenderInteger= function (data, type, row) {
    if (type == 'display') {
        return $.number(data, 0, ',', '.');
    }
    return data;
}

var _fnRender2DigitDecimal = function (data, type, row) {
    if (type == 'display') {
        return $.number(data, 2, ',', ' ');
    }
    return data;
}

var _fnRenderEmptyCell = function (data, type, row) {
    if (type == 'display') {
        return "";
    }
    return data;
}


//set the first column numbering
var _fnlocalDrawCallback = function (oSettings) {
    
    /* Need to redo the counters if filtered or sorted */
    if (oSettings.bSorted || oSettings.bFiltered) {
        for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
            $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html((i + 1) + '.');
        }
    }
};

var _fnRenderCheckbox = function (data, type, row) {
    if (type == "display") {
        if ((data == 1) || data == true) {
            return "<input type='checkbox' checked='checked'/>";

        } else {
            return "<input type='checkbox'/>";
        }

    }
    return data;
}

var _footerCallback=function ( row, data, start, end, display ) {
                        var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };
 
            // Total over all pages
                            total = api
                                .column( 5 )
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
 
           
 
            // Update footer
                        $(api.column( 5 ).footer()).html(total);
            }

var datatable_idUI = {
    "sProcessing":   "Sedang memproses...",
    "sLengthMenu":   "Tampilkan _MENU_ entri",
    "sZeroRecords":  "[tidak ada data]",
    "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
    "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
    "sInfoPostFix":  "",
    "sSearch":       "Cari: ",
    "sUrl":          "",
    "oPaginate": {
        "sFirst":    "Pertama",
        "sPrevious": "Sebelumnya",
        "sNext":     "Selanjutnya",
        "sLast":     "Terakhir"
    }
}

/*default options for datatables.net*/
var datatableDefaultOptions = {
    "paging": false,
    "searching": false,
    "info": false,
    "fnDrawCallback": _fnlocalDrawCallback,
    "bLengthChange": false,
    "order": [[1, "asc"]],
    "language": datatable_idUI,
  // "footerCallback":_footerCallback,
    "sPaginationType": "bootstrap"
}

/*
datatableDefaultOptions.ordering=false;

*/