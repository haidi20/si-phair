@extends('layouts.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    {{-- <span class="fs-4 fw-bold">Data Departemen</span>
                    <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                        data-toggle="modal">
                        <i class="fas fa-plus text-white-50"></i> Tambah Departemen
                    </button> --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            isi dashboard
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        const initialState = {
            departmens: [],
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            //
        });
    </script>
@endsection
