@extends('layouts.master')

@section('content')
    @include('pages.master.position.partials.modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Slip Gaji Karyawan</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Slip Gaji Karyawan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employees">Pilih karyawan</label>
                                <select name="employees" id="select_employee" class="form-control select2" style="width: 100%;">
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->name }} - {{$employee->position_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        
                        
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="basicInput">Pilih Bulan</label>

                                @php
                                    $monthNow = \Carbon\Carbon::now()->format('m');
                                    $yearNow = \Carbon\Carbon::now()->format('Y');
                                    $tahuns = [2023,2024,2025,2026,2027,2028,2029,2030,2031];
                                @endphp
                                <select name="bulan" id="select_bulan" class="form-control select2">
                                    <option {{$monthNow == 1 ? 'selected' : ''}} value="1">Jan</option>
                                    <option {{$monthNow == 2 ? 'selected' : ''}} value="2">Feb</option>
                                    <option {{$monthNow == 3 ? 'selected' : ''}} value="3">Mar</option>

                                    <option {{$monthNow == 4 ? 'selected' : ''}} value="4">Apr</option>
                                    <option {{$monthNow == 5 ? 'selected' : ''}} value="5">Mei</option>
                                    <option {{$monthNow == 6 ? 'selected' : ''}} value="6">Jun</option>

                                    <option {{$monthNow == 7 ? 'selected' : ''}} value="7">Jul</option>
                                    <option {{$monthNow == 8 ? 'selected' : ''}} value="8">Agt</option>
                                    <option {{$monthNow == 9 ? 'selected' : ''}} value="9">Sept</option>

                                    <option {{$monthNow == 10 ? 'selected' : ''}} value="10">Okt</option>
                                    <option {{$monthNow == 11 ? 'selected' : ''}} value="11">Nov</option>
                                    <option {{$monthNow == 12 ? 'selected' : ''}} value="12">Des</option>


                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="basicInput">Pilih Tahun</label>

                                
                                <select name="bulan" id="select_tahun" class="form-control select2">
                                   @foreach ($tahuns as $tahun)
                                   <option {{$monthNow == $tahun ? 'selected' : ''}} value="{{$tahun}}">{{$tahun}}</option>
                                   @endforeach
                                    
                                </select>
                            </div>
                        </div>


                        
                        <div class="col-md-2" style="align-self: center;">
                            <button type="button"  id="btn_download" class="btn btn-sm btn-success mt-2 ml-4 mt-md-0">
                                Download
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>

        </section>
    </div>

    <div class="modal fade attendance_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">

    
    
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/nocss/litepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            setupSelect();
        });



        function setupSelect() {
            $(".select2").select2();
        }


        $(document).on("click","#btn_download",function(e) {
            var bulan  = $('#select_bulan').val();
            var tahun  = $('#select_tahun').val();

            var employee_id  = $('#select_employee').val();

            if(bulan =='' || employee_id =='' || tahun ==''  ){
                alert("WAJIB ISI");
                return;
            }

            window.open("{{ route('payslip.index') }}/download_slip?bulan="+bulan+"&tahun="+tahun+"&employee_id="+employee_id, '_blank'); 

            // $.ajax({
            //         url: "{{ route('payslip.store') }}",
            //         method: 'POST',
            //         data: {
            //             bulan:bulan,
            //             tahun:tahun,
            //             employee_id:employee_id
            //         },
            //         cache: false,
            //         dataType: 'json',
            //         success: function(responses) {

            //             // console.info(responses);

            //             const Toast = Swal.mixin({
            //                 toast: true,
            //                 position: 'top-end',
            //                 showConfirmButton: false,
            //                 timer: 2500,
            //                 timerProgressBar: true,
            //                 didOpen: (toast) => {
            //                     toast.addEventListener('mouseenter', Swal.stopTimer)
            //                     toast.addEventListener('mouseleave', Swal.resumeTimer)
            //                 }
            //             });
            //             if (responses.success == true) {
            //                 $('#formModal').modal('hide');
            //                 Toast.fire({
            //                     icon: 'success',
            //                     title: responses.message
            //                 });

            //                 window.LaravelDataTables["dataTableBuilder"].ajax.reload(
            //                 function(json) {});
            //             }
            //         },
            //         error: function(err) {
            //             console.log(err.responseJSON.message);
            //             const Toast = Swal.mixin({
            //                 toast: true,
            //                 position: 'top-end',
            //                 showConfirmButton: false,
            //                 timer: 4000,
            //                 timerProgressBar: true,
            //                 didOpen: (toast) => {
            //                     toast.addEventListener('mouseenter', Swal.stopTimer)
            //                     toast.addEventListener('mouseleave', Swal.resumeTimer)
            //                 }
            //             });

            //             Toast.fire({
            //                 icon: 'error',
            //                 title: err.responseJSON.message
            //             });
            //         }
            //     });


        });
    </script>
   
@endsection
