<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceFingerspot;
use App\Models\AttendanceHasEmployee;
use App\Models\AttendancePayrol;
use App\Models\Employee;
use App\Models\Finger;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PayrollController extends Controller
{
    public function monthly()
    {
        $month = Carbon::now();
        $monthNow = $month->format("Y-m");
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $employees =  Employee::select('id', 'name')->get();

        $data = (object) [
            //
        ];

        return view("pages.payroll.monthly", compact(
            "data",
            "month",
            "employees",
            "monthNow",
        ));
    }

    public function fetchInformation()
    {
        $employeeId = request("employee_id");
        $month = Carbon::parse(request("month_filter", Carbon::now()));
        $monthNow = $month->format("Y-m");
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $employee =  Employee::findOrFail($employeeId);

        return response()->json([
            "employee" => $employee,
            "monthReadAble" => $monthReadAble,
        ]);
    }

    public function fetchSalary()
    {
        $employee_id = request("employee_id");

        $month_filter  = request('month_filter') . '-01';

        $data = Payroll::whereDate('bulan', $month_filter)->where('employee_id', $employee_id)->firstOrFail();


        // Payroll::whereDate('')
        // $month = Carbon::parse(request("month_filter", Carbon::now()));
        // $monthNow = $month->format("Y-m");
        // $monthReadAble = $month->isoFormat("MMMM YYYY");

        // $data = (object) [
        //     // A. Pendapatan
        //     "jumlah_gaji_dasar" => 1,
        //     "nominal_gaji_dasar" => "3.394.000",
        //     "jumlah_tunjangan_tetap" => 1,
        //     "nominal_tunjangan_tetap" => "-",
        //     "jumlah_uang_makan" => 10,
        //     "nominal_uang_makan" => "-",
        //     // total berapa jam lembur
        //     "jumlah_lembur" => 7,
        //     "nominal_lembur" => "137.326",
        //     "nominal_tambahan_lain_lain" => "130.538",
        //     "jumlah_pendapatan_kotor" => "3.661.864",
        //     // B. Pemotongan
        //     "nominal_bpjs_dibayar_karyawan" => "135.781",
        //     "nominal_pajak_penghasilan_pph21" => "-",
        //     "nominal_potongan_lain_lain" => "-",
        //     "jumlah_potongan" => "135.781",
        //     "gaji_bersih" => "3.526.083",
        // ];

        return response()->json([
            "data" => $data,
        ]);
    }

    public function fetchBpjs()
    {
        $employeeId = request("employee_id");
        $month = Carbon::parse(request("month_filter", Carbon::now()));
        $monthNow = $month->format("Y-m");
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $data = (object) [
            "dasar_upah_bpjs_tk" => "3.394.513",
            "dasar_upah_bpjs_kesehatan" => "3.394.513",
        ];

        $jaminanSosial = [
            (object) [
                "nama" => "Hari Tua (JHT)",
                "perusahaan_persen" => "3,70",
                "perusahaan_nominal" => "125.597",
                "karyawan_persen" => "2,00",
                "karyawan_nominal" => "67.890",
            ],
            (object) [
                "nama" => "Kecelakaan (JKK)",
                "perusahaan_persen" => "1,74",
                "perusahaan_nominal" => "59.065",
                "karyawan_persen" => "0,00",
                "karyawan_nominal" => "0",
            ],
            (object) [
                "nama" => "Kematian (JKM)",
                "perusahaan_persen" => "0,30",
                "perusahaan_nominal" => "10.184",
                "karyawan_persen" => "0,00",
                "karyawan_nominal" => "0",
            ],
            (object) [
                "nama" => "Pensiun (JP)",
                "perusahaan_persen" => "2,00",
                "perusahaan_nominal" => "67.890",
                "karyawan_persen" => "1,00",
                "karyawan_nominal" => "33.945",
            ],
            (object) [
                "nama" => "Kesehatan (BPJS)",
                "perusahaan_persen" => "4,00",
                "perusahaan_nominal" => "135.781",
                "karyawan_persen" => "1,00",
                "karyawan_nominal" => "33.945",
            ],
        ];

        return response()->json([
            "data" => $data,
            "jaminanSosial" => $jaminanSosial,
        ]);
    }

    public function fetchPph21()
    {
        $employeeId = request("employee_id");
        $month = Carbon::parse(request("month_filter", Carbon::now()));
        $monthNow = $month->format("Y-m");
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $data = (object) [
            // D. Penghasilan Kotor
            "gaji_kotor_potongan" => "3.661.864",
            "bpjs_dibayar_perusahaan" => "398.516",
            "total_penghasilan_kotor" => "4.060.380",
            // E. Pengurangan
            "biaya_jabatan" => "203.019",
            "bpjs_dibayar_karyawan" => "135.781",
            "jumlah_pengurangan" => "338.800",
            // F. Gaji Bersih 12 Bulan
            "gaji_bersih_setahun" => "44.658.964",
            // G. PKP 12 Bulan= (F)-PTKP
            "pkp_setahun" => "44.658.964",
        ];

        // $table = [
        //     (object) [
        //         "tarif" => "5",
        //         "dari_pkp" => "0",
        //         "ke_pkp" => "50",
        //         "progressive_pph21" => "2.232.948",
        //     ],
        //     (object) [
        //         "tarif" => "15",
        //         "dari_pkp" => "50",
        //         "ke_pkp" => "250",
        //         "progressive_pph21" => "-",
        //     ],
        //     (object) [
        //         "tarif" => "25",
        //         "dari_pkp" => "250",
        //         "ke_pkp" => "500",
        //         "progressive_pph21" => "-",
        //     ],
        //     (object) [
        //         "tarif" => "30",
        //         "dari_pkp" => "500",
        //         "ke_pkp" => "1.000",
        //         "progressive_pph21" => "-",
        //     ],
        // ];

        return response()->json([
            "data" => $data,
            // "table" => $table,
        ]);
    }

    function attendance()
    {
        // return request()->all();

        $employee_id = request()->get('employee_id') ?? '';

        $employee = Employee::findOrFail($employee_id);
        $month_filter = request()->get('month_filter') ?? '';

        $end_date = Carbon::parse($month_filter . '-25')->format('Y-m-d');
        $start_date = Carbon::parse($month_filter . '-26')->addMonth(-1)->format('Y-m-d');

        // return [$start_date,$end_date];

        // 
        $attende_fingers = AttendanceHasEmployee::where('employee_id', $employee_id)
            ->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // return $sql = Str::replaceArray('?', $attende_fingers->getBindings(), $attende_fingers->toSql());

        foreach ($attende_fingers as $key => $v) {
            $new_at  = AttendancePayrol::firstOrCreate([
                'employee_id' => $employee_id,
                'date' => $v->date,
            ]);


            $fingers =  AttendanceFingerspot::whereIn('pin', function ($q)use($employee_id ) {
                $q->select('id_finger')
                    ->from(with(new Finger())->getTable())
                    ->where('employee_id',$employee_id);
            })
            ->whereDate('scan_date',$v->date)
            ->get();

            $jam_kerja_masuk = null;
            $jam_kerja_keluar = null;

            foreach ($fingers as $key => $f) {
                # code...

                $nxx = Carbon::parse($f->scan_date)->format('H');
                if($nxx > 6 && $nxx < 11){
                    $jam_kerja_masuk = Carbon::parse($f->scan_date)->format('H:i');
                }


                if($nxx > 10 && $nxx < 19){
                    $jam_kerja_keluar = Carbon::parse($f->scan_date)->format('H:i');
                }


            }


            if ($new_at->is_koreksi == 0) {

                //hitung jam.a
                $hour_lembur_x = $new_at->duration_overtime % 60;
                $hour_lembur_y =  \floor($new_at->duration_overtime / 60);
                $jumlah_jam_lembur_tmp = 0;
                $kali_1 = 0;
                $kali_2 = 0;
                $kali_3 = 0;
                $kali_4 = 0;



                for ($i = 1; $i <= $hour_lembur_y; $i++) {
                    if ($i == 1) {
                        $jumlah_jam_lembur_tmp += 1.5;
                        $kali_1 += 1.5;
                    } elseif ($i > 1 && $i < 8) {
                        $jumlah_jam_lembur_tmp += 2.00;
                        $kali_2 += 2.00;
                    } elseif ($i == 8) {
                        $jumlah_jam_lembur_tmp += 3.00;
                        $kali_3 += 3.00;
                    } elseif ($i > 8) {
                        $jumlah_jam_lembur_tmp += 4.00;
                        $kali_4 += 4.00;
                    }
                }

                if (($hour_lembur_x > 29) && ($hour_lembur_x < 45) && ($jumlah_jam_lembur_tmp == 0)) {
                    $jumlah_jam_lembur_tmp += 1.5 * 0.5;
                    $kali_1 += 1.5 * 0.5;
                }

                if (($hour_lembur_x >= 45) && ($jumlah_jam_lembur_tmp == 0)) {
                    $jumlah_jam_lembur_tmp += 1.5;
                    $kali_1 += 1.5;
                }


                // if (($hour_lembur_x > 29) && ($hour_lembur_x < 45) && ($jumlah_jam_lembur_tmp > 0)) {
                //     $jumlah_jam_lembur_tmp += 2 * 0.5;
                //     $kali_2 += 2 * 0.5;
                // }

                // if (($hour_lembur_x >= 45) && ($jumlah_jam_lembur_tmp > 0)) {
                //     $jumlah_jam_lembur_tmp += 2.00;
                //     $kali_2 += 2.00;
                // }




                $new_at->update([
                    'hour_start' => $v->hour_start,
                    'hour_end' => $v->hour_end,
                    'duration_work' => $v->duration_work,

                    'hour_rest_start' => $v->hour_rest_start,
                    'hour_rest_end' => $v->hour_rest_end,
                    'duration_rest' => $v->duration_rest,

                    'hour_overtime_start' => $v->hour_overtime_start,
                    'hour_overtime_end' => $v->hour_overtime_end,
                    'duration_overtime' => $v->duration_overtime,


                    'lembur_kali_satu_lima' => $kali_1,
                    'lembur_kali_dua' => $kali_2,
                    'lembur_kali_tiga' => $kali_3,
                    'lembur_kali_empat' => $kali_4,
                ]);
            }
        }

        // Str
        // $sql = \str_replace_array('?', $query->getBindings(), $query->toSql());
        // Str

        // for laravel 5.8^
        //  return $sql = Str::replaceArray('?', $query->getBindings(), $query->toSql());
        // \dd($sql);


        $attendance = AttendancePayrol::where('employee_id', $employee_id)
            ->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->orderBy('date','asc')
            ->get();

        $data = compact('attendance', 'employee');

        return view("pages.payroll.partials.attendance_ajax", $data);
    }

    function edit_attendance($id)
    {
        $attendance = AttendancePayrol::with('employee')->findOrFail($id);

        $data = compact('attendance');
        return view("pages.payroll.partials.edit_attendance_ajax", $data);
    }


    function update_attendance($id)
    {
        // DB
        // DB::enableQueryLog();
        $attendance = AttendancePayrol::findOrFail($id);

        if (request()->ajax()) {
            try {

                // return [
                //     'hour_start'=>\explode(':',request()->get('hour_start'))[0],
                //     'hour_end'=>\explode(':',request()->get('hour_end'))[0],
                // ];

                $total_minutes_start = (\explode(':', request()->get('hour_start'))[0] * 60) + \explode(':', request()->get('hour_start'))[1];
                $total_minutes_end = (\explode(':', request()->get('hour_end'))[0] * 60) + \explode(':', request()->get('hour_end'))[1];


                // $time_start = Carbon::createFromTimeString(request()->get('hour_start').":00");
                // $start_of_day_start = Carbon::createFromTimeString(request()->get('hour_start').":00")->startOfDay();
                // $total_minutes_start = $time_start->diffInMinutes($start_of_day_start);

                // $time_end = Carbon::createFromTimeString(request()->get('hour_end').":00");
                // $end_of_day_end = Carbon::createFromTimeString(request()->get('hour_end').":00")->endOfDay();
                // $total_minutes_end = $time_end->diffInMinutes($end_of_day_end);

                if ($total_minutes_end <= $total_minutes_start) {
                    $new_hour_end = Carbon::parse($attendance->date)->addDays(1)->format('Y-m-d') . " " . request()->get('hour_end') . ":00";
                } else {
                    $new_hour_end = Carbon::parse($attendance->date)->format('Y-m-d') . " " . request()->get('hour_end') . ":00";

                    // $time_duration = Carbon::createFromTimeString($attendance->date." ".request()->get('hour_duration').":00");
                    // $end_of_day_duration = Carbon::createFromTimeString($new_hour_end);
                    // $total_minutes_duration = $time_duration->diffInMinutes($end_of_day_duration);
                }

                $time_duration = Carbon::createFromTimeString($attendance->date . " " . request()->get('hour_start') . ":00");
                $end_of_day_duration = Carbon::createFromTimeString($new_hour_end);
                $total_minutes_duration = $time_duration->diffInMinutes($end_of_day_duration);



                if (request()->get('edit_jam_lembur') == 'iya') {

                    $attendance->update([
                        'lembur_kali_satu_lima' => request()->get('lembur_kali_satu_lima'),
                        'lembur_kali_dua' => request()->get('lembur_kali_dua'),
                        'lembur_kali_tiga' => request()->get('lembur_kali_tiga'),
                        'lembur_kali_empat' => request()->get('lembur_kali_empat'),
                        'is_koreksi_lembur' => 1,
                    ]);
                }


                $attendance->update([
                    'hour_start' => Carbon::parse($attendance->date)->format('Y-m-d') . " " . request()->get('hour_start') . ":00",
                    'hour_end' => $new_hour_end,
                    'duration_work' => $total_minutes_duration,

                    'keterangan' => request()->get('keterangan'),
                    'is_koreksi' => 1,
                ]);

                //    $data = [
                //     'hour_start'=>Carbon::parse($attendance->date)->format('Y-m-d')." ".request()->get('hour_start').":00",
                //         'hour_end'=>$new_hour_end,

                //         'lembur_kali_satu_lima'=>request()->get('lembur_kali_satu_lima'),
                //         'lembur_kali_dua'=>request()->get('lembur_kali_dua'),
                //         'lembur_kali_tiga'=>request()->get('lembur_kali_tiga'),
                //         'lembur_kali_empat'=>request()->get('lembur_kali_empat'),

                //         'keterangan'=>request()->get('keterangan'),
                //    ];

                //    return dd($attendance);

                $output = ['success' => true, 'msg' => 'Berhasil Edit Absensi', 'data' => [$total_minutes_end, $total_minutes_start]];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $output = ['success' => false, 'msg' => 'Gagal Edit Absensi', 'error' => [$e->getLine(), $e->getMessage(), $e->getTrace()]];
            }

            return $output;
        }


        // $data = compact('attendance');
        // return view("pages.payroll.partials.edit_attendance_ajax", $data);
    }
}
