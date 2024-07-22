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
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <span class="fs-4 fw-bold">Chart</span>
                            <span>
                                {{ $dateNowReadable }}
                            </span>
                            {{-- <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                        data-toggle="modal">
                        <i class="fas fa-plus text-white-50"></i> Tambah Departemen
                    </button> --}}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <span class="fs-4 fw-bold">data</span>
                            {{-- <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                        data-toggle="modal">
                        <i class="fas fa-plus text-white-50"></i> Tambah Departemen
                    </button> --}}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Waktu</th>
                                                <th scope="col">pH Air</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>12:00:00</td>
                                                <td>6.5</td>
                                            </tr>
                                            <tr>
                                                <td>12:00:05</td>
                                                <td>6.4</td>
                                            </tr>
                                            <tr>
                                                <td>12:00:10</td>
                                                <td>6.6</td>
                                            </tr>
                                            <tr>
                                                <td>12:00:15</td>
                                                <td>6.7</td>
                                            </tr>
                                            <tr>
                                                <td>12:00:20</td>
                                                <td>6.5</td>
                                            </tr>
                                            <tr>
                                                <td>12:00:25</td>
                                                <td>6.6</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="{{ asset('js/chart.js') }}"></script>
    <script>
        const initialState = {
            departmens: [],
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            setupChart();
        });

        function setupChart() {
            const ctx = document.getElementById('myChart').getContext('2d');

            // Dummy data for pH levels and corresponding times
            const labels = ['08:10:05', '08:10:10', '08:10:15', '08:10:20', '08:10:25', '08:10:30'];

            // Dummy pH values
            const phValues = [7.0, 7.1, 6.9, 7.2, 7.0, 7.1, 7.3, 7.2, 7.0, 6.8, 7.1, 7.2, 7.3];

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'pH Level',
                        data: phValues,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Time (s)'
                            }
                        },
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: 'pH Level'
                            },
                            min: 6.5,
                            max: 8
                        }
                    }
                }
            });
        }
    </script>
@endsection
