<link rel="stylesheet" href="{{ url('/')}}/assets/custom-style.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
{{-- <link rel="stylesheet" href="{{ Module::asset('library:css/bootstrap.css') }}"> --}}
<script src="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.all.min.js" type="text/javascript"></script>
<script src="{{ url('/')}}/assets/bower_components/moment/moment.js"></script>
<script src="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<style>
    .swal2-checkbox, .swal2-file, .swal2-input, .swal2-radio, .swal2-select, .swal2-textarea {
        margin:auto auto 1.5em auto;
        font-size: 12px;
    }
    .swal2-content-custom {
        text-align:-webkit-left;
        text-align:left;
    }
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }
    .box .overlay, .overlay-wrapper .overlay {
        background: rgba(199, 199, 199, 0.7);
    }
    .select2-container {
        z-index: 99999999999999;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        margin-top: -0.7rem;
    }

    div.dataTables_wrapper div.dataTables_processing {
        background-color: rgba(255, 255, 255, 0.7);
        position: absolute;
        top: 0;
        left:0;
        width: 100%;
        height: 100%;
        text-align: center;
        margin: auto;
    }

    .dataTables_wrapper .dataTables_processing>.fa, .overlay-wrapper .overlay>.fa {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -15px;
        margin-top: -15px;
        color: #000;
        font-size: 30px;
    }

    .searchInput {
        width: 100% !important;
    }

    td b center {
        font-size: 12px;
    }
