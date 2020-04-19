<link rel="stylesheet" href="{{ url('/')}}/assets/custom-style.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/yadcf-master/dist/jquery.dataTables.yadcf.css">
{{-- <link rel="stylesheet" href="{{ Module::asset('library:css/bootstrap.css') }}"> --}}
<script src="{{ url('/')}}/assets/bower_components/sweetalert2/sweetalert2.all.min.js" type="text/javascript"></script>
<script src="{{ url('/')}}/assets/bower_components/moment/moment.js"></script>
<script src="{{ url('/')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/bower_components/yadcf-master/dist/jquery.dataTables.yadcf.js"></script>
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
    ajaxGetList();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
});
    function ajaxGetList(){
        $.ajax({
            url: "{{ url('/') }}/library/harga-satuan/tes",
            type: "get",
            success:function(data){
                var tempRow = "";
                $.each(data, function(i,v){
                    // tempRow +=
                    //     `<tr data-row='` + v.id + `' >
                    //         <td>` + v.id + `</td>
                    //         <td>` + v.parent_id + `</td>
                    //         <td>` + v.code + `</td>
                    //         <td>` + v.name + `</td>
                    //         <td>
                    //             <input style='width:3rem' class='form-control input-sm' value='` + v.isDivider + `' data-id='` + v.id + `' data-coa='` +  v.code + `'> <button class='btn btn-primary saveData' data-id='` + v.id + `'>Simpan</button>
                    //         </td>
                    //     </tr>`;
                    tempRow +=
                        `<tr data-row='` + v.id + `' >
                            <td>` + v.code + `</td>
                            <td>` + v.name + `</td>
                            <td>
                                <input style='width:3rem' class='form-control input-sm' value='` + v.isDivider + `' data-id='` + v.id + `' data-coa='` +  v.code + `'> <button class='btn btn-primary saveData' data-id='` + v.id + `'>Simpan</button>
                            </td>
                        </tr>`;
                })
                $('#list-table').append(tempRow);

                $('.saveData').click(function(e){
                    e.preventDefault();
                    var id_ = $(this).data('id')
                    var selInput_ = $(this).prev().val();
                    $.ajax({
                        url : "{{ url('/') }}/library/harga-satuan/update/tes",
                        type : "post",
                        data : { id: id_, isDivider : selInput_},
                        success: (resp) => {
                            if(resp.status == "success"){
                                HS.toast.success('Saved')
                            }else{
                                HS.toast.error('Not Saved')
                            }
                        }
                    })
                })
            },
        })
    }



</script>