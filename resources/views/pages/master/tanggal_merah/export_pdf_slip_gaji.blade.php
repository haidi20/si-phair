<style>
    .page-break {
        page-break-after: always;
    }
    </style>

@php
    $rosters = \App\Models\RosterStatus::all();

    
@endphp
@foreach ($employees->chunk(10) as $employee_chunk)

    @foreach ($employee_chunk as $employee)
    @php
        $payroll = \App\Models\Payroll::where('period_payroll_id',$period_payroll->id)->where('employee_id',$employee->id)->first();
        $attendance = \App\Models\AttendancePayrol::where('payroll_id',\App\Models\Payroll::where('period_payroll_id',$period_payroll->id)->where('employee_id',$employee->id)->first()->id)->orderBy('date','asc')->get();
    @endphp
    <table width="100%" cellspacing="0" cellpadding="0" border="1">
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td> 
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
        
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 18px; background-color:#92d050" colspan="14" align="center">PERHITUNGAN GAJI</td>
        
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Bulan</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">{{\Carbon\Carbon::parse($period_payroll->period)->translatedFormat('F')}}</td>
            <td style="font-size: 12px;">{{\Carbon\Carbon::parse($period_payroll->period)->translatedFormat('Y')}}</td>
            <td style="font-size: 12px;" colspan="2">Gaji Dasar</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$employee->basic_salary}}</td>
            <td style="font-size: 12px;">  /bulan </td>
            <td align="left" style="font-size: 12px;" colspan="2">Tunj. Makan</td>
        
            <td align="right"style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$employee->meal_allowance_per_attend}}</td>
            <td style="font-size: 12px;">/hadir</td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Id</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;" colspan="2">{{$employee->nip}}</td>
            <td style="font-size: 12px;" colspan="2">Tunj. Tetap</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$employee->allowance}}</td>
            <td style="font-size: 12px;">  /bulan </td>
            <td style="font-size: 12px;" colspan="2">Tunj. Transport</td>
        
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$employee->transport_allowance_per_attend}}</td>
            <td style="font-size: 12px;">/hadir</td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Nama</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;" colspan="2">{{$employee->name}}</td>
            <td style="font-size: 12px;" colspan="2">Rate Lembur</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$employee->overtime_rate_per_hour}}</td>
            <td style="font-size: 12px;">/jam</td>
            <td style="font-size: 12px;" colspan="2">Tunj. Kehadiran</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$employee->attend_allowance_per_attend}}</td>
            <td style="font-size: 12px;">/hadir</td>
        </tr>

        {{-- position --}}
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Posisi</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;" colspan="2">{{$employee->position->name ?? ''}}</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;" colspan="2">PTKP Karyawan</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$employee->ptkp_karyawan}}</td>
            <td style="font-size: 12px;">/tahun</td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="left" style="font-size: 12px;" colspan="2">Jumlah Cuti/Ijin</td>
            <td style="font-size: 12px;" colspan="2">-</td>
            <td style="font-size: 12px;">  /bulan </td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="left" style="font-size: 12px;" colspan="2">Sisa Cuti</td>
            <td style="font-size: 12px;" colspan="2">-</td>
            <td style="font-size: 12px;">hari/tahun</td>
        </tr>

        <tr>
            <td align="center" style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;" rowspan="2">Kode Hari Kerja</td>
            <td align="center" rowspan="2" style="font-size: 12px; background-color: #92d050">Tanggal</td>
            <td align="center" rowspan="2" style="font-size: 12px; background-color: #92d050">Hari</td>
            <td align="center" style="font-size: 12px;">Masuk</td>
            <td align="center" style="font-size: 12px;">Keluar</td>
            <td align="center" style="font-size: 12px; background-color: #92d050">Durasi</td>
            <td align="center" style="font-size: 12px;">Koreksi</td>
            <td align="center" style="font-size: 12px; background-color: #92d050">Istirahat</td>
            <td align="center" style="font-size: 12px; background-color: #92d050">Jam Kerja</td>
            <td align="center" style="font-size: 12px; background-color: #92d050">Normal</td>
            <td align="center" colspan="4" style="font-size: 12px; background-color: #92d050">Perhitungan Lembur</td>

        </tr>

        <tr>
            
            
            <td align="center" style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;">(hh:mm)</td>
            <td align="center" style="font-size: 12px;">(hh:mm)</td>
            <td align="center" style="font-size: 12px;">(jam)</td>
            <td align="center" style="font-size: 12px;">(jam)</td>
            <td align="center" style="font-size: 12px;">(jam)</td>
            <td align="center" style="font-size: 12px;">(jam)</td>
            <td align="center" style="font-size: 12px;">(jam)</td>
            <td align="center" style="font-size: 12px;">X1</td>
            <td align="center" style="font-size: 12px;">X2</td>
            <td align="center" style="font-size: 12px;">X3</td>
            <td align="center" style="font-size: 12px;">X4</td>
        </tr>


        @php
            $iter=0;
        @endphp
        @foreach ($attendance->chunk(10) as $att)
            @foreach ($att as $a)
                @if (($iter > 0 && $tanggal_lama != $a->date) or ($iter==0))
            @php

        

            $tanggal_lama  = $a->date;



            $iter++;
            $tanggal  = \Carbon\Carbon::parse($a->date);

            $d_hour = \floor($a->duration_work / 60);
            $d_minute = $a->duration_work%60 ;

            $jam_kerja_kotor_hour = \floor((($a->duration_work + $a->duration_overtime))/60);
            $jam_kerja_kotor_minute = ((($a->duration_work + $a->duration_overtime))/60)%60;


            $jam_kerja_bersih_hour = \floor((($a->duration_work + $a->duration_overtime) - $a->duration_rest)/60);
            $jam_kerja_bersih_minute = ((($a->duration_work + $a->duration_overtime) - $a->duration_rest)/60)%60;

            $jam_istirahat_hour = \floor(( $a->duration_rest)/60);
            $jam_istirahat_minute = (( $a->duration_rest)/60)%60;


        @endphp
                <tr style="background-color:red">

                    @php
                    $background_color = 'white';

                    
                    if(\strtolower($a->roster_status_initial) == 'off'){
                        $background_color = 'red';
                    }
                    @endphp
                    <td align="center" style="font-size: 12px;"><br></td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$a->roster_status_initial}}</td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{round($tanggal->translatedFormat('d'))}}</td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$tanggal->translatedFormat('l')}}</td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{ $a->hour_start != null ? \Carbon\Carbon::parse($a->hour_start)->translatedFormat('H:i') : ''}}</td>

                    @if ($a->hour_overtime_end != null)
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{ $a->hour_overtime_end != null ? \Carbon\Carbon::parse($a->hour_overtime_end)->translatedFormat('H:i') : ''}}</td>
                    @else
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{ $a->hour_end != null ? \Carbon\Carbon::parse($a->hour_end)->translatedFormat('H:i') : ''}}</td>
                    @endif
                    
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$jam_kerja_kotor_hour}} : {{$jam_kerja_kotor_minute}}</td>
                    {{-- <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$d_hour}} : {{$d_minute}}</td> --}}
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}"><br></td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$jam_istirahat_hour}} : {{$jam_istirahat_minute}}</td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$jam_kerja_bersih_hour}} : {{$jam_kerja_bersih_minute}}</td>
                    
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">---</td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$a->lembur_kali_satu_lima}}</td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$a->lembur_kali_dua}}</td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$a->lembur_kali_tiga}}</td>
                    <td align="center" style="font-size: 12px; background-color: {{$background_color}}">{{$a->lembur_kali_empat}}</td>
                    <td align="center" style="font-size: 12px;"><br></td>
                </tr>
            @endif
                
            @endforeach
        

    
    
        @endforeach

        @if ($iter < 32)
        @for ($i = 0; $i < (32 - $iter); $i++)
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
        @endfor
    @endif
        

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">TOTAL</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">t1</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>


        <tr>
            <td style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;" colspan="2">Jumlah Masuk Hari Kerja</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;">{{$payroll->jumlah_hari_tunjangan_makan}}</td>
            <td style="font-size: 12px;">hari</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Total Jam Lembur</td>
            <td align="center" style="font-size: 12px;">{{$payroll->jumlah_jam_rate_lembur}}</td>
            <td align="center" style="font-size: 12px;">jam</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>

    </table>

        <div class="page-break"></div>

        <table width="100%" cellspacing="0" cellpadding="0" border="1">

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Perhitungan Gaji</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">t1</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">II. Perhitungan BPJS</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;" colspan="2">Dasar Upah BPJS TK</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$payroll->dasar_updah_bpjs_tk}}</td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">A. Pendapatan Gaji</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="font-size: 12px;">Dasar Upah BPJS KES</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$payroll->dasar_updah_bpjs_kes}}</td>
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;"> &nbsp;1. Gaji Dasar</td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;">1</td>
            <td align="center" style="font-size: 12px;">Bulan</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$payroll->gaji_dasar}}</td>
            <td align="center" style="font-size: 12px;">Jaminan Sosial</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 10px;">%Perusahaan</td>
            <td align="center" style="font-size: 10px;">%Karyawan</td>
            <td align="center" style="font-size: 10px;">Rp Perusahaan</td>
            <td align="center" style="font-size: 10px;">Rp Karyawan</td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>

            <td colspan="2" style="left" style="font-size: 12px;"> &nbsp;2. Tunjangan Tetap</td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;">1</td>
            <td align="center" style="font-size: 12px;">Bulan</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$payroll->pendapatan_tunjangan_tetap}}</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>

        <tr >
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;" >  &nbsp;3. Uang Makan</td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;">{{$payroll->jumlah_hari_tunjangan_makan}}</td>
            <td align="center" style="font-size: 12px;">Hari</td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$payroll->pendapatan_uang_makan}}</td>
            <td style="font-size: 12px;">1. Hari Tua (JHT)</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;">{{$payroll->jht_perusahaan_persen}} % </td>
            <td align="right" style="font-size: 12px;">{{$payroll->jht_karyawan_persen}} % </td>
            <td align="right" style="font-size: 12px;" data-format="#,##" >{{$payroll->jht_perusahaan_rupiah}}</td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->jht_karyawan_rupiah}}</td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;"> &nbsp;4. Lembur</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">{{$payroll->jumlah_jam_rate_lembur}}</td>
            <td style="font-size: 12px;">Jam</td>
            <td align="right" style="font-size: 12px;" data-format="#,##" colspan="2">{{$payroll->pendapatan_lembur}}</td>
            <td style="font-size: 12px;">2. Kecelakaan (JKK)</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;">{{$payroll->jkk_perusahaan_persen}} % </td>
            <td align="right" style="font-size: 12px;">{{$payroll->jkk_karyawan_persen}} % </td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->jkk_perusahaan_rupiah}}</td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->jkk_karyawan_rupiah}}</td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;"> &nbsp;5. Tambahan Lain Lain</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"></td>
            <td style="font-size: 12px;"></td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$payroll->pendapatan_tambahan_lain_lain}}</td>
            <td style="font-size: 12px;">3. Kematian (JKM)</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;">{{$payroll->jkm_perusahaan_persen}} % </td>
            <td align="right" style="font-size: 12px;">{{$payroll->jkm_karyawan_persen}} % </td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->jkm_perusahaan_rupiah}}</td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->jkm_karyawan_rupiah}}</td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Jumlah Pendapatan Kotor A</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"></td>
            <td style="font-size: 12px;"></td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{$payroll->jumlah_pendapatan}}</td>
            <td style="font-size: 12px;">4. Pensiun (JP)</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;">{{$payroll->jp_perusahaan_persen}} % </td>
            <td align="right" style="font-size: 12px;">{{$payroll->jp_karyawan_persen}} % </td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->jp_perusahaan_rupiah}}</td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->jp_karyawan_rupiah}}</td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">B. Pemotongan</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
            <td style="font-size: 12px;">5. Kesehatan (BPJS)</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;">{{$payroll->bpjs_perusahaan_persen}} % </td>
            <td align="right" style="font-size: 12px;">{{$payroll->bpjs_karyawan_persen}} % </td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->bpjs_perusahaan_rupiah}}</td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->bpjs_karyawan_rupiah}}</td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;"> &nbsp;1. BPJS dibayar Karyawan</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{\round($payroll->pemotongan_bpjs_dibayar_karyawan)}}</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;"> &nbsp;2. Pajak Penghasilan PPH21((H)/12)</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{\round($payroll->pemotongan_pph_dua_satu)}}</td>
            <td style="font-size: 12px;">Total</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>

            <td align="right" style="font-size: 12px;">{{$payroll->total_bpjs_perusahaan_persen}} %</td>
            <td align="right" style="font-size: 12px;">{{$payroll->total_bpjs_karyawan_persen}} %</td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->total_bpjs_perusahaan_rupiah}}</td>
            <td align="right" style="font-size: 12px;" data-format="#,##">{{$payroll->total_bpjs_karyawan_rupiah}}</td>
            
        </tr>


        <tr>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;">  &nbsp;3. Potongan Absen</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{\round($payroll->pemotongan_potongan_lain_lain)}}</td>
            <td colspan="2" style="font-size: 12px;">IV. Perhitungan Pajak Penghasilan (PPH21) </td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;"> &nbsp;4. Potongan Hutang</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{\round($payroll->pemotongan_potongan_lain_lain)}}</td>
            
            <td style="font-size: 12px;">D. Penghasilan kotor </td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td colspan="2" style="left" style="font-size: 12px;"> &nbsp;5. Potongan Lain-Lain</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{\round($payroll->pemotongan_potongan_lain_lain)}}</td>
    
            <td style="font-size: 12px;">1. Gaji Kotor - Potongan </td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pajak_gaji_kotor_kurang_potongan}}</td>
            
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Jumlah Potongan (B)</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" data-format="Rp* #,##" colspan="2">{{\round($payroll->jumlah_pemotongan)}}</td>
            
            <td style="font-size: 12px;">2. BPJS dibayar Perusahaan </td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pajak_bpjs_dibayar_perusahaan}}</td>
        
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Total Penghasilan Kotor (D) </td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pajak_total_penghasilan_kotor}}</td>
            
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">E. Pengurang</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;" colspan="2">C. Gaji Bersih (A)-(B)</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px; background-color:#fcfc04" data-format="Rp* #,##" colspan="2">{{round($payroll->gaji_bersih)}}</td>

            <td style="font-size: 12px;">1. Biaya Jabatan (5% x (D)) </td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pajak_biaya_jabatan}}</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">2. BPJS dibayar Karyawan </td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pajak_bpjs_dibayar_karyawan}}</td>
            
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Dihitung Oleh</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Diterima Oleh</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">Jumlah Pengurang (E) </td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pajak_total_pengurang}}</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">F. Gaji Bersih 12 Bulan</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pajak_gaji_bersih_setahun}}</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">G. PKP 12 Bulan = (F)- PTKP</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pkp_setahun > 0 ? $payroll->pkp_setahun : 0}}</td>
            
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;">Tarif</td>
            <td align="center" style="font-size: 12px;">Dari PKP</td>
            <td align="center" style="font-size: 12px;">Ke PKP</td>
            <td align="center" style="font-size: 12px;">Progressive PPH 21</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;">5%</td>
            <td align="center" style="font-size: 12px;"> 0 Jt</td>
            <td align="center" style="font-size: 12px;">60 Jt</td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pkp_lima_persen ?? 0}}</td>
        
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;">15%</td>
            <td align="center" style="font-size: 12px;"> 60 Jt</td>
            <td align="center" style="font-size: 12px;">250 Jt</td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pkp_lima_belas_persen ?? 0}}</td>
            
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;">25%</td>
            <td align="center" style="font-size: 12px;"> 250 Jt</td>
            <td align="center" style="font-size: 12px;"> 500 Jt</td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pkp_dua_puluh_lima_persen ?? 0}}</td>
        
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="center" style="font-size: 12px;">30%</td>
            <td align="center" style="font-size: 12px;"> 500 Jt</td>
            <td align="center" style="font-size: 12px;"> 1000 Jt</td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pkp_tiga_puluh_persen ?? 0}}</td>
        
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>

        <tr>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;">G. PPH21 Setahun</td>
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
            <td align="right" style="font-size: 12px;" colspan="2" data-format="Rp* #,##">{{$payroll->pajak_pph_dua_satu_setahun > 0 ? $payroll->pajak_pph_dua_satu_setahun : 0}}</td>
        
            <td style="font-size: 12px;"><br></td>
            <td style="font-size: 12px;"><br></td>
        </tr>
    </table>

    <div class="page-break"></div>
    @endforeach
@endforeach