@extends('layouts.master')

@section('content')
    <div id="root">
        <job-order-report base-url="{{ $baseUrl }}" user="{{ $user }}" />
    </div>
@endsection

@section('style')
    <style>
        #root {
            /* background-color: white; */
        }
    </style>
@endsection

@section('script')
    {{--  --}}
@endsection
