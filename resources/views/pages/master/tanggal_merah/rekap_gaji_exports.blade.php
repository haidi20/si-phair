@php
    $rosters = \App\Models\RosterStatus::all();
    $jabatan  = '';
@endphp
<table>


    @if ($type_employe =='cv')
        <tr>
            <td align="center" colspan="19">CV. KARYA PACIFIC TEHNIK</td>
        </tr>
    @endif

    @if ($type_employe =='pt')
        <tr>
            <td align="center" colspan="19">PT. KARYA PACIFIC TEHNIK</td>
        </tr>
    @endif

    @if ($type_employe =='all')
        <tr>
            <td align="center" colspan="19">CV. KARYA PACIFIC TEHNIK   &amp; PT. KARYA PACIFIC TEHNIK</td>
        </tr>
    @endif


    <tr>
        <td align="center" colspan="19">REKAP GAJI/UPAH</td>
    </tr>

    <tr>
        <td align="center" colspan="19">{{\Carbon\Carbon::parse($period_payroll->period)->translatedFormat('F')}} {{\Carbon\Carbon::parse($period_payroll->period)->translatedFormat('Y')}}</td>
    </tr>


    

    
    
    <tr>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
    </tr>

    <tr>
        <td style="background-color: #92d050" align="center" rowspan="3">NO</td>
        <td style="background-color: #92d050" align="center" rowspan="3">NAMA</td>
        <td style="background-color: #92d050" align="center" rowspan="3">JABATAN</td>
        <td style="background-color: #92d050" align="center" rowspan="3">HARI KERJA</td>
        <td style="background-color: #92d050" align="center" colspan="9">PENDAPATAN</td>
        
        <td style="background-color: #92d050" align="center" colspan="5">PEMOTONGAN</td>
        <td style="background-color: #92d050" align="center" rowspan="3">GAJI BERSIh</td>

    </tr>

    <tr>
        
        <td style="background-color: #92d050" align="center" rowspan="2">UPAH POKOK </td>
        <td style="background-color: #133cc4" align="center" colspan="7">TUNJANGAN</td>

        <td style="background-color: #92d050" align="center" rowspan="2">PKP</td>
        
        <td style="background-color: #92d050" align="center" rowspan="2">BPJS</td>
        <td style="background-color: #92d050" align="center" rowspan="2">PPH21</td>
        <td style="background-color: #92d050" align="center" rowspan="2">HUTANG</td>
        <td style="background-color: #92d050" align="center" rowspan="2">ABSEN</td>
        <td style="background-color: #92d050" align="center" rowspan="2">LAIN2</td>
    </tr>

    <tr>
        
   
        <td style="background-color: #92d050" align="center">T.TETAP</td>
        <td style="background-color: #92d050" align="center">UANG MAKAN</td>
        <td style="background-color: #92d050" align="center">LEMBUR</td>
        <td style="background-color: #92d050" align="center">LAIN2</td>
        <td style="background-color: #92d050" align="center">INSENTIF</td>
        <td style="background-color: #92d050" align="center">BPJS YG DIBYR</td>
        <td style="background-color: #92d050" align="center">POTONGAN</td>
        
    </tr>


    @foreach ($payrolls as $p)

    @if ($jabatan != $p->position_name)

    @php
        $jabatan = $p->position_name;
    @endphp

    <tr>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
    </tr>

        
    @endif


    <tr>
        <td align="center">{{$loop->iteration}}</td>
        <td style="background-color: #133cc4" align="center">{{$p->name}}</td>
        <td align="center">{{$p->position_name}}</td>
        <td align="center">{{$p->jumlah_hari_tunjangan_makan}}</td>
        <td data-format="#,##" align="right">{{$p->basic_salary}}</td>
        <td data-format="#,##" align="right">{{$p->allowance}}</td>
        <td data-format="#,##" align="right">{{$p->pendapatan_uang_makan}}</td>
        <td data-format="#,##" align="right">{{$p->pendapatan_lembur}}</td>
        <td data-format="#,##" align="right">{{$p->pendapatan_tambahan_lain_lain}}</td>
        <td data-format="#,##" align="right">0</td>

        
        <td data-format="#,##" align="right">{{$p->total_bpjs_perusahaan_rupiah}}</td>
        
        <td data-format="#,##" align="right">0</td>

        <td data-format="#,##" align="right">{{\floor($p->pkp_setahun/12)}}</td>


        <td data-format="#,##" align="right">{{$p->pajak_bpjs_dibayar_karyawan}} </td>
        <td data-format="#,##" align="right">{{$p->pemotongan_pph_dua_satu}} </td>

        <td data-format="#,##" align="right">{{$p->jumlah_hutang}} </td>

        <td data-format="#,##" align="right">{{$p->pemotongan_tidak_hadir}} </td>

       

        
        
        
        <td data-format="#,##" align="right">{{$p->pemotongan_potongan_lain_lain}}</td>
        
        <td data-format="#,##" align="right">{{$p->gaji_bersih}}</td>
       
  
    
    </tr>
    @endforeach

    <tr>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
    </tr>

    <tr>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
    </tr>

    <tr>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td align="center"><br></td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pendapatan_gaji_dasar')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pendapatan_tunjangan_tetap')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pendapatan_uang_makan')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pendapatan_lembur')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pendapatan_tambahan_lain_lain')}}</td>
        <td data-format="#,##" align="right">0</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('total_bpjs_perusahaan_rupiah')}}</td>
        <td data-format="#,##" align="right">0</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pkp')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pajak_bpjs_dibayar_karyawan')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pemotongan_pph_dua_satu')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('jumlah_hutang')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pemotongan_tidak_hadir')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('pemotongan_potongan_lain_lain')}}</td>
        <td data-format="#,##" align="right">{{$payrolls->sum('gaji_bersih')}}</td>
    </tr>



    
    

    






















</table>