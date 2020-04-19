<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/custom-style.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
</style>

<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        var enddate = $('#enddate').val();
        $('#tglperpanjang').datepicker({
            dateFormat: 'dd/M/yy',
            minDate: new Date(enddate)
        })
        $('#tglpengajuan').datepicker({
            dateFormat: 'dd/M/yy',
            minDate: new Date(enddate)
        })

        var pricelist = $('.table-pricelist').DataTable({
            'paging'       	: true,
            'lengthChange' 	: false,
            'pageLength'   	: 5,
            'searching'    	: true,
            'ordering'     	: false,
            'info'         	: true,
            'autoWidth'    	: false,
            'serverside'	: true,
            'processing'	: true,
            'language'		: {
                processing	: ''
            },
            'ajax'			: {
                url: "{{ url('/')}}/library/supplier/pricelist/datatable",
                data: { supplier : $('.addNewPriceList').data('rekan') }
            },
            'columns' : [
                { data:'last_update', name:'last_update', width:'16rem',
                    render: function(data,type,row,meta){
                        var updatedAt = moment(row.updated_at).format("DD-MM-YYYY h:mm:ss")
                        return updatedAt;
                    }
                },
                { data: 'preview', name:'preview',
                    render: function(data,type,row,meta){
                        var ext = getExtensionFile(row.price_file);
                        var pricefile = "";
                        if(ext){
                            if(ext == "mp4" || ext == "avi" || ext == "3gp"){
                                pricefile = "<td>" +
                                    "<video width='240' height='160' controls>" +
                                    "<source src='{{ url('/')}}/assets/rekanan/" + row.rekanan_group_id + "/" + row.price_file + "' type='video/" + ext + "'>Your browser does not support the video tag.</video></td>";
                            }else{
                                pricefile = "<a href='{{ url('/')}}/assets/rekanan/" + row.rekanan_group_id + "/" + row.price_file + "'>" + row.price_file + "</a>";
                            }
                        }

                        return pricefile;
                    }
                },
                { data:'keterangan', name:'keterangan', width:'22rem'},
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
                        var act = `<center>
                                            <a class="btn btn-info btn-circle" href="{{ url("/")}}/library/supplier/pricelist/download/` + row.id + `"><span class="fa fa-download"></span></a>
                                            <button class="btn btn-danger btn-circle deletePriceList" data-rekan='` + row.rekanan_group_id + `' data-pricelist='` + row.id + `' id="deletePriceList">
                                            <span class="fa fa-trash"></span>
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


        $(".select2").select2();
    })

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
                `<div class="swal2-content-custom">
					<form action="" method="post" enctype="multipart/form-data" id="pricelist-form" name="pricelist-form">
						<label>Tanggal berlaku</label>
							<input type="text" class="form-control float-right swal2-input" name="tanggalBerlaku" id="tanggalBerlaku">
						<label>Barang</label>
							<select name='namaBarang' multiple id='namaBarang' class='form-control swal2-select'></select>
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
                $('#namaBarang').select2({
                    placeholder : 'Cari barang ...',
                    minimumResultsForSearch: 10,
                    width: '100%',
                    ajax: {
                        dataType: 'json',
                        url: "{{ url('/')}}/library/mou/select2/item",
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
                    open:"center",
                    locale: {
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