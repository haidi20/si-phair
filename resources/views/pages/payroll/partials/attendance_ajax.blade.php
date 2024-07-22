<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <table style="width: 100%" class="table">
                    <tr>

                        <td>Kode Hari Kerja</td>
                        <td>Tanggal</td>
                        <td>Hari</td>
                        <td>Masuk</td>
                        <td>Keluar</td>
                        <td>Durasi</td>
                        <td>Koreksi Jam</td>
                        <td>Istirahat</td>
                        <td>Jam Kerja</td>
                        <td>Jam Lembur</td>
                        <td>Normal</td>
                        <td colspan="4">Perhitungan Lembur</td>
                       
                        <td>Aksi</td>
                    </tr>

                    <tr>
     
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td>
                             {{-- --  --}}
                            </td>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td>x1</td>
                        <td>x2</td>
                        <td>x3</td>
                        <td>x4</td>
                        <td><br></td>
                    </tr>

                    @php
                        $iter=0;
                    @endphp
                    @foreach ($attendance as $a)

                     
                        @php

                    

                        $tanggal_lama  = $a->date;



                        $iter++;
                        $tanggal  = \Carbon\Carbon::parse($a->date);

                        if($a->hour_start == null or $a->hour_end == null){
                            $d_hour = 0;
                            $d_minute = 0;
                        }else{
                            $d_hour = \floor($a->duration_work / 60);
                            $d_minute = $a->duration_work%60 ;
                        }

                       
                    @endphp

                                @if ( $a->roster_status_initial =='OFF' or $a->roster_status_initial =='C')
                                <tr style="background-color: #white">
                                    <td style="color:red"> {{$a->roster_status_initial}}</td>

                                    <td style="color:red">{{round($tanggal->translatedFormat('d'))}}</td>
                                    <td style="color:red">{{$tanggal->translatedFormat('l')}}</td>
                                    <td style="color:red">{{ $a->hour_start != null ? \Carbon\Carbon::parse($a->hour_start)->translatedFormat('H:i') : ''}}</td>

                                    @if ($a->duration_overtime != null)
                                    <td style="color:red">{{ $a->hour_overtime_end != null ? \Carbon\Carbon::parse($a->hour_overtime_end)->translatedFormat('H:i') : ''}}</td>
                                    @else
                                    <td style="color:red">{{ $a->hour_end != null ? \Carbon\Carbon::parse($a->hour_end)->translatedFormat('H:i') : ''}}</td>
                                    @endif
                                
                                    <td style="color:red">{{$d_hour}} : {{$d_minute}}</td>
                                    <td style="color:red"><br></td>
                                    <td style="color:red">{{\floor($a->duration_rest/60)}}</td>
                                    <td style="color:red">{{\floor((($a->duration_work + $a->duration_overtime) - $a->duration_rest)/60)}}</td>
                                    <td style="color:red">{{\floor(((0 + $a->duration_overtime) - 0)/60)}}</td>
                                    <td style="color:red">{{\floor($a->working_hour/60)}} </td>
                                    <td style="color:red">{{$a->lembur_kali_satu_lima}}</td>
                                    <td style="color:red">{{$a->lembur_kali_dua}}</td>
                                    <td style="color:red">{{$a->lembur_kali_tiga}}</td>
                                    <td style="color:red">{{$a->lembur_kali_empat}}</td>
                                    <td style="color:red">
                                        @if ($a->is_final == 0)
                                        <a href="#" class="btn icon btn-primary edit_modal_attendance" data-href="{{\URL::to('/')}}/payroll/{{$a->id}}/edit_attendance" data-container=".attendance_modal"><i class="bi bi-pencil"></i></a>
                                        @endif
                                        
                                    </td>
                                </tr>

                            @elseif ( $a->hour_start == null or $a->hour_end == null)
                            <tr style="background-color: #b61e1e">
                                <td style="color:white"> {{$a->roster_status_initial}}</td>
                     
                                <td style="color:white">{{round($tanggal->translatedFormat('d'))}}</td>
                                <td style="color:white">{{$tanggal->translatedFormat('l')}}</td>
                                <td style="color:white">{{ $a->hour_start != null ? \Carbon\Carbon::parse($a->hour_start)->translatedFormat('H:i') : ''}}</td>

                                @if ($a->duration_overtime != null)
                                <td style="color:white">{{ $a->hour_overtime_end != null ? \Carbon\Carbon::parse($a->hour_overtime_end)->translatedFormat('H:i') : ''}}</td>
                                @else
                                <td style="color:white">{{ $a->hour_end != null ? \Carbon\Carbon::parse($a->hour_end)->translatedFormat('H:i') : ''}}</td>
                                @endif
                               
                                <td style="color:white">{{$d_hour}} : {{$d_minute}}</td>
                                <td style="color:white"><br></td>
                                <td style="color:white">{{\floor($a->duration_rest/60)}}</td>
                                <td style="color:white">{{\floor((($a->duration_work + $a->duration_overtime) - $a->duration_rest)/60)}}</td>
                                <td style="color:white">{{\floor(((0 + $a->duration_overtime) - 0)/60)}}</td>
                                <td style="color:white">{{\floor($a->working_hour/60)}} </td>
                                <td style="color:white">{{$a->lembur_kali_satu_lima}}</td>
                                <td style="color:white">{{$a->lembur_kali_dua}}</td>
                                <td style="color:white">{{$a->lembur_kali_tiga}}</td>
                                <td style="color:white">{{$a->lembur_kali_empat}}</td>
                                <td style="color:white">
                                    @if ($a->is_final == 0)
                                    <a href="#" class="btn icon btn-primary edit_modal_attendance" data-href="{{\URL::to('/')}}/payroll/{{$a->id}}/edit_attendance" data-container=".attendance_modal"><i class="bi bi-pencil"></i></a>
                                    @endif
                                    
                                </td>
                            </tr>
                            
                            @else

                            <tr style="background-color: #white">
                                <td style="color:black"> {{$a->roster_status_initial}}</td>

                                <td style="color:black">{{round($tanggal->translatedFormat('d'))}}</td>
                                <td style="color:black">{{$tanggal->translatedFormat('l')}}</td>
                                <td style="color:black">{{ $a->hour_start != null ? \Carbon\Carbon::parse($a->hour_start)->translatedFormat('H:i') : ''}}</td>

                                @if ($a->duration_overtime != null)
                                <td style="color:black">{{ $a->hour_overtime_end != null ? \Carbon\Carbon::parse($a->hour_overtime_end)->translatedFormat('H:i') : ''}}</td>
                                @else
                                <td style="color:black">{{ $a->hour_end != null ? \Carbon\Carbon::parse($a->hour_end)->translatedFormat('H:i') : ''}}</td>
                                @endif
                               
                                <td style="color:black">{{$d_hour}} : {{$d_minute}}</td>
                                <td style="color:black"><br></td>
                                <td style="color:black">{{\floor($a->duration_rest/60)}}</td>
                                <td style="color:black">{{\floor((($a->duration_work + $a->duration_overtime) - $a->duration_rest)/60)}}</td>
                                <td style="color:black">{{\floor(((0 + $a->duration_overtime) - 0)/60)}}</td>
                                <td style="color:black">{{\floor($a->working_hour/60)}} </td>
                                <td style="color:black">{{$a->lembur_kali_satu_lima}}</td>
                                <td style="color:black">{{$a->lembur_kali_dua}}</td>
                                <td style="color:black">{{$a->lembur_kali_tiga}}</td>
                                <td style="color:black">{{$a->lembur_kali_empat}}</td>
                                <td style="color:black">
                                    @if ($a->is_final == 0)
                                    <a href="#" class="btn icon btn-primary edit_modal_attendance" data-href="{{\URL::to('/')}}/payroll/{{$a->id}}/edit_attendance" data-container=".attendance_modal"><i class="bi bi-pencil"></i></a>
                                    @endif
                                    
                                </td>
                            </tr>
                                
                            @endif
                      

                    
                    
                        @endforeach
                </table>
            </div>
        </div>

    </div>
</div>