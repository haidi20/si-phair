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
            <th>NIP</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>No. Telepon</th>
            <th>Agama</th>
            <th>Alamat</th>
            <th>Tanggal Masuk</th>
            <th>NPWP</th>
            <th>No BPJS</th>
            <th>No BPJS Tenaga Kerja</th>
            <th>Perusahaan</th>
            <th>Posisi</th>
            <th>Lokasi</th>
            <th>Tipe Karyawan</th>
            <th>Awal Kontrak</th>
            <th>Akhir Kontrak</th>
            <th>Pendidikan Terakhir</th>
            <th>Jam Kerja</th>
            <th>BPJS JHT</th>
            <th>BPJS JKK</th>
            <th>BPJS JKM</th>
            <th>BPJS JP</th>
            <th>BPJS Kesehatan</th>
            <th>PTKP</th>
            <th>PTKP Karyawan</th>
            <th>Status Menikah</th>
            <th>Status Karyawan</th>
            <th>Tanggal Keluar</th>
            <th>Alasan</th>

            <!-- DATA GAJI & REKENING -->
            <th>Gaji Pokok</th>
            <th>Tunjangan</th>
            <th>Tunjangan Makan per Kehadiran</th>
            <th>Tunjangan Transport per Kehadiran</th>
            <th>Tunjangan Kehadiran per Kehadiran</th>
            <th>Biaya Lembur per Jam</th>
            <th>PPN per Tahun</th>

            <th>Nomor Rekening</th>
            <th>Nama Pemilik Rekening</th>
            <th>Nama Bank</th>
            <th>Cabang</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $item->nip }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->birth_place }}</td>
                <td>{{ $item->birth_date }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->religion }}</td>
                <td>{{ $item->address }}</td>
                <td>{{ $item->enter_date }}</td>
                <td>{{ $item->npwp }}</td>
                <td>{{ $item->no_bpjs }}</td>
                <td>{{ $item->no_bpjs_tenaga_kerja }}</td>
                <td>{{ $item->company_name }}</td>
                <td>{{ $item->position_name }}</td>
                <td>{{ $item->location_name }}</td>
                <td>{{ $item->employee_type_name }}</td>
                <td>{{ $item->contract_start }}</td>
                <td>{{ $item->contract_end }}</td>
                <td>{{ $item->latest_education }}</td>
                <td>{{ $item->working_hour }}</td>
                <td>{{ $item->bpjs_jht == 'Y' ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $item->bpjs_jkk == 'Y' ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $item->bpjs_jkm == 'Y' ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $item->bpjs_jp == 'Y' ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $item->bpjs_kes == 'Y' ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $item->ptkp }}</td>
                <td>{{ $item->ptkp_karyawan }}</td>
                <td>{{ $item->married_status }}</td>
                <td>
                    @if ($item->employee_status === 'aktif')
                        Aktif
                    @else
                        Tidak Aktif
                    @endif
                </td>
                <td>{{ $item->out_date }}</td>
                <td>{{ $item->reason }}</td>
                <td>{{ number_format($item->basic_salary, 0, ',', '.') }}</td>
                <td>{{ number_format($item->allowance, 0, ',', '.') }}</td>
                <td>{{ number_format($item->meal_allowance_per_attend, 0, ',', '.') }}</td>
                <td>{{ number_format($item->transport_allowance_per_attend, 0, ',', '.') }}</td>
                <td>{{ number_format($item->attend_allowance_per_attend, 0, ',', '.') }}</td>
                <td>{{ number_format($item->overtime_rate_per_hour, 0, ',', '.') }}</td>
                <td>{{ number_format($item->vat_per_year, 0, ',', '.') }}</td>
                <td>{{ $item->rekening_number }}</td>
                <td>{{ $item->rekening_name }}</td>
                <td>{{ $item->bank_name }}</td>
                <td>{{ $item->branch }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
