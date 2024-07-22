@php
    use Carbon\Carbon;
@endphp

<table>
    <tr>
        <td colspan="4"
            style="font-size: 15px; font-weight: bold; text-align: center; vertical-align: middle; height: 50px">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <p style="margin-top: 10px; padding-left: 30px;">PT KARYA PACIFIC TEKNIK SHIPYARD</p>
        </td>
    </tr>
    {{-- <tr></tr> --}}
    <thead>
        <tr>
            <th nowrap>Nama</th>
            <th nowrap>Jabatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['position_name'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