</style>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': decodeURIComponent($('meta[name="csrf-token"]').attr('content'))
        }
    });

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true,
    });

    var loadingStates = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';

    var HS = {
        ajaxUrl : {
            hsDevCostDetailsDataTable : "{{ url('/')}}/library/harga-satuan/datatable/devcost",
            hsConCostDetailsDataTable : "{{ url('/')}}/library/harga-satuan/datatable/concost",
            hsDevCostCoaItemList : "{{ url('/')}}/library/harga-satuan/coalist/devcost",
            hsConCostCoaItemList : "{{ url('/')}}/library/harga-satuan/coalist/concost",
        },
        ajax : function(url_, dataType_ = "json", type_ = 'get', data_ = null){
            $.ajax({
                url: url_,
                dataType : dataType_,
                type : type_,
                async: true,
                data : data_
            }).done(function(resp){
                return resp;
            });
        },
        toast : {
            success : (title_) => Toast.fire({
                icon: 'success',
                title: title_
            }),
            error   : (title_) => Toast.fire({
                icon: 'error',
                title: title_
            })
        },
        alert : {
            error : (title_, text) => Swal.fire({
                icon : 'error',
                title : title_,
                text : text_
            }),

        }
    }

    $(document).ready(function() {

        var devcost_table = $('#DevCost-list-table').DataTable({
            'paging'       : true,
            'lengthChange' : false,
            'pageLength'   : 5,
            'searching'    : true,
            'ordering'     : true,
            'info'         : true,
            'autoWidth'    : false,
            'processing'   : true,
            'serverSide'   : true,
            "bSortCellsTop": true,
            'ajax'         : {
                url : HS.ajaxUrl.hsDevCostDetailsDataTable,
                cache : false,
            },
            'language' : {
                processing : '<i class="fa fa-refresh fa-spin"></i>'
            },
            'columns'      : [
                { data : null, orderable: false, searchable: false, defaultContent: '', width: '3rem',
                    className: 'devcost-details-control'
                },
                { data : 'coa', name : 'coa', width : '8rem', searchable : true },
                { data : 'ipk_name', name : 'ipk_name', width : '20rem' },
                { data : 'min_nilai', name : 'min_nilai', width: '10rem', className : 'dt-body-right',
                    render: function(data,type,row,meta) {
                        var h = parseFloat(data).toFixed(2)
                        var harga = h.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        var rowSatuan = (row.satuan != null) ? row.satuan : "";
                        var rowMinProjName = (row.min_proj_code !== "undefined") ? row.min_proj_code : "";
                        var temp = "<b>Rp. " + harga + " <br><center>" + rowMinProjName + "</center></b>";
                        return temp;
                    }
                },
                { data : 'max_nilai', name : 'max_nilai', width: '10rem', className : 'dt-body-right',
                    render: function(data,type,row,meta) {
                        var h = parseFloat(data).toFixed(2)
                        var harga = h.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        var rowSatuan = (row.satuan != null) ? row.satuan : "";
                        var rowMaxProjName = (row.max_proj_code !== "undefined") ? row.max_proj_code : "";
                        var temp = "<b>Rp. " + harga + " <br><center>" + rowMaxProjName + "</center></b>";
                        return temp;
                    }
                },
            ],
            initComplete : function(settings, json) {
                //$('#harga_satuan-list-table_wrapper').parents().find('.overlay').remove()
            }
        });

        var concost_table = $('#ConCost-list-table').DataTable({
            'paging'       : true,
            'lengthChange' : false,
            'pageLength'   : 5,
            'searching'    : true,
            'ordering'     : true,
            'info'         : true,
            'autoWidth'    : false,
            'processing'   : true,
            'serverSide'   : true,
            "bSortCellsTop": true,
            'ajax'         : {
                url : HS.ajaxUrl.hsConCostDetailsDataTable,
                cache : false,
                error : function(data){
                    console.log(data)
                }
            },
            'language' : {
                processing : '<i class="fa fa-refresh fa-spin"></i>'
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).attr('id', data.id);
            },
            'columns'      : [
                { data : null, orderable: false, searchable: false, defaultContent: '', width: '3rem',
                    className: 'concost-details-control '
                },
                { data : 'coa', name : 'coa', width : '8rem', searchable : true },
                { data : 'ipk_name', name : 'ipk_name', width : '20rem' },
                { data : 'min_nilai', name : 'min_nilai', width: '10rem', className : 'dt-body-right',
                    render: function(data,type,row,meta) {
                        var h = parseFloat(data).toFixed(2)
                        var harga = h.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        var rowSatuan = (row.satuan != null) ? row.satuan : "";
                        var rowMinProjName = (row.min_proj_name !== "undefined") ? row.min_proj_name : "";
                        var temp = "<b>Rp. " + harga + " </b>";
                        return temp;
                    }
                },
                { data : 'max_nilai', name : 'max_nilai', width: '10rem', className : 'dt-body-right',
                    render: function(data,type,row,meta) {
                        var h = parseFloat(data).toFixed(2)
                        var harga = h.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        var rowSatuan = (row.satuan != null) ? row.satuan : "";
                        var rowMinProjName = (row.max_proj_name !== "undefined") ? row.max_proj_name : "";
                        var temp = "<b>Rp. " + harga + " </b>";
                        return temp;
                    }
                },
            ],
            initComplete : function(settings, json) {
                //$('#harga_satuan-list-table_wrapper').parents().find('.overlay').remove()
            }
        });

        $('#DevCost-list-table_filter').hide()
        $('#ConCost-list-table_filter').hide()
        $('#search_harga_satuan_table').parent().parent().hide()

        $('#DevCost-list-table thead tr:eq(1) th').each(function (i){
            var title = $(this).text();
            $('input', this).on('change clear', function (){
                if (devcost_table.column(i).search() !== this.value) {
                    devcost_table.column(i).search(this.value).draw();
                }
            });
        });

        $('#ConCost-list-table thead tr:eq(1) th').each(function (i){
            var title = $(this).text();
            $('input', this).on('change clear', function (){
                if (concost_table.column(i).search() !== this.value) {
                    concost_table.column(i).search(this.value).draw();
                }
            });
        });

        $('#DevCost-list-table tbody').on('click', 'td.devcost-details-control', function () {
            var tr = $(this).closest('tr');
            var row = devcost_table.row(tr);
            var ipk = devcost_table.row($(this).parents('tr')).data().ipk_id;

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                if(devcost_table.row('.shown').length) {
                    $('.devcost-details-control', devcost_table.row('.shown').node()).click();
                }
                row.child(formatCoaList(row.data(),tr,row, 0)).show();
                tr.addClass('shown');
            }
            var data = ajaxGetCoaList(ipk, 0);
            var tbody = "";
            if(data.length > 0){
                $.each(data, function(i, v){
                    var parseMax = parseFloat(v.max_nilai).toFixed(2)
                    var parseMin = parseFloat(v.min_nilai).toFixed(2)
                    var parseVol = parseFloat(v.volume).toFixed(2)
                    var vol = parseVol.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    var hargaMax = parseMax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    var hargaMin = parseMin.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    var bg = (v.ipk_isDivider == 1) ? 'background-color: cornflowerblue;' : '';
                    // tbody += 
                    //     `<tr style='` + bg + `' data-isTag = ` + v.ipk_isDivider + `>
                    //         <td>` + v.coa + `</td>
                    //         <td>` + v.ipk_name + `</td> 
                    //         <td>` + vol + ` ` + v.satuan + `</td> 
                    //         <td style='text-align:right'><b>Rp. ` + hargaMin + `<br><center>` + v.min_proj_name  + `</b></center></td>
                    //         <td style='text-align:right'><b>Rp. ` + hargaMax + `<br><center>` + v.max_proj_name + `</b></center></td>
                    //     </tr>`;
                    tbody += 
                        `<tr style='` + bg + `' data-isTag = ` + v.ipk_isDivider + `>
                            <td>` + v.coa + `</td>
                            <td>` + v.ipk_name + `</td> 
                            <td>` + vol + ` ` + v.satuan + `</td> 
                        </tr>`;

                });
            }
            $('#table_devcost_coaitem tbody').append(tbody);
        });

        var concost_detail_table = $('#table_concost_coaitem').DataTable({
            'paging'       : false,
            'lengthChange' : false,
            'searching'    : false,
            'ordering'     : true,
            'info'         : false,
            'autoWidth'    : false,
        });

        var loadingStates = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';

        $('#ConCost-list-table tbody').on('click', 'td.concost-details-control', function () {
            $('#ConCost-list-table_wrapper').parent('div').find('.overlay').remove();
            concost_detail_table.destroy();
            var tr = $(this).closest('tr');
            var row = concost_table.row(tr);
            var ipk = concost_table.row($(this).parents('tr')).data().ipk_id;

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                if(concost_table.row('.shown').length) {
                    var _dt = $(this);
                    $('.concost-details-control', concost_table.row('.shown').node()).click();
                }
                row.child(formatCoaList(row.data(),tr,row, 1)).show();
                tr.addClass('shown');
            }
            var data = ajaxGetCoaList(ipk, 1);
            var tbody = "";
            if(data.length > 0){
                $.each(data, function(i, v){
                    var parseMax = parseFloat(v.max_nilai).toFixed(2)
                    var parseMin = parseFloat(v.min_nilai).toFixed(2)
                    var parseVol = parseFloat(v.volume).toFixed(2)
                    var vol = parseVol.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    var hargaMax = parseMax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    var hargaMin = parseMin.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    var bg = (v.ipk_isDivider == 1) ? 'background-color: cornflowerblue;' : '';
                    tbody += 
                    `<tr style='` + bg + `' data-isTag = ` + v.ipk_isDivider + ` data-ipk='` + v.ipk_id + `'>
                            <td class="concost_detail-details-control"></td>
                            <td>` + v.coa + `</td>
                            <td>` + v.ipk_name + `</td> 
                            <td>` + vol + ` ` + v.satuan + `</td> 
                            <td style='text-align:right'><b>Rp. ` + hargaMin + `<br><center>` + v.min_proj_code  + `</b></center></td>
                            <td style='text-align:right'><b>Rp. ` + hargaMax + `<br><center>` + v.max_proj_code + `</b></center></td>
                        </tr>`;

                });
            }
            $('#table_concost_coaitem tbody').append(tbody);

            concost_detail_table = $('#table_concost_coaitem').DataTable({
                    'paging'       : false,
                    'lengthChange' : false,
                    'searching'    : false,
                    'ordering'     : true,
                    'info'         : false,
                    'autoWidth'    : false,
            });
            
            $('#table_concost_coaitem tbody').on('click', 'td.concost_detail-details-control', function () {
                $('#ConCost-list-table_wrapper').parent('div').prepend(loadingStates);
                var tr = $(this).closest('tr');
                var row = concost_detail_table.row(tr);
                var ipk = $(tr).data('ipk');
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    if(concost_detail_table.row('.shown').length) {
                        var _dt = $(this);
                        $('.concost_detail-details-control', concost_detail_table.row('.shown').node()).click();
                    }
                    row.child(formatCoaList(row.data(),tr,row, 1, true)).show();
                    tr.addClass('shown');
                }
                var data = ajaxGetCoaList(ipk, 1);
                var tbody = "";
                if(data.length > 0){
                    $.each(data, function(i, v){
                        var parseMax = parseFloat(v.max_nilai).toFixed(2)
                        var parseMin = parseFloat(v.min_nilai).toFixed(2)
                        var parseVol = parseFloat(v.volume).toFixed(2)
                        var vol = parseVol.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        var hargaMax = parseMax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        var hargaMin = parseMin.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        var bg = (v.ipk_isDivider == 1) ? 'background-color: cornflowerblue;' : '';
                        tbody += 
                        `<tr style='` + bg + `' data-isTag = ` + v.ipk_isDivider + `>
                                <td>` + v.coa + `</td>
                                <td>` + v.ipk_name + `</td> 
                                <td>` + vol + ` ` + v.satuan + `</td> 
                                <td style='text-align:right'><b>Rp. ` + hargaMin + `<br><center>` + v.min_proj_code  + `</b></center></td>
                                <td style='text-align:right'><b>Rp. ` + hargaMax + `<br><center>` + v.max_proj_code + `</b></center></td>
                            </tr>`;

                    });
                }else{
                    tbody += `<tr><td colspan='5'><center>No Data</center></td></tr>`;
                }
                $('#table_concost_coaitem_detail tbody').append(tbody);
                $('#ConCost-list-table_wrapper').parent('div').find('.overlay').remove();
            });

            $('#ConCost-list-table_wrapper').parent('div').find('.overlay').remove();
        });
    });

    //end documentready
    function formatCoaList (d,tr,row, _cost, hasParent = false) {
        var cost = _cost;
        var supplierId = d.id_grup;
        if(cost == 0){
            var temp_table = `
                <div class="row">
                    <div class="box table-responsive" style="padding-left:1rem;padding-right:1rem;">
                        <table id="table_devcost_coaitem" class="table table-dark table-striped table_devcost_coaitem">
                            <thead class="">
                                <tr>
                                    <th>COA Item</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Volume</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>`;
        }else{
            if(hasParent){
                var temp_table = `
                    <div class="row">
                        <div class="box table-responsive" style="padding-left:1rem;padding-right:1rem;">
                            <table id="table_concost_coaitem_detail" class="table table-dark-green table-striped table_concost_coaitem_detail">
                                <thead class="">
                                    <tr>
                                        <th>COA Item</th>
                                        <th>Nama Pekerjaan</th>
                                        <th>Volume</th>
                                        <th>Harga Min</th>
                                        <th>Harga Max</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>`;
            }else{
                var temp_table = `
                    <div class="row">
                        <div class="box table-responsive" style="padding-left:1rem;padding-right:1rem;">
                            <table id="table_concost_coaitem" class="table table-dark table-striped table_concost_coaitem">
                                <thead class="">
                                    <tr>
                                        <th></th>
                                        <th>COA Item</th>
                                        <th>Nama Pekerjaan</th>
                                        <th>Volume</th>
                                        <th>Harga Min</th>
                                        <th>Harga Max</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>`;
            }
        }
        return temp_table;
    }

    function ajaxGetCoaList(coaParent, cost_){
        var cost = cost_;
        var url_ = (cost) ? HS.ajaxUrl.hsConCostCoaItemList : HS.ajaxUrl.hsDevCostCoaItemList
        var parent = coaParent;
        return $.parseJSON($.ajax({
            type    : "post",
            url     : url_,
            data    : { parent : parent },
            async : false,
            success : function(data){
                return data;
            },
            error : function(data){
                console.log(data)
                HS.alert.error('Oooppss..', 'Terjadi kesalahan saat mengambil data')
            }
        }).responseText);
    }



</script>