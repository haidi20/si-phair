@extends('layouts.master')

@section('content')
@include('pages.period_payroll.partials.modal')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tanggal Merah</h3>
                {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                        {{-- <li class="breadcrumb-item active" aria-current="page">Tanggal Merah</li> --}}
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        



        <section class="section col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                        </div>
                    </div>
                    {{-- <span class="fs-4 fw-bold">Data Tanggal Merah</span> --}}

                    <br>
                    <div class="row">
                        <div class="col-5">
              
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Tanggal</label>
                                <div class="col-sm-8">
                                    <input name="tanggal" type="date" class="form-control" id="form_add_tanggal" autocomplete="off"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>
                            </div>
    


                        </div>

                        <div class="col-5">
              
                 
    
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Keterangan</label>
                                <div class="col-sm-8">
                                    <input name="keterangan" type="text" class="form-control" id="form_add_keterangan" autocomplete="off"
                                            value="">
                                </div>
                            </div>

                        </div>

                        <div class="col-2">
              
                 
    
                            <div class="form-group row">
                                
                                <div class="col-sm-12">
                                    <button class="btn btn-success" id="btn_tambah">Tambah</button>
                                </div>
                            </div>

                        </div>




                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                        </div>
                    </div>


                </div>



                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                {!! $html->table(['class' => 'table table-striped table-bordered']) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
       
        </section>
    </div>
</div>
@endsection


@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/nocss/litepicker.js"></script>

@endsection
@section('script')
{!! $html->scripts() !!}
<script>
    const initialState = {
        period_payrolls: [],
    };

    let state = {
        ...initialState
    };

    $(document).ready(function() {
        $('.dataTable').DataTable();

        // 

        $(document).on('click', '.btn-download', function() {
            window.open($(this).data('download') , '_blank');
        });

        // new Litepicker({
        //         element: document.getElementById('month_filter'),
        //         format: 'YYYY-MM',
        //         scrollToDate :false,
        //         singleMode: true,
        //         // date:false,
        //         resetButton: true,
        //         splitView : true,
        //         // dropdowns  : {"minYear":1990,"maxYear":null,"months":false,"years":false}
        //     });


            // new Litepicker({
            //     element: document.getElementById('start_of_workdays'),
            //     format: 'YYYY-MM-DD',
            //     singleMode: true,
            //     tooltipText: {
            //         one: 'night',
            //         other: 'nights'
            //     },
            //     tooltipNumber: (totalDays) => {
            //         return totalDays - 1;
            //     },
            // });

            // new Litepicker({
            //     element: document.getElementById('end_of_workdays'),
            //     format: 'YYYY-MM-DD',
            //     singleMode: true,
            //     tooltipText: {
            //         one: 'night',
            //         other: 'nights'
            //     },
            //     tooltipNumber: (totalDays) => {
            //         return totalDays - 1;
            //     },
            // });

            

            $(document).on('click', '#btn_tambah', function() {
                var tanggal = $('#form_add_tanggal').val();
                var keterangan = $('#form_add_keterangan').val();

                if(tanggal == ''  || keterangan == ''){
                     alert("Wajib DIisi");
                     return;
                }
                console.log([tanggal,keterangan]);
                $.ajax({
                    url: "{{ route('tanggal_merah.store') }}",
                    method: 'POST',
                    data: {
                        tanggal:tanggal,
                        keterangan:keterangan
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(responses) {

                        // console.info(responses);

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        if (responses.success == true) {
                            $('#formModal').modal('hide');
                            Toast.fire({
                                icon: 'success',
                                title: responses.message
                            });

                            window.LaravelDataTables["dataTableBuilder"].ajax.reload(
                            function(json) {});
                        }
                    },
                    error: function(err) {
                        console.log(err.responseJSON.message);
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        });
                    }
                });


            });
                
           


        // Does some stuff and logs the event to the console
        // });


        $(document).on('change', '#month_filter', function() {
            // alert();

            var month_year = $(this).val().toString().split('-');
            var month_year_end = $(this).val().toString().split('-');
            // console.log([month_year , month_year_end]);

            month_year[1] = month_year[1] - 1;
            if(month_year[1] == 0){
                month_year[0] = month_year[0] - 1;
                month_year[1] = 12;
            }

            $('#start_of_workdays').val(month_year[0]+"-"+month_year[1]+"-26");
            $('#end_of_workdays').val(month_year_end[0]+"-"+month_year_end[1]+"-25");
           


        // Does some stuff and logs the event to the console
        });



        state.period_payrolls = {!! json_encode($period_payrolls) !!};
        send();
    });

    function onCreate() {
        clearForm();
        $("#titleForm").html("Tambah Tanggal Merah");
        onModalAction("formModal", "show");
    }

    function onEdit(data) {
        clearForm();

        $("#id").val(data.id);
        $("#name").val(data.name);
        $("#description").val(data.description);

        $("#titleForm").html("Ubah Tanggal Merah");
        onModalAction("formModal", "show");
    }

    function send() {
        $("#form").submit(function(e) {
            e.preventDefault();
            let fd = new FormData(this);

            $.ajax({
                url: "{{ route('period_payroll.store') }}",
                method: 'POST',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(responses) {

                    // console.info(responses);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                    if (responses.success == true) {
                        $('#formModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: responses.message
                        });

                        window.LaravelDataTables["dataTableBuilder"].ajax.reload(
                        function(json) {});
                    }
                },
                error: function(err) {
                    console.log(err.responseJSON.message);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: 'error',
                        title: err.responseJSON.message
                    });
                }
            });
        });
    }

    function onDelete(data) {
        Swal.fire({
            title: 'Perhatian!!!',
            html: `Anda yakin ingin hapus data kapal <h2><b> ${data.name} </b> ?</h2>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            onfirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('period_payroll.delete') }}",
                    method: 'DELETE',
                    dataType: 'json',
                    data: {
                        id: data.id
                    },
                    success: function(responses) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        $('#formModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: responses.message
                        });

                        window.LaravelDataTables["dataTableBuilder"].ajax.reload(
                        function(json) {});
                    },
                    error: function(err) {
                        // console.log(err.responseJSON.message);
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        });
                    }
                });
            }
        });
    }

    function clearForm() {
        $("#id").val("");
        $("#name").val("");
        $("#description").val("");
    }
</script>
@endsection
