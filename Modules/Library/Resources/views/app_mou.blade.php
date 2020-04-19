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

</style>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

    var MOU = {
        ajaxUrl : {
            mouDetailsDataTable : "{{ url('/')}}/library/mou/datatable",
            mouStore            : "{{ url('/')}}/library/mou/store",
            mouModify           : "{{ url('/')}}/library/mou/modify",
            mouDelete           : "{{ url('/')}}/library/mou/delete",
            select2Project      : "{{ url('/')}}/library/mou/select2/project",
            select2Supplier     : "{{ url('/')}}/library/mou/select2/supplier",
            select2Item         : "{{ url('/')}}/library/mou/select2/item"
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
            success : (title_, text_) => Swal.fire({
                icon: 'success',
                title: title_,
                text : text_
            }),
            error   : (title_, text_) => Swal.fire({
                icon: 'error',
                title: title_,
                text : text_
            })
        }, 
    }
    $(document).ready(function(){
        
        //var mou = MOU.ajax(MOU.ajaxUrl.mouDetailsDataTable)
        //$('#mou-list-table').parents().find('div.box').append(loadingStates);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var mou_table = $('#mou-list-table').DataTable({
            'paging'       : true,
            'lengthChange' : false,
            'pageLength'   : 5,
            'searching'    : true,
            'ordering'     : true,
            'info'         : true,
            'autoWidth'    : false,
            'processing'   : true,
            'serverSide'   : true,
            'language'     : {
                processing : '<i class="fa fa-refresh fa-spin"></i>'
            },
            'ajax'         : {
                url : MOU.ajaxUrl.mouDetailsDataTable,
                cache : false,
            },
            'columns'      : [
                // { data : 'id', name : 'id', visible : false },
                // { data : 'rekan_id', name : 'rekan_id', visible : false },
                // { data : 'project_id', name : 'project_id', visible : false },
                // {
                //     "className"     : 'details-control',
                //     "orderable"     : false,
                //     "data"          : null,
                //     "defaultContent": '',
                //     "width"         : "20px",
                //     visible         : false
                // },
                { data : 'proj_name', name : 'proj_name', width : '20rem' },
                { data : 'nomor_mou', name : 'nomor_mou', width : '11rem' },
                { data : 'rekan_name', name : 'rekan_name', width: '15rem'},
                { data : 'item_name', name : 'item_name', width: '11rem'},
                { data : 'jenis_mou', name : 'jenis_mou', width: '15rem'},
                { data : 'file_mou', name : 'file_mou',
                    render: function(data,type,row,meta) {
                        return `<center><a class='btn btn-danger' download href='{{ url('/')}}/library/mou/download/` + row.id + `'><span class='fa fa-download'></span></a></center>`;
                    }
                },

                // { data : null, name : 'detail', 
                //     render: function(data,type,row,meta) {
                //         return "<center><button class='btn-detail btn btn-success'> Detail </button></center>"
                //     }
                // }
            ],
            initComplete : function(settings, json) {
                //$('#mou-list-table_wrapper').parents().find('.overlay').remove()
            }
        });

        $('#mou-list-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
    
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });

        function format (d) {
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                '<tr>'+
                    '<td>Full name:</td>'+
                    '<td>'+d.name+'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td>Extension number:</td>'+
                    '<td>'+d.extn+'</td>'+
                '</tr>'+
                '<tr>'+
                    '<td>Extra info:</td>'+
                    '<td>And any further details here (images etc)...</td>'+
                '</tr>'+
            '</table>';
        }

        $('#mou-list-table_filter').remove();
        $('#search_mou_table').on('change clear', function(e) {
            mou_table.search($(this).val()).draw();
        });
        $('#search_mou_table').keyup(function(e) {
            if(e.keyCode === 13){
                mou_table.search($(this).val()).draw();
            }
        });

        $('#addNewMOU').click(function(e){
            e.preventDefault();
            const formAdd = Swal.fire({
                allowEscapeKey : false,
                allowOutsideClick : false,
                width: "70rem",
                title: 'Tambah MOU',
                confirmButtonText: 'Simpan',
                html:
                `<div class="swal2-content-custom">
                    <form action="" method="post" enctype="multipart/form-data" id="mou-form" name="mou-form">
                        <label>Tanggal Berlaku</label>
                        <input type='text' class='form-control float-right swal2-input' name='tanggalBerlaku' id='tanggalBerlaku' />
                        <label>Jenis MOU</label>
                        <select class="form-control float-right swal2-select" name="jenisMOU" id="jenisMOU">
                            <option value="MOU Pengadaan Barang">MOU Pengadaan Barang</option>
                            <option value="MOU Pekerjaan">MOU Pekerjaan</option>
                        </select>
                        <label>Nomor MOU</label>
                        <input type="text" class="form-control float-right swal2-input" name="nomorMOU" id="nomorMOU">
                        <label>Nama Proyek</label>
                        <select class="form-control"  required name="namaProyek" id="namaProyek"></select><br>
                        <label>Nama Supplier</label>
                        <select class="form-control" required name="namaSupplier" id="namaSupplier"></select><br>
                        <label>Item</label>
                        <select class="form-control" required name="namaBarang[]" multiple="multiple" id="namaBarang"></select><br>
                        <label>Pilih File</label>
                        <input class="swal2-file" multiple name="filemou[]" required id="filemou" type="file" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf, image/*">
                    </form>
                </div>`,
                focusConfirm: true,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                cancelButtonColor: '#c82333',
                onOpen: function() {
                    setTimeout(() => {
                        $('#namaProyek').select2({
                            placeholder : 'Cari proyek ...',
                            minimumResultsForSearch: 10,
                            width: '100%',
                            ajax: {
                                dataType: 'json',
                                url: MOU.ajaxUrl.select2Project,
                                type:"post",
                                delay:250,
                                data: function(params) {
                                    return {
                                        search: params.term
                                    }
                                },
                                processResults: function (data, page){
                                    return {
                                        results: data
                                    };
                                },
                                cache : true
                            }
                        });
                        $('#namaBarang').select2({
                            placeholder : 'Cari barang ...',
                            minimumResultsForSearch: 10,
                            width: '100%',
                            ajax: {
                                dataType: 'json',
                                url: MOU.ajaxUrl.select2Item,
                                delay: 250,
                                type:"post",
                                data: function(params) {
                                    return {
                                        search: params.term
                                    }
                                },
                                processResults: function (data, page){
                                    return {
                                        results: data
                                    };
                                },
                                cache : true
                            }
                        });
                        $('#namaSupplier').select2({
                            placeholder : 'Cari supplier ...',
                            minimumResultsForSearch: 10,
                            width: '100%',
                            ajax: {
                                dataType: 'json',
                                url: MOU.ajaxUrl.select2Supplier,
                                delay: 250,
                                type:"post",
                                data: function(params) {
                                    return {
                                        search: params.term
                                    }
                                },
                                processResults: function (data, page){
                                    return {
                                        results: data
                                    };
                                },
                                cache : true
                            }
                        });
                        $('#tanggalBerlaku').daterangepicker({
                            // timePicker: true,
                            // timePickerIncrement: 30,
                            open:"center",
                            locale: {
                                // format: 'MM/DD/YYYY hh:mm A'
                                format: 'MM/DD/YYYY'
                            }
                        });
                    }, 1000);
                },
                preConfirm: () => {
                    var mouFormData = new FormData();
                    //var mouFile = $('#filemou').files.length;
                    
                    //var mouFile = $('#filemou')[0].files[0];
                    var listItem = $('#namaBarang').select2('data');
                    var item = [];
                    if(listItem.length > 0){
                        $.each(listItem, function(i,v){
                            item.push(v.id)
                        })
                    }
                    var proyek = ($('#namaProyek').select2('data').length > 0) ? $('#namaProyek').select2('data')[0].id : 0;
                    var rekan = ($('#namaSupplier').select2('data').length > 0) ? $('#namaSupplier').select2('data')[0].id : 0;
                    
                    var jenisMOU = $('#jenisMOU').val();
                    var nomorMOU = $('#nomorMOU').val();
                    var tanggalBerlaku = $('#tanggalBerlaku').val();
                    var mouFile = $('#filemou')[0].files;
                    mouFormData.append('proyek', proyek);
                    mouFormData.append('rekan', rekan);
                    mouFormData.append('item', JSON.stringify(item));
                    mouFormData.append('jenisMOU', jenisMOU);
                    mouFormData.append('nomorMOU', nomorMOU);
                    console.log(mouFile)
                    if(mouFile.length > 0){
                        $.each(mouFile, function(i,v){
                            mouFormData.append('mouFile[]', $('#filemou')[0].files[i]);
                        })
                    }
                    
                    mouFormData.append('tanggalBerlaku', tanggalBerlaku);
                    //return true;
                    return new Promise(function(resolve,reject){
                        $.ajax({
                            url: MOU.ajaxUrl.mouStore,
                            method: "post",
                            data : mouFormData,
                            processData: false,
                            contentType: false,
                            success: (result) => {
                                console.log('ajaxCallback')
                                console.log(result)
                                if(result){
                                    resolve(result)
                                    return true
                                }else{
                                    return false;
                                }
                            },
                            error : (result) => {
                                console.log(result)
                                MOU.alert.error("Oooppss...", "Terjadi Kesalahan saat mengambil data")
                            }
                        });
                    })
                },
                allowOutsideClick: () => !Swal.isLoading(),

            }).then((result) => {
                if(result.dismiss === 'cancel'){
                    Swal.fire({
                        title :'Cancelled',
                        text : 'Tidak jadi menambahkan mou',
                        icon : 'warning'
                    });
                }else{
                    isCancel = 0;
                    if (result.value.status == "success") {
                        Swal.fire({
                            icon : 'success',
                            title: 'Berhasil',
                            text: result.value.msg
                        });
                        mou_table.ajax.reload();
                    }else{
                        Swal.fire({
                            icon : 'error',
                            title: 'Gagal',
                            text: result.value.msg
                        })
                    }
                }
            }).catch((error) => {
                if (error) {
                    Swal.fire({
                        icon : 'error',
                        title: 'Error ...',
                        text: 'Data tidak tersimpan'
                    })
                }
            });
        })
    });
</script>