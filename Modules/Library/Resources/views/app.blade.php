<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/custom-style.css">
{{-- <link rel="stylesheet" href="{{ Module::asset('library:css/bootstrap.css') }}"> --}}
<script src="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.all.min.js" type="text/javascript"></script>
<script src="{{ url('/')}}/assets/bower_components/moment/moment.js"></script>
<script src="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>

    var loadingStates = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
    $(document).ready(function() {
        var loadingStates = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
        var navigationFn = {
            goToSection: function(id) {
                $('html, body').animate({
                    scrollTop: $(id).offset().top
                }, 0);
            }
        };

        //$('#boxSupplierListTable').append(loadingStates);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var projectTable = $('#project-list-table').DataTable({
            'paging'       : true,
            'lengthChange' : false,
            'pageLength'   : 5,
            'searching'    : true,
            'ordering'     : false,
            'info'         : true,
            'autoWidth'    : false,
        });

        var supplier_table = $('#supplier-list-table').DataTable({
            'paging'       : true,
            'lengthChange' : false,
            'pageLength'   : 5,
            'searching'    : true,
            'ordering'     : false,
            'info'         : true,
            'autoWidth'    : false,
            'processing'   : true,
            'serverSide'   : true,
            'responsive'   : true,
            'ajax'         :  {
                url     :   "{{ url('/')}}/library/supplier/datatable",
                cache   : false,
            },
            'language'     : {
                processing : '<i class="fa fa-refresh fa-spin"></i>',
            },
            'deferRender'  : true,
            'columns'      : [
                { data : 'id', name : 'id', visible : false, searchable: false },
                { data : 'id_grup', name : 'id_grup', visible : false, searchable: false },
                { data : 'nama_supplier', name : 'nama_supplier', width : '250px' },
                { data : 'nama_barang', name : 'nama_barang', width: '200px'},
                { data : 'jenis_barang', name : 'jenis_barang', },
                { data : 'pricelist', orderable : false, defaultContent: '', searchable: false,
                    createdCell : function(cell,cellData,rowData,row,col){
                        if (cellData != null){
                            $(cell).addClass('details-control');
                        }
                    },
                    render: function(data,type,row,meta) {
                        return ""
                    }
                },
                { data : null, name : 'detail', searchable: false,
                    render: function(data,type,row,meta) {
                        return "<center><button class='btn-detail btn btn-success'> Detail </button></center>"
                    }
                }
            ],
            initComplete: function(settings, json){
                //$('#boxSupplierListTable').find('.overlay').remove()
            }
        });

        $('#supplier-list-table_filter').remove();

        $('#close-form').click(function(e){
            e.preventDefault();
            $('#detail-supplier').hide('slow');
        });

        $('#supplier-list-table tbody').on('click', '.btn-detail', (function(e){
            e.preventDefault();
            //$('#detail-supplier').find('.box').append(loadingStates);
            var supplierId = supplier_table.row($(this).parents('tr')).data().id;
            var tempKaitan = `<div class="row"><div class="center-block text-center">No Data</div></div>`;
            $('#kaitan_project .box-body').html(tempKaitan);
            projectTable.destroy();
            var supplier_detail = $.ajax({
                url: "{{ url('/')}}/library/supplier/detail",
                dataType : "json",
                async: true,
                data : {
                    supplier : supplierId, "_token": "{{ csrf_token() }}"
                },
                type: "post"
            });
            supplier_detail.done(function(data) {
                $('#pic_owner_name').text(data.pic_owner_name);
                $('#pic_owner_telp').text(data.pic_owner_telp);
                $('#pic_sales_name').text(data.pic_sales_name);
                $('#pic_sales_telp').text(data.pic_sales_telp);
                $('#box-title-supplier').html(data.nama_supplier + ' - ' + data.jenis_barang);
                $('#historyFile').data('history', data.id_grup)
            });

            projectTable = $('#project-list-table').DataTable({
                'paging'       : true,
                'lengthChange' : false,
                'pageLength'   : 5,
                'searching'    : false,
                'ordering'     : false,
                'info'         : true,
                'autoWidth'    : true,
                'processing'   : true,
                'serverSide'   : true,
                'language'     : {
                    processing : '<i class="fa fa-refresh fa-spin"></i>',
                },
                'ajax'         :  {
                    url : "{{ url('/')}}/library/supplier/project",
                    type : "post",
                    data : { supplier : supplierId, "_token": "{{ csrf_token() }}" },
                    async : true,
                },
                'columns'      : [
                    { data : 'rekan_id', name : 'rekan_id', visible : false },
                    { data : 'proj_id', name : 'proj_id', visible : false },
                    { data : 'project_name', name : 'project_name' },
                    { data : 'alamat', name : 'alamat'},
                    { data : 'kaitan', name : 'kaitan',
                        render: function(data,type,row,meta){
                            var kaitan = `<a href='#kaitan_project' class='open-tab' data-kaitan='` + data + `' data-project='` + row.proj_id + `'> ` +
                                data + `</a>`;
                            return kaitan;
                        }
                    },
                    { data : 'updated_at', name : 'updated_at',
                        render: function(data,type,row,meta){
                            return moment(data.updated_at).format("DD-MM-YYYY h:mm:ss")
                        }
                    },
                ],
                'initComplete' : function(settings, json){
                    //$('#detail-supplier').find('.overlay').remove();
                }
            });
            $('#detail-supplier').show('slow');
            setTimeout(function() {
                navigationFn.goToSection('#detail-supplier');
                $('#detail_supplier_project_tab').tab("show");
            }, 200);

            if($('#detail-supplier').is(':visible')){
                $('#project-list-table tbody').on('click', '.open-tab', function(e){
                    var kaitan = $(this).data('kaitan');
                    var project = $(this).data('project');
                    $('#kaitan_project').find('.box').append(loadingStates);
                    $('#kaitan_project_tab').tab("show");
                    $('#kaitan_project .box-body').empty();
                    var data = { rekan : supplierId, proj : project, supplier : supplierId, "_token": "{{ csrf_token() }}" }
                    if(kaitan == "SPK"){
                        ajaxProjectSPKDetail(data, '#kaitan_project .box-body');
                    }
                    if(kaitan == "PO"){
                        ajaxProjectPODetail(data, '#kaitan_project .box-body');
                    }

                    if(kaitan == "MOU"){

                    }
                    $('#box-title-kaitan').html($('#box-title-supplier').html());
                });
            }

            $('#detail_supplier_project_tab').tab("show");
        }));

        $('#search_supplier_table').keyup(function(e) {
            if (e.key === "Enter") {
                supplier_table.search(this.value).draw();
            }
            supplier_table.search($(this).val()).draw();
        });

        var search_supplier_table = document.getElementById('search_supplier_table');
        search_supplier_table.addEventListener("keypress", function onEvent(event) {
            // if (event.key === "Enter") {
            //     supplier_table.search(this.value).draw();
            // }
        });

        $('#historyFile').click(function(e){
            e.preventDefault();
            var rekanan_group_id = $('#historyFile').data('history');
            swal.fire({
                allowEscapeKey : false,
                allowOutsideClick : false,
                width: "100rem",
                title: 'History Pricelist',
                confirmButtonText: 'Close',
                html:
                    `<div class="swal2-content-custom">
                        <h3 id="supp-name"></h3>
                        <div class="row"><div class="col-md-12"><button class="btn btn-primary addNewPriceList" data-rekan='` + rekanan_group_id + `'><span class="fa fa-plus"></span> Tambah Pricelist </button>
                        <div class="table-responsive">
                            <table class="table table-striped table-historyfile row-border stripe">
                                <thead>
                                    <tr>
                                        <th>Last Update</th>
                                        <th>Preview</th>
                                        <th>Keterangan</th>
                                        <th>Tanggal Berlaku</th>
                                        <th>Action</th>
                                    </tr>
                                </thead><tbody></tbody>
                            </table>
                        </div>
                    </div>`,

                onOpen: function(){
                    $('#supp-name').html($("#box-title-supplier").text());
                    ajaxPricelist(rekanan_group_id,'.table-historyfile tbody', 1);
                    setTimeout(() => {

                    }, 500);

                    $('.addNewPriceList').click(function(e){
                        e.preventDefault();
                        var supplier = $(this).data('rekan');
                        openPricelistForm(supplier)
                    })
                }
            })
        });

        //Lihat file pricelist
        $('#supplier-list-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = supplier_table.row(tr);
            var supplierId = supplier_table.row($(this).parents('tr')).data().id;

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                if(supplier_table.row('.shown').length) {
                    $('.details-control', supplier_table.row('.shown').node()).click();
                }
                row.child(format(row.data(),tr,row)).show();
                tr.addClass('shown');
                ajaxPricelist(supplierId,'.table_pricelist tbody', 0, tr, row);
            }

            $('.addNewPriceList').click(function(e){
                console.log('click')
                e.preventDefault();
                var supplier = $(this).data('rekan');
                openPricelistForm(supplier)
            })
        });

        function format (d,tr,row) {
            var supplierId = d.id_grup;
            var temp_table = `
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary addNewPriceList" data-rekan='`+ supplierId +`'><span class="fa fa-plus"></span> Tambah Pricelist </button>
                </div>
                <div class="box table-responsive">
                    <table id="table_pricelist" style="padding-left:50px;" class="table table-dark table-striped table_pricelist">
                        <thead class="">
                            <tr>
                                <th>Last Update</th>
                                <th>Preview</th>
                                <th>Tanggal Berlaku</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>`;
            return temp_table;
        }

        $('#search-btn').click(function(e) {
            e.preventDefault();
            var txtVal = $('#search_supplier_table').val()
            supplier_table.search(txtVal).draw();
        });

    });

    /** ! on ready **/

    var loadingStates = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
    var isCancel = 1;

    var table_pricelist =  $('.table_pricelist').DataTable({
        'paging'       : true,
        'lengthChange' : false,
        'pageLength'   : 5,
        'searching'    : false,
        'ordering'     : true,
        'info'         : true,
        'autoWidth'    : false,
        columns : [
            { data:'last_update', name:'last_update', width:'16rem'},
            { data: 'preview', name:'preview' },
            { data:'keterangan', name:'keterangan', width:'22rem'},
            { data:'tanggal_berlaku', name:'tanggal_berlaku', width: '18rem' },
            { data:'', name:'action', width:'17rem' },
        ],
    });

    function ajaxPricelist(rekanan_group_id, selector, history = 0, row = null, tr = null){
        $(selector).empty();
        //$(selector).parent('table').parent('div').prepend(loadingStates);
        var temp_row ="";
        $.ajax({
            url: "{{ url('/')}}/library/supplier/pricelist",
            dataType : "json",
            data : {
                supplier : rekanan_group_id, history : history
            },
            type: "post",
            success : function(data){
                if(data.length > 0){
                    $.each(data, function(i,v){
                        var ext = getExtensionFile(v.price_file);
                        var pricefile = "";
                        if(ext){
                            if(ext == "mp4" || ext == "avi" || ext == "3gp"){
                                pricefile = "<td>" +
                                    "<video width='240' height='160' controls>" +
                                    "<source src='{{ url('/')}}/assets/rekanan/" + v.rekanan_group_id + "/" + v.price_file + "' type='video/" + ext + "'>Your browser does not support the video tag.</video></td>";
                            }else{
                                pricefile = "<td><a href='{{ url('/')}}/assets/rekanan/" + v.rekanan_group_id + "/" + v.price_file + "'>" + v.price_file + "</a></td>";
                            }
                        }
                        var dari = String(moment(v.berlaku_dari_tanggal).format("DD-MM-YYYY"));
                        var sampai = String(moment(v.berlaku_sampai_tanggal).format("DD-MM-YYYY"));
                        var tanggal_berlaku = dari + " s/d " + sampai;
                        if(history){
                            temp_row += 
                                `<tr>
                                    <td> ` + moment(v.updated_at).format("DD-MM-YYYY h:mm:ss")  + `</td>` +
                                    pricefile +
                                    `<td  class="cell-keterangan">
                                        <p>` + v.keterangan  + `</p>
                                        <input class="form-control" type="text" name="keterangan" value="` + v.keterangan + `" style="width:100%;display:none;">
                                        <input type="hidden" type="text" name="pricelist" value="` + v.id + `">
                                    </td>
                                    <td> ` + tanggal_berlaku  + `</td>
                                    <td>
                                        <center>
                                        <a class="btn btn-info btn-circle" href="{{ url("/")}}/library/supplier/pricelist/download/` + v.id + `"><span class="fa fa-download"></span></a>
                                        </center>
                                    </td>
                                </tr>`;
                        }else{
                            temp_row += 
                                `<tr>
                                    <td> ` + moment(v.updated_at).format("DD-MM-YYYY h:mm:ss")  + `</td>` +
                                    pricefile +
                                    `<td class="cell-tanggalberlaku">
                                        <p>` + tanggal_berlaku  + `</p> 
                                        <input class="form-control tanggalberlaku" type="text" name="tanggalBerlaku" value="` + moment(v.berlaku_dari_tanggal).format("MM/DD/YYYY") + ` - ` + moment(v.berlaku_sampai_tanggal).format("MM/DD/YYYY") + `" style="width:100%;display:none;">
                                        <input type="hidden" type="text" name="pricelist" value="` + v.id + `">
                                    </td>
                                    <td class="cell-keterangan">
                                        <p>` + v.keterangan  + `</p>
                                        <input class="form-control" type="text" name="keterangan" value="` + v.keterangan + `" style="width:100%;display:none;">
                                        <input type="hidden" type="text" name="pricelist" value="` + v.id + `">
                                    </td>
                                    <td>
                                        <center>
                                            <a class="btn btn-info btn-circle" href="{{ url("/")}}/library/supplier/pricelist/download/` + v.id + `"><span class="fa fa-download"></span></a>
                                            <button class="btn btn-danger btn-circle deletePriceList" data-rekan='` + v.rekanan_group_id + `' data-pricelist='` + v.id + `' id="deletePriceList">
                                            <span class="fa fa-trash"></span>
                                            </button>
                                        </center>
                                    </td>
                                </tr>`;
                        }
                    });
                    $(selector).html(temp_row);
                    //$(selector).parent('table').parent('div').find('.overlay').remove();
                }else{
                    openPricelistForm(rekanan_group_id,row,tr);
                }

                $('.table-historyfile').DataTable({
                    'paging'       : true,
                    'lengthChange' : false,
                    'pageLength'   : 5,
                    'searching'    : false,
                    'ordering'     : true,
                    'info'         : true,
                    'autoWidth'    : true,
                    "order": [[ 0, "desc" ]]
                });

                $('.deletePriceList').click(function(e){
                    var pricelist = $(this).data('pricelist');
                    var rekan = $(this).data('rekan');
                    deletePricelist(pricelist,rekan)
                })
            },
            error : function(data){
                console.log(data)
                Swal.fire({
                    icon : 'error',
                    title: 'Ooopss ...',
                    text: 'Gagal mengambil data'
                })
            }
        });
        $('.table_pricelist tbody').on('dblclick', '.cell-keterangan', function(e){
            var cell = $(this);
            var p = $(cell).children('p');
            var input = $(cell).children('input[name="keterangan"]');
            $(p).hide();
            $(input).show()
            $(input).focus();
        });

        $('.table_pricelist tbody').on('dblclick', '.cell-tanggalberlaku', function(e){
            var cell = $(this);
            var p = $(cell).children('p');
            var input = $(cell).children('input[name="tanggalBerlaku"]');
            var pricelist = $(cell).children('input[name="pricelist"]');
            $(p).hide();
            $('.tanggalberlaku').daterangepicker({
                autoApply : true,
                open:"left",
                locale: {
                    format: 'MM/DD/YYYY'
                }
            }, function(start,end,label){
                var _s = start.format('DD-MM-YYYY')
                var _e = end.format('DD-MM-YYYY')
                $(input).val(_s + ' s/d ' + _e)
                $(p).html(_s + ' s/d ' + _e);
                var tgl_ = start.format("MM/DD/YYYY") + ' - ' + end.format("MM/DD/YYYY");
                var data = { 
                    "tanggalBerlaku" : tgl_, 
                    "pricelist" : $(pricelist).val()
                }
                ajaxPricelistModify(data, input);
                $(input).hide();
                $(p).show();
            })

            $(input).show()
            $(input).focus();
        });

        $('.table_pricelist tbody').on("blur", 'input[name="keterangan"]', function(e){
            var input = $(this);
            var pricelist = $(this).next('input[name="pricelist"]');
            var data = { "keterangan" : $(input).val(), "pricelist" : $(pricelist).val() }
            var p = $(this).prev('p');
            ajaxPricelistModify(data, input);
            $(this).hide();
            $(p).html($(input).val())
            $(p).show();
        });

        $('.table_pricelist tbody').on("keyup", 'input[name="keterangan"]', function(e){
            if(e.keyCode == 13){
                var input = $(this);
                var pricelist = $(this).next('input[name="pricelist"]');
                var data = { "keterangan" : $(input).val(), "pricelist" : $(pricelist).val() }
                var p = $(this).prev('p');
                ajaxPricelistModify(data, input);
                $(this).hide();
                $(p).html($(input).val())
                $(p).show();
            }
        });

        $('.table_pricelist tbody').on("blur", 'input[name="tanggalBerlaku"]', function(e){
            var input = $(this);
            var pricelist = $(this).next('input[name="pricelist"]');
            var data = { "tanggalBerlaku" : $(input).val(), "pricelist" : $(pricelist).val() }
            var p = $(this).prev('p');
            if(!$(this).is(':focus') && !$('.show-calendar').is(':visible')){
                $(this).hide();
                $(p).html($(input).val())
                $(p).show();
            }
        });
    }

    function ajaxPricelistModify(data, input){
        $.ajax({
            url : "{{ url('/')}}/library/supplier/pricelist/modify",
            type : "post",
            data : data,
            success : function(data){
                if(data.status == "success"){
                    toastSuccess()
                }else{
                    toastFail()
                }
            },
            error : function(data){
                console.log(data)
                toastFail()
            }
        });
    }

    function ajaxProjectPODetail(data_, selector, bool = false){
        $.ajax({
            url : "{{ url('/')}}/library/supplier/project/po",
            type : "post",
            data : data_,
            success : function(data){
                var tempDiv = `
                <div class="col-md-12">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td style='width:100px'><label>Proyek</label></td>          
                                <td style='width:20px'><label>:</label></td>          
                                <td><label>` + data.project_name + `</label></td>
                            </tr>
                            <tr>
                                <td><label>Lokasi</label></td>          
                                <td><label>:</label></td>          
                                <td><label>` + data.alamat + `</label></td>
                            </tr>
                            <tr>
                                <td><label>Last Update</label></td>
                                <td><label>:</label></td>
                                <td><label>` + data.updated_at.replace(/\+/g, '') + `</label></td>
                            </tr>
                        </tbody>
                    </table>
                </div>`;

                var tempTable = `
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-po-detail row-border stripe">
                            <thead>
                                <tr>
                                    <th>No. PO</th>          
                                    <th>Nama Barang</th>          
                                    <th>Harga Barang</th>
                                    <th>Last Update</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>`;

                $(selector).html(tempDiv + tempTable);

                $('.table-po-detail').DataTable({
                    'paging'       : true,
                    'lengthChange' : false,
                    'pageLength'   : 5,
                    'searching'    : false,
                    'ordering'     : true,
                    'info'         : true,
                    'autoWidth'    : false,
                    'language'     : {
                        processing : '<i class="fa fa-refresh fa-spin"></i>',
                    },
                    'ajax'         : {
                        url : "{{ url('/')}}/library/supplier/project/po/detail",
                        type : "post",
                        data : data_,
                        dataSrc : 'data',
                    },
                    'columns'      : [
                        { data:'nomor_po', name:'nomor_po'},
                        { data: 'nama_barang', name:'nama_barang' },
                        { data:'harga_satuan', name:'harga_satuan',
                            render: function(data,type,row,meta){
                                var harga = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                                return harga;
                            }
                        },
                        { data:'updated_at', name:'updated_at',
                            render: function(data,type,row,meta){
                                var last_update = data.replace(/\+/g, '');
                                return last_update;
                            }
                        },
                        { data:null, name:'action',
                            render: function(data,type,row,meta){
                                var link = `<a href="{{ url('/')}}/purchaseorder/cetakpdf/`+ row.po_id + `" class="btn btn-danger"><span class="fa fa-download"></span> Download</a>`;
                                return link;
                            }
                        },
                    ],
                    'initComplete' : function(){
                        $(selector).parent().find('.overlay').remove();
                    }
                })
            },
            error : function(data){
                console.log(data)
                Swal.fire({
                    icon: 'error',
                    title: 'Ooopss ...',
                    text: 'Gagal mengambil data',
                    showConfirmButton: true
                })
                $('#detail_supplier_project_tab').tab("show");
            }
        });
    }

    function ajaxProjectSPKDetail(data_, selector, bool = false){
        $.ajax({
            url : "{{ url('/')}}/library/supplier/project/spk",
            type : "post",
            data : data_,
            success : function(data){
                var tempDiv = `
                <div class="col-md-12">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td style='width:100px'><label>Proyek</label></td>          
                                <td style='width:20px'><label>:</label></td>          
                                <td><label>` + data.project_name + `</label></td>
                            </tr>
                            <tr>
                                <td><label>Lokasi</label></td>          
                                <td><label>:</label></td>          
                                <td><label>` + data.alamat + `</label></td>
                            </tr>
                            <tr>
                                <td><label>Last Update</label></td>
                                <td><label>:</label></td>
                                <td><label>` + data.updated_at.replace(/\+/g, '') + `</label></td>
                            </tr>
                        </tbody>
                    </table>
                </div>`;

                var tempTable = `
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-spk-detail row-border stripe">
                            <thead>
                                <tr>
                                    <th>No. SPK</th>          
                                    <th>Item Pekerjaan</th>          
                                    <th>Nilai</th>
                                    <th>Last Update</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>`;
                $(selector).html(tempDiv + tempTable);

                $('.table-spk-detail').DataTable({
                    'paging'       : true,
                    'lengthChange' : false,
                    'pageLength'   : 5,
                    'searching'    : false,
                    'ordering'     : true,
                    'info'         : true,
                    'autoWidth'    : false,
                    'language'     : {
                        processing : '<i class="fa fa-refresh fa-spin"></i>',
                    },
                    'ajax'         : {
                        url : "{{ url('/')}}/library/supplier/project/spk/detail",
                        type : "post",
                        data : data_,
                        dataSrc : 'data',
                    },
                    'columns'      : [
                        { data:'spk_no', name:'nomor_spk'},
                        { data:'ipk_name', name:'nama_barang' },
                        { data:'nilai', name:'nilai',
                            render: function(data,type,row,meta){
                                var nilai = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                                return nilai;
                            }
                        },
                        { data:'updated_at', name:'updated_at',
                            render: function(data,type,row,meta){
                                var last_update = data.replace(/\+/g, '');
                                return last_update;
                            }
                        },
                        { data:null, name:'action',
                            render: function(data,type,row,meta){
                                var link = `<a href="{{ url('/') }}/spk/cetakSpk?spk_id=`+ row.spk_id + `" class="btn btn-danger"><span class="fa fa-download"></span> Download</a>`;

                                return link;
                            }
                        },
                    ],
                    'initComplete' : function(){
                        $(selector).parent().find('.overlay').remove();
                    }
                })
            },
            error : function(data){
                console.log(data)
                Swal.fire({
                    icon: 'error',
                    title: 'Ooopss ...',
                    text: 'Gagal mengambil data',
                    showConfirmButton: true
                })
                $('#detail_supplier_project_tab').tab("show");
            }
        });
    }

    function toastSuccess(){
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Data di simpan',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
        })
    }

    function toastFail(){
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Data di simpan',
            showConfirmButton: false,
            timer: 500,
            timerProgressBar: true,
        })
    }

    function openPricelistForm(d,row = null,tr = null){
        Swal.fire({
            allowEscapeKey : false,
            allowOutsideClick : false,
            width: "70rem",
            title: 'Tambah Pricelist',
            confirmButtonText: 'Simpan',
            html:
                '<div class="swal2-content-custom"><form action="" method="post" enctype="multipart/form-data" id="pricelist-form" name="pricelist-form">' +
                '<label>Tanggal berlaku</label><input type="text" class="form-control float-right swal2-input" name="tanggalBerlaku" id="tanggalBerlaku">' +
                '<label>Pilih File</label><input class="swal2-file" name="pricefile" id="pricefile" type="file" accept="application/msword, application/vnd.ms-excel,' + 'application/vnd.ms-powerpoint,text/plain, application/pdf, image/*,video/x-flv,video/mp4,application/x-mpegURL,video/x-msvideo">' +
                '<label>Keterangan</label><input type="text" name="keterangan" id="keterangan" class="swal2-input" placeholder="Keterangan">' +
                '</form></div>',
            focusConfirm: true,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            cancelButtonColor: '#c82333',
            onOpen: function() {
                $('#tanggalBerlaku').daterangepicker({
                    // timePicker: true,
                    // timePickerIncrement: 30,
                    open:"center",
                    locale: {
                        // format: 'MM/DD/YYYY hh:mm A'
                        format: 'MM/DD/YYYY'
                    }
                });
            },
            preConfirm: () => {
                var pricelistFormData = new FormData();
                var priceFile = $('#pricefile')[0].files[0];
                var keterangan = $('#keterangan').val();
                var tanggalBerlaku = $('#tanggalBerlaku').val();
                pricelistFormData.append('keterangan', keterangan);
                pricelistFormData.append('tanggal_berlaku', tanggalBerlaku);
                pricelistFormData.append('pricefile', priceFile);
                pricelistFormData.append('rekanan_group_id', d);
                return new Promise(function(resolve,reject){
                    $.ajax({
                        url: "{{ url('/')}}/library/supplier/pricelist/store",
                        method: "post",
                        data : pricelistFormData,
                        processData: false,
                        contentType: false,
                        success : function(result, textStatus, xhr){
                            if(result){
                                resolve(result)
                                return true
                            }else{
                                return false;
                            }
                        },
                        error : function(result, textStatus, xhr){
                            console.log(result)
                            Swal.fire({
                                title :'Gagal',
                                text : 'Terjadi kesalahan',
                                icon : 'error'
                            });
                        }
                    });
                })
            },
            allowOutsideClick: () => !Swal.isLoading(),

        }).then((result) => {
            if(result.dismiss === 'cancel'){
                Swal.fire({
                    title :'Cancelled',
                    text : 'Tidak jadi menambahkan pricelist',
                    icon : 'warning'
                });
                if(row != null && tr != null){
                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data(),tr,row)).show();
                        tr.addClass('shown');
                    }
                }
            }else{
                isCancel = 0;
                if (result.value.status === "success") {
                    Swal.fire({
                        icon : 'success',
                        title: 'Berhasil',
                        text: 'Data tersimpan'
                    });
                }else{
                    Swal.fire({
                        icon : 'warning',
                        title: 'Gagal',
                        text: result.value.msg
                    })
                }
            }
            ajaxPricelist(d, '.table_pricelist tbody')
        }).catch((error) => {
            isCancel = 0;
            if (error) {
                Swal.fire({
                    icon : 'error',
                    title: 'Error ...',
                    text: 'Data tidak tersimpan'
                })
            }
        });
    }

    function deletePricelist(pricelist, d){
        Swal.fire({
            title: 'Anda yakin?',
            text: "Pricelist akan dihapus",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{ url('/')}}/library/supplier/pricelist/delete",
                    method: "post",
                    data : { pricelist: pricelist},
                    success : function(result){
                        Swal.fire(
                            'Berhasil!',
                            'Data telah di hapus.',
                            'success'
                        )
                    },
                    error : function(result){
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan',
                            'error'
                        )
                    }
                }).done((result) => {
                    
                });
            }
            ajaxPricelist(d, '.table_pricelist tbody')
        })
    }

    function getExtensionFile(filename){
        return filename.replace(/^.*?\.([a-zA-Z0-9]+)$/, "$1");
    }
    function isVideoFile(filename){
        //var re = new RegExp("([a-zA-Z0-9\s_\\.\-\(\):])+(.avi|.mp4|.mkv|.3gp)$");
        //return re.test(filename);
        return false;
    }
</script>
