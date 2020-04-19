<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/custom-style.css">

<script src="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.all.min.js" type="text/javascript"></script>
<script src="{{ url('/')}}/assets/bower_components/moment/moment.js"></script>
<script src="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>

<style>
	.select2-container {
        z-index: 99999999999999;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        margin-top: -0.7rem;
	}

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
		background-color: #544c4c;
	}

    span.select2-selection.has-error {
        border: 1px solid #f27474 !important;
    }
	
	
</style>

<script type="text/javascript">

    $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Pricelist
        var pricelist = $('.table-pricelist').DataTable({
            'paging'       	: true,
            'lengthChange' 	: false,
            'pageLength'   	: 5,
            'searching'    	: true,
            'ordering'     	: true,
            'info'         	: true,
            'autoWidth'    	: false,
            'serverside'	: true,
            'processing'	: true,
            'language'		: {
                processing	: '<i class="fa fa-refresh fa-spin"></i>'
            },
            'ajax'			: {
                url: "{{ url('/')}}/rekanan/user/pricelist/datatable",
                data: { rekanan : $('.addNewPriceList').data('rekan') },
            },
            'columns' : [
                { data:'last_update', name:'last_update', width:'16rem',
                    render: function(data,type,row,meta){
                        var updatedAt = moment(row.updated_at.date).format("DD-MM-YYYY h:mm:ss")
                        return updatedAt;
                    }
                },
                { data: 'preview', name:'preview',
                    render: function(data,type,row,meta){
                        var ext = getExtensionFile(row.price_file);
                        var pricefile = "";
                        if(ext){
                            if(ext == "mp4" || ext == "avi" || ext == "3gp"){
                                pricefile = 
                                `<video width='240' height='160' controls>
                                    <source src='{{ url('/')}}/assets/rekanan/` + row.rekanan_group_id + `/` + row.price_file + `' type='video/` + ext + `'>Your browser does not support the video tag.</video>`;
                            }else{
                                pricefile = `<a href='{{ url('/')}}/assets/rekanan/` + row.rekanan_group_id + `/` + row.price_file + `'>` + row.price_file + `</a>`;
                            }
                        }

                        return pricefile;
                    }
                },
                { data:'item_category', name:'item_category', width:'18rem'},
                { data:'item', name:'item', width:'18rem'},
                { data:'tanggal_berlaku', name:'tanggal_berlaku', width: '18rem',
                    render: function(data,type,row,meta){
                        var dari = String(moment(row.berlaku_dari_tanggal).format("DD-MM-YYYY"));
                        var sampai = String(moment(row.berlaku_sampai_tanggal).format("DD-MM-YYYY"));
                        var tanggal_berlaku = dari + " s/d " + sampai;

                        return tanggal_berlaku;
                    }
                },
                { data:'', name:'action', width:'17rem',
                    render: function(data,type,row,meta){
                        var act = 
                            `<center>
                                <a class="btn btn-info btn-circle" href="{{ url("/")}}/library/supplier/pricelist/download/` + row.id + `" data-toggle="tooltip" data-placement="top" title="Download">
                                    <span class="fa fa-download"></span>
                                </a>
                                <button class="btn btn-danger btn-circle deletePriceList" data-rekan='` + row.rekanan_group_id + `' data-pricelist='` + row.id + `' data-toggle="tooltip" data-placement="top" title="Delete">
                                    <span class="fa fa-trash" ></span>
                                </button>
							</center>`;
                        return act;
                    }
                },
            ],
            'initComplete': function(){
                $('.addNewPriceList').click(function(e){
                    e.preventDefault();
                    var supplier = $(this).data('rekan');
                    openPricelistForm(supplier)
                })
            }
        });

        function openPricelistForm(d,row = null,tr = null){
            Swal.fire({
                allowEscapeKey : false,
                allowOutsideClick : false,
                width: "70rem",
                title: 'Tambah Pricelist',
                confirmButtonText: 'Simpan',
                html:
                    `<div class="swal2-content-custom">
                        <form action="" method="post" enctype="multipart/form-data" id="pricelist-form" name="pricelist-form">
                            <label>Tanggal berlaku</label>
                                <input type="text" class="form-control float-right swal2-input" name="tanggalBerlaku" id="tanggalBerlaku">
                            <label>Kategori Barang</label>
                                <select name='kategoriBarang' id='kategoriBarang' class='form-control swal2-select'></select>
                                </br></br>
                            <label>Barang</label>
                                <select name='namaBarang' multiple id='namaBarang' class='form-control swal2-select'>
                                </select>
                                </br></br>
                            <label>Pilih File</label>
                                <input class="swal2-file" name="pricefile" id="pricefile" type="file" accept="application/msword, application/vnd.ms-excel,application/vnd.ms-powerpoint,text/plain, application/pdf, image/*,video/x-flv,video/mp4,application/x-mpegURL,video/x-msvideo">
                            <label>Keterangan</label>
                                <input type="text" name="keterangan" id="keterangan" class="swal2-input" placeholder="Keterangan">
                        </form>
                    </div>`,
                focusConfirm: true,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                cancelButtonColor: '#c82333',
                onOpen: function() {
                    $('#kategoriBarang').select2({
                        placeholder : 'Cari barang ...',
                        //minimumResultsForSearch: 10,
                        width: '100%',
                        ajax: {
                            dataType: 'json',
                            url: "{{ url('/')}}/rekanan/user/data-barang/select2/getCatItem",
                            delay: 250,
                            type:"get",
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
                    }).on('change', function(e){
                        $('#subKategoriBarang').val(null).trigger('change');
                        $('#namaBarang').val(null).trigger('change');
                    });
                    // $('#subKategoriBarang').select2({
                    //     placeholder : 'Cari barang ...',
                    //     minimumResultsForSearch: 10,
                    //     width: '100%',
                    //     ajax: {
                    //         dataType: 'json',
                    //         url: "{{ url('/')}}/rekanan/user/data-barang/select2/getSubCatItem",
                    //         delay: 250,
                    //         type:"get",
                    //         data: function(params) {
                    //             return {
                    //                 search: params.term,
                    //                 category: $('#kategoriBarang').select2('data')[0].id
                    //             }
                    //         },
                    //         processResults: function (data, page){
                    //             return {
                    //                 results: data
                    //             };
                    //         },
                    //         cache : true
                    //     }
                    // }).on('change', function(e){
                    //     $('#namaBarang').val(null).trigger('change');
                    // });
                    $('#namaBarang').select2({
                        placeholder : 'Cari barang ...',
                        //minimumResultsForSearch: 10,
                        width: '100%',
                        ajax: {
                            dataType: 'json',
                            url: "{{ url('/')}}/rekanan/user/data-barang/select2/getItem",
                            delay: 250,
                            type:"get",
                            data: function(params) {
                                return {
                                    search: params.term,
                                    category: $('#kategoriBarang').select2('data')[0].id,
                                    //subcategory : $('#subKategoriBarang').select2('data')[0].id
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
                        open:"center",
                        locale: {
                            format: 'MM/DD/YYYY'
                        }
                    });
                },
                preConfirm: (data) => {
                    var result_ = "";
                    var pricelistFormData = new FormData();
                    var priceFile = $('#pricefile')[0].files[0];
                    var keterangan = $('#keterangan').val();
                    var tanggalBerlaku = $('#tanggalBerlaku').val();
                    var catItem = $('#kategoriBarang').select2('data');
                    // var subCatItem = $('#subKategoriBarang').select2('data');
                    var listItem = $('#namaBarang').select2('data');
                    var items = [];
                        if(listItem.length > 0){
                            $.each(listItem, function(i,v){
                                items.push(v.id)
                            })
                        }
                    var valid = false;
                    if(priceFile != "undefined" && keterangan != "" && catItem.length != 0 && listItem.length != 0){
                        valid = true;
                    }else{
                        valid = false;
                    }
                    return new Promise(function(resolve, reject){
                        if(valid){
                            pricelistFormData.append('items', JSON.stringify(items));
                            pricelistFormData.append('keterangan', keterangan);
                            pricelistFormData.append('tanggal_berlaku', tanggalBerlaku);
                            pricelistFormData.append('pricefile', priceFile);
                            pricelistFormData.append('rekanan_group_id', d);
                            $.ajax({
                                url: "{{ url('/')}}/rekanan/user/pricelist/store",
                                method: "post",
                                data : pricelistFormData,
                                processData: false,
                                contentType: false,
                                success : function(result, textStatus, xhr){
                                    //console.log(result)
                                    if(result){
                                        resolve(result)
                                        return true;
                                    }else{
                                        return false;
                                    }
                                    //result_ = result;
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
                        }else{
                            if(priceFile == null){ $('#pricefile').addClass('swal2-inputerror')  } else { $('#pricefile').removeClass('swal2-inputerror') }
                            if(keterangan == ""){ $('#keterangan').addClass('swal2-inputerror')  } else { $('#keterangan').removeClass('swal2-inputerror') }
                            if(catItem.length == 0){ $('#kategoriBarang').next().find('.select2-selection').addClass('has-error')  } else { $('#kategoriBarang').next().find('.select2-selection').removeClass('has-error') }
                            // if(subCatItem.length == 0){ $('#subKategoriBarang').next().find('.select2-selection').addClass('has-error')  } else { $('#subKategoriBarang').next().find('.select2-selection').removeClass('has-error') }
                            if(listItem.length == 0){ $('#namaBarang').next().find('.select2-selection').addClass('has-error')  } else { $('#namaBarang').next().find('.select2-selection').removeClass('has-error') }
                            result_ = "data tidak boleh kosong";
                            resolve(valid)
                            Swal.showValidationMessage(`data tidak boleh kosong`);
                            return false;
                        }
                        
                    })                
                },
                //allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if(result.dismiss === 'cancel'){
                    Swal.fire({
                        title :'Cancelled',
                        text : 'Tidak jadi menambahkan pricelist',
                        icon : 'warning'
                    });
                }else{
                    console.log(result)
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
                pricelist.ajax.reload()
            })
            .catch((error) => {
                Swal.showValidationMessage(`${error}`)
            });
        }

        function ajaxPricelistModify(data, input){
            $.ajax({
                url : "{{ url('/')}}/rekanan/user/pricelist/modify",
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
                        url: "{{ url('/')}}/rekanan/user/pricelist/delete",
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
                pricelist.ajax.reload()
            })
        }

        //ListPO
        var list_po = $('.table-po').DataTable({
            'paging'       	: true,
            'lengthChange' 	: false,
            'pageLength'   	: 5,
            'searching'    	: true,
            'ordering'     	: true,
            'info'         	: true,
            'autoWidth'    	: false,
            'serverside'	: true,
            'processing'	: true,
            'language'		: {
                processing	: '<i class="fa fa-refresh fa-spin"></i>'
            },
            'ajax'			: {
                url: "{{ url('/')}}/rekanan/user/po/datatable",
                data: { rekanan : $('.addNewPriceList').data('rekan') },
            },
            'columns'       : [
                { data:'updated_at', name:'updated_at', width:'15rem',
                    render: function(data, type, row,meta){
                        var updatedAt = moment(row.updated_at.date).format("DD-MM-YYYY h:mm:ss")
                        return updatedAt;
                    }
                },
                { data:'kategori_barang', name:'kategori_barang', width:'15rem'},
                { data:'nama_barang', name:'nama_barang', width:'15rem'},
                { data:'proj_name', name:'proj_name', width:'15rem'},
                { data:'proj_alamat', name:'proj_alamat', width:'15rem'},
                { data:'nomor_po', name:'nomor_po', width:'15rem'}
            ]
        });

        //ListMOU
        var mou_table = $('.table-mou').DataTable({
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
                url : "{{ url('/')}}/rekanan/user/mou/datatable",
                data: { rekanan : $('.addNewPriceList').data('rekan') },
            },
            'columns'      : [
                { data : 'proj_name', name : 'proj_name', width : '20rem' },
                { data : 'nomor_mou', name : 'nomor_mou', width : '11rem' },
                { data : 'item_name', name : 'item_name', width: '11rem'},
                { data : 'jenis_mou', name : 'jenis_mou', width: '15rem'},
                { data : 'file_mou', name : 'file_mou',
                    render: function(data,type,row,meta) {
                        return `<center><a class='btn btn-danger' download href='{{ url('/')}}/library/mou/download/` + row.id + `'><span class='fa fa-download'></span></a></center>`;
                    }
                },
            ],
            initComplete : function(settings, json) {
                //$('#mou-list-table_wrapper').parents().find('.overlay').remove()
            }
        });
    })

    

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

    

    

    function getExtensionFile(filename){
        return filename.replace(/^.*?\.([a-zA-Z0-9]+)$/, "$1");
    }
    function isVideoFile(filename){
        //var re = new RegExp("([a-zA-Z0-9\s_\\.\-\(\):])+(.avi|.mp4|.mkv|.3gp)$");
        //return re.test(filename);
        return false;
    }
</script>