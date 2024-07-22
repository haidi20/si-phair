<?php

namespace App\Http\Controllers;

use App\Exports\PayrollExportPerEmployee;
use App\Models\Attendance;
use App\Models\AttendanceHasEmployee;
use App\Models\AttendancePayrol;
use App\Models\BaseWagesBpjs;
use App\Models\BpjsCalculation;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PeriodPayroll;
use App\Models\RosterDaily;
use App\Models\salaryAdjustment;
use App\Models\salaryAdjustmentDetail;
use App\Models\SalaryAdvance;
use App\Models\SalaryAdvanceDetail;
use App\Models\Vacation;
use Carbon\CarbonPeriod;
// use Database\Seeders\;
use App\Models\JobStatusHasParent;
use App\Models\TanggalMerah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;

class PayslipController extends Controller
{
    function index()
    {


        $employees = Employee::select('id', 'name', 'position_id')->get();
        $compact = compact('employees');
        return view("pages.payroll.pay_slip.index", $compact);
    }


    function store(Request $request)
    {


        $bulan =  $request->bulan;
        $tahun =  $request->tahun;
        $employee_id =  $request->employee_id;

        // return [$bulan,$tahun,$employee_id];

        $employee_real_data = Employee::findOrFail($employee_id);

        // ;

        // return Employee::get()->where('company_id', '2')->values();

        // $employee_real_data->enter_date;





        // $period_payroll->date_start = $this->period_payrol_month_year->date_start;
        //         $period_payroll->date_end = $this->period_payrol_month_year->date_end;


        // $payroll = Payroll::where('employee_id',$employee_id)->where('period_payroll_id',$period_payroll->id)
        // ->get();

        try {
            DB::beginTransaction();


            if (($employee_real_data->employee_type_id == 1) or ($employee_real_data->employee_type_id == 2)) {
                $period_payroll = PeriodPayroll::whereMonth('period', $bulan)->whereYear('period', $tahun)->first();

                if (!isset($period_payroll->id)) {
                    $period_payroll = PeriodPayroll::create([
                        'period' => $tahun . "-" . $bulan . "-01",
                        'date_start' => Carbon::parse($tahun . "-" . $bulan . "-01")->addMonth(-1)->format('Y-m-') . "26",
                        'date_end' => $tahun . "-" . $bulan . "-25",
                    ]);
                }

                /////bersih bersih absen
                Attendance::whereDate('attendance.date', '>=', $period_payroll->date_start)
                    ->whereDate('attendance.date', '<=', $period_payroll->date_end)
                    ->where('employee_id', $employee_id)
                    ->update([
                        'jam_masuk_real' => null,
                        'jam_pulang_real' => null,
                        'jam_istirahat_keluar_real' => null,
                        'jam_istirahat_masuk_real' => null,
                        'jam_pulang_lembur' => null,
                        'jam_mulai_lembur' => null,
                        'duration_work' => null,
                        'hour_start' => null,
                        'hour_end' => null,
                        'cloud_id' => null,
                        'pin' => null,


                        'duration_overtime' => null,
                        'hour_overtime_start' => null,
                        'hour_overtime_end' => null,

                        'lembur_kali_satu_lima'=>null,
                        'lembur_kali_dua'=>null,
                        'lembur_kali_tiga'=>null,
                        'lembur_kali_empat'=>null,

                    ]);



                $dtn = Carbon::parse($period_payroll->date_start);
                $dtn_enter_date = Carbon::parse($employee_real_data->enter_date);

                if ($dtn->lessThan($dtn_enter_date)) {
                    $period_payroll->date_start = $employee_real_data->enter_date;
                }








                //isi apa aja dulu
                // $query_where = ' AND e.id="' . $employee_id . '" ';

                // return $employee_real_data;

                $tanggals = [];

                foreach ($employee_real_data->data_finger as $key => $df) {
                    // return $df;

                    $hadirs = DB::table('attendance_fingerspots')
                        ->select(
                            'attendance_fingerspots.*',
                            DB::raw('DATE(attendance_fingerspots.scan_date) as tanggal'),
                            DB::raw('TIME(attendance_fingerspots.scan_date) as jam'),
                        )
                        // ->whereBetween('attendance_fingerspots.scan_date', [$period_payroll->date_start, $period_payroll->date_end])
                        ->whereDate('attendance_fingerspots.scan_date', '>=', $period_payroll->date_start)
                        ->whereDate('attendance_fingerspots.scan_date', '<=', $period_payroll->date_end)
                        ->where('attendance_fingerspots.pin', $df->pin)
                        ->where('attendance_fingerspots.cloud_id', $df->finger_tool->cloud_id)
                        ->where('attendance_fingerspots.status_scan', '<', 4)
                        ->orderBy('attendance_fingerspots.scan_date', 'asc')
                        ->get();



                    // return $hadirs->groupBy('tanggal')->map( function($q){
                    //     return $q->groupBy('scan_status');
                    // });
                    foreach ($hadirs as $key => $hadir) {
                        $tanggals[] = $hadir->tanggal;

                        if ($hadir->status_scan == 0) {
                            $start = Carbon::createFromTimeString('16:30:00');
                            $end = Carbon::createFromTimeString('19:00:00')->addDay();

                            $time_now = Carbon::createFromTimeString($hadir->jam);

                            if ($time_now->between($start, $end)) {
                                $hadir->status_scan = 1;
                            }
                        }




                        $new_attendance_lost = Attendance::firstOrCreate([
                            'employee_id' => $employee_id,
                            'date' => $hadir->tanggal,
                        ]);


                        if ($hadir->status_scan == 0) {
                            //jam masuk
                            $new_attendance_lost->update([
                                'jam_masuk_real' => $hadir->jam,
                                'hour_start' => $hadir->jam,
                            ]);
                        } elseif ($hadir->status_scan == 1) {
                            $new_attendance_lost->update([
                                'jam_pulang_real' => $hadir->jam
                            ]);
                        } elseif ($hadir->status_scan == 2) {
                            $new_attendance_lost->update([
                                'jam_istirahat_keluar_real' => $hadir->jam
                            ]);
                        } elseif ($hadir->status_scan == 3) {
                            $new_attendance_lost->update([
                                'jam_istirahat_masuk_real' => $hadir->jam
                            ]);
                        }
                    }
                }




                // return 'SELECT TIME(af.scan_date) AS jam, DATE(af.scan_date) AS tanggal, af.scan_date, af.status_scan, af.pin, f.employee_id FROM employees AS e, attendance_fingerspots AS af, fingers AS f
                // WHERE e.id=f.employee_id
                // AND f.id_finger=af.pin
                // AND (DATE(af.scan_date) BETWEEN "' . $period_payroll->date_start . '" AND "' . $period_payroll->date_end . '")
                // AND af.status_scan < 4
                // GROUP BY e.id, af.status_scan
                // ORDER BY f.employee_id ASC , af.status_scan asc, af.scan_date ASC';

                // $tanggals =  \collect($datas_real)->groupBy('tanggal')->keys();

                $jumlah_hari_kerja_real_absen =  count(array_values(array_unique($tanggals)));
                $new_datax = [];

                // return array_values(array_unique($tanggals));
                $masuk_tanggal_merah =  TanggalMerah::whereIn('tanggal', array_values(array_unique($tanggals)))->get();

                // $employee_real_data->basic_salary/26

                foreach ($masuk_tanggal_merah as $key => $xx) {

                    salaryAdjustmentDetail::where('employee_id', $employee_id)
                        ->where('is_tanggal_merah', 1)
                        ->where('tanggal_merah', $xx->tanggal)
                        ->delete();


                    $new_SA = salaryAdjustment::firstOrCreate([
                        'employee_base' => 'choose_employee',
                        'name' => 'HARI KERJA +1',
                        'type_time' => 'base_time',
                        'is_month_end' => 0,
                        'type_amount' => 'percent',
                        'amount' => 3.846153846,
                        'type_adjustment' => 'addition',
                        'note' => $xx->keterangan,
                        'month_start' => $period_payroll->date_end,
                        'is_thr' => 0,
                        'type_incentive' => 'incentive',
                    ]);

                    salaryAdjustmentDetail::create([
                        'salary_adjustment_id' => $new_SA->id,
                        'employee_id' => $employee_id,
                        'type_amount' => 'percent',
                        'amount' => 3.846153846,
                        'type_time' => 'base_time',
                        'month_start' => $period_payroll->date_end,
                        'type_incentive' => 'another',
                        'tanggal_merah' => $xx->tanggal,
                        'is_tanggal_merah' => 1,
                    ]);
                }




                // return ;
                foreach ($tanggals as $key => $tanggal) {
                    $dtx = Carbon::parse($tanggal)->addDays(1)->format('Y-m-d');

                    $new_x = DB::table('attendance_fingerspots')
                        ->select(
                            'attendance_fingerspots.*',
                            DB::raw('DATE(attendance_fingerspots.scan_date) as tanggal'),
                            DB::raw('TIME(attendance_fingerspots.scan_date) as jam'),
                        )
                        // ->whereNull('attendance_fingerspots.deleted_at')
                        ->whereBetween('attendance_fingerspots.scan_date', [$tanggal . " 16:00:00 ", $dtx . " 09:00:00"])
                        // ->whereDate('attendance_fingerspots.scan_date','>=',$period_payroll->date_start)
                        // ->whereDate('attendance_fingerspots.scan_date','<=',$period_payroll->date_end)
                        ->where('attendance_fingerspots.pin', $df->pin)
                        ->where('attendance_fingerspots.cloud_id', $df->finger_tool->cloud_id)
                        ->where('attendance_fingerspots.status_scan', '>=', 4)
                        ->orderBy('attendance_fingerspots.scan_date', 'asc')
                        ->get();



                    // $new_x = collect(DB::select('SELECT TIME(MIN(af.scan_date)) AS jam, DATE(af.scan_date) AS tanggal, af.scan_date, af.status_scan, af.pin, f.employee_id 
                    //  FROM employees AS e, attendance_fingerspots AS af, fingers AS f
                    //  WHERE e.id=f.employee_id
                    //  AND f.id_finger=af.pin
                    //  AND (af.scan_date BETWEEN "' . $tanggal . ' 17:00:00" AND "' . $dtx . ' 09:00:00")
                    //  AND af.status_scan > 3 ' . $query_where . '
                    //  GROUP BY DATE(af.scan_date),e.id,af.status_scan 
                    //  ORDER BY f.employee_id ASC , af.status_scan asc, af.scan_date ASC'));

                    ////////////////////////////////////////////////////////////////////////


                    if ($new_x->count() > 0) {
                        // return $new_x;
                        foreach ($new_x as $key => $x) {
                            $x->tanggal_attendance = $tanggal;


                            $new_attendance_lost = Attendance::firstOrCreate([
                                'employee_id' => $employee_id,
                                'date' => $tanggal,
                            ]);

                            if ($x->status_scan == 5) {
                                $new_attendance_lost->update([
                                    'jam_pulang_lembur' => $x->jam
                                ]);
                            } elseif ($x->status_scan == 4) {
                                $new_attendance_lost->update([
                                    'jam_mulai_lembur' => $x->jam
                                ]);
                            }

                            $js = JobStatusHasParent::where('employee_id', $employee_id)
                                ->whereDate('datetime_start', $tanggal)
                                ->where('status', 'overtime')
                                ->get();

                            foreach ($js as $key => $j) {
                                $new_attendance_lost->update([
                                    'is_nonstop' => $j->is_overtime_rest
                                ]);
                            }
                        }
                    }
                }

                // return $new_datax;

                // foreach ($datas_real as $key => $d) {
                //     $new_attendance_lost = Attendance::firstOrCreate([
                //         'employee_id' => $d->employee_id,
                //         'date' => $d->tanggal,
                //     ]);


                //     if ($d->status_scan == 0) {
                //         //jam masuk
                //         $new_attendance_lost->update([
                //             'jam_masuk_real' => $d->jam
                //         ]);
                //     } elseif ($d->status_scan == 1) {
                //         $new_attendance_lost->update([
                //             'jam_pulang_real' => $d->jam_pulang
                //         ]);
                //     } elseif ($d->status_scan == 2) {
                //         $new_attendance_lost->update([
                //             'jam_istirahat_keluar_real' => $d->jam
                //         ]);
                //     } elseif ($d->status_scan == 3) {
                //         $new_attendance_lost->update([
                //             'jam_istirahat_masuk_real' => $d->jam
                //         ]);
                //     }
                // }


                //isi data lembur terlebih dahulu
                $js = JobStatusHasParent::select(
                    'job_status_has_parents.*',
                    DB::raw('DATE(job_status_has_parents.datetime_start) as tanggal')

                )
                    ->whereNotNull('employee_id')
                    ->whereNotNull('datetime_end')
                    ->whereNull('deleted_at')
                    ->whereDate('datetime_start', '>=', $period_payroll->date_start)
                    ->whereDate('datetime_start', '<=', $period_payroll->date_end)
                    ->where('status', 'overtime')
                    ->where('employee_id', $employee_id)
                    ->get();

                // ;

                $tmp_grpby =  $js->groupBy('tanggal');
                foreach ($tmp_grpby as $key => $v) {

                    $tanggal_tmp = $key;
                    # code...
                    // return $key;

                    // if($key == "2023-08-11"){
                    //     return $v;
                    // }


                    $duration_overtime_last = 0;
                    foreach ($v as $key => $vv) {
                        $start_carbon = Carbon::parse($vv->datetime_start);
                        $end_carbon = Carbon::parse($vv->datetime_end);

                        // if($tanggal_tmp == "2023-08-17"){
                        //     return [$vv->datetime_start,$vv->datetime_end];
                        // }



                        $duration_overtime = $start_carbon->diffInMinutes($end_carbon);

                        // if($tanggal_tmp == "2023-08-22"){
                        //     return [$vv->datetime_start,$vv->datetime_end,$duration_overtime];
                        // }



                        if ($vv->is_overtime_rest) {
                            $duration_overtime  = $duration_overtime - 60;
                        }

                        $duration_overtime_last = $duration_overtime_last + $duration_overtime;
                    }

                    Attendance::where('employee_id', $vv->employee_id)
                        ->whereDate('date', Carbon::parse($vv->datetime_start)->format('Y-m-d'))
                        ->update([
                            'hour_overtime_start' => $vv->datetime_start,
                            'hour_overtime_end' => $vv->datetime_end,
                            'duration_overtime' => $duration_overtime_last
                        ]);
                }

                // return $tmp_grpby;


                // foreach ($js as $key => $s) {



                //     // if($s->employee_id == 6){
                //     //     return "nemu fin";
                //     // }



                // }




                // if ($period_payroll->is_final == 1) {
                //     if ($this->period_payrol_month_year != null) {
                //         Log::error('sudah ada');
                //         print("Sudah Ada");
                //         return false;
                //     } else {
                //         Log::error('sudah ada');
                //         print("Sudah Ada");
                //         return false;
                //     }
                // }
                // $period_payroll->save();


                // AttendancePayrol::whereDate('date','>=',$period_payroll->date_start)
                // ->whereDate('date','<=',$period_payroll->date_end)
                // ->get();
                // AttendancePayrol::create();

                // if ($message == 'ditambahkan') {
                //     DB::select("
                //     INSERT INTO attendance(pin, cloud_id,employee_id,date,hour_start,hour_end,duration_work,hour_rest_start,hour_rest_end,duration_rest,hour_overtime_start,hour_overtime_end,duration_overtime,hour_overtime_job_order_start,hour_overtime_job_order_end,duration_overtime_job_order,is_weekend,is_vacation,is_payroll_use,payroll_id)
                //     SELECT pin, cloud_id,employee_id,date,hour_start,hour_end,duration_work,hour_rest_start,hour_rest_end,duration_rest,hour_overtime_start,hour_overtime_end,duration_overtime,hour_overtime_job_order_start,hour_overtime_job_order_end,duration_overtime_job_order,is_weekend,is_vacation,is_payroll_use,payroll_id
                //     FROM attendance_has_employees where date(date) >= '" . $period_payroll->date_start . "' AND date(date) <= '" . $period_payroll->date_end . "'
                //     ");
                // }











                $employees = Employee::whereIn('id', [$employee_id])->orderBy('name', 'asc')->get();

                $bpjs_jht = BpjsCalculation::where('code', 'jht')->first();
                $bpjs_jkk = BpjsCalculation::where('code', 'jkk')->first();
                $bpjs_jkm = BpjsCalculation::where('code', 'jkm')->first();
                $bpjs_jp = BpjsCalculation::where('code', 'jp')->first();
                $bpjs_kes = BpjsCalculation::where('code', 'kes')->first();

                ///////////////////

                $bpjs_jht->company_percent = $employee_real_data->bpjs_jht_company_percent;
                $bpjs_jht->employee_percent = $employee_real_data->bpjs_jht_employee_percent;

                $bpjs_jkk->company_percent = $employee_real_data->bpjs_jkk_company_percent;
                $bpjs_jkk->employee_percent = $employee_real_data->bpjs_jkk_employee_percent;

                $bpjs_jkm->company_percent = $employee_real_data->bpjs_jkm_company_percent;
                $bpjs_jkm->employee_percent = $employee_real_data->bpjs_jkm_employee_percent;

                $bpjs_jp->company_percent = $employee_real_data->bpjs_jp_company_percent;
                $bpjs_jp->employee_percent = $employee_real_data->bpjs_jp_employee_percent;

                $bpjs_kes->company_percent = $employee_real_data->bpjs_kes_company_percent;
                $bpjs_kes->employee_percent = $employee_real_data->bpjs_kes_employee_percent;

                // return [$employee_real_data->bpjs_kes_company_percent, $employee_real_data->bpjs_kes_employee_percent];






                $bpjs_dasar_updah_bpjs_tk = $employee_real_data->bpjs_dasar_updah_bpjs_tk ?? 0;
                $dasar_updah_bpjs_kes = $employee_real_data->dasar_updah_bpjs_kes ?? 0;

                // print("\n\n\n FFFFFFFFFFFFFFFFFFFFF \n");


                $tanggal_tambahan_lain =  Carbon::parse($period_payroll->period . "-30");

                // return [$period_payroll->date_start,$period_payroll->date_end];

                $period = CarbonPeriod::create($period_payroll->date_start, $period_payroll->date_end);

                // print("masuk sini -----------------");
                foreach ($employees as $key => $employee) {
                    $employee_id = $employee->id;
                    $start_date = $period_payroll->date_start;
                    $end_date =  $period_payroll->date_end;

                    $new_payroll = Payroll::firstOrCreate([
                        'employee_id' => $employee->id,
                        'period_payroll_id' => $period_payroll->id,
                    ]);



                    // AttendanceHasEmployee

                    // return [$start_date,$end_date];
                    $attende_fingers = AttendanceHasEmployee::where('employee_id', $employee_id)
                        ->whereDate('date', '>=', $start_date)
                        ->whereDate('date', '<=', $end_date)
                        // ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();

                    // return $sql = Str::replaceArray('?', $attende_fingers->getBindings(), $attende_fingers->toSql());

                    foreach ($attende_fingers as $key => $v) {
                        $new_at  = Attendance::firstOrCreate([
                            'employee_id' => $employee_id,
                            'date' => $v->date,
                        ]);

                        if ($new_at->is_koreksi == 0) {
                            $new_at->update([
                                'hour_start' => $v->hour_start,
                                'hour_end' => $v->hour_end,
                                'duration_work' => $v->duration_work,

                                'hour_rest_start' => $v->hour_rest_start,
                                'hour_rest_end' => $v->hour_rest_end,
                                'duration_rest' => $v->duration_rest,

                                // 'hour_overtime_start' => $v->hour_overtime_start,
                                // 'hour_overtime_end' => $v->hour_overtime_end,
                                // 'duration_overtime' => $v->duration_overtime,
                            ]);
                        }


                        //validasi lembur
                    }



                    $data_absens = Attendance::where('employee_id', $employee->id)
                        ->whereDate('date', '>=', $period_payroll->date_start)
                        ->whereDate('date', '<=', $period_payroll->date_end)
                        ->get();

                    // print("-----------");
                    // print_r(json_encode($data_absens->pluck('date')));
                    // print("-----------");

                    $jumlah_jam_lembur_tmp = 0;
                    $jumlah_hari_kerja_tmp = 0;
                    $jumlah_hari_tidak_masuk_tmp = 0;


                    $jumlah_hutang  = 0;
                    $tanggal_apa_aja  = [];


                    // print("MASUK PERIODCONTROLER -----xxxxx");

                    // return [$period_payroll->date_start, $period_payroll->date_end];


                    //masalah lagi hutang ini, secepatnya di perbaiki
                    $jumlah_hutang =  SalaryAdvanceDetail::whereDate('salary_advance_details.date_start', '<=', $period_payroll->date_end)
                        // ->whereDate('salary_advance_details.date_end', '<=', $period_payroll->date_end)
                        ->where('employee_id', $employee_id)
                        ->whereDate('date_end', '>=', $period_payroll->date_end)
                        ->sum('amount');

                    //////////////////////////////////////////
                    /////// DETAI PAYROL TENTANG HUTANG////////
                    ///////////////////////////////////////////  

                    // $iterxxxxxxxxxxxxx=1;
                    foreach ($period as $key => $p) {
                        // $iterxxxxxxxxxxxxx++;
                        $new_old_d = $data_absens->where('date', $p->format('Y-m-d'))->first();
                        if ($new_old_d != null) {
                            // print_r(json_encode($data_absens->pluck('date')));
                        }

                        // if( == null){
                        //     return $new_old_d;
                        // }


                        $roster_daily = RosterDaily::where('employee_id', $employee->id)
                            ->whereDate('date', $p->format('Y-m-d'))
                            ->first();

                        Attendance::where('employee_id', $employee->id)
                            ->whereDate('date', $p->format('Y-m-d'))->update([
                                'roster_daily_id' => $roster_daily->id ?? null,
                                'roster_status_initial' => $roster_daily->roster_status_initial ?? null
                            ]);



                        //  if( $p->format('Y-m-d')=='2023-06-19'){
                        //     return $new_old_d;
                        //  }
                        $kali_1 = 0.00;
                        $kali_2 = 0.00;
                        $kali_3 = 0.00;
                        $kali_4 = 0.00;



                        if (isset($new_old_d->id) && ($new_old_d->hour_start != null)) {
                            // return $p->format('Y-m-d');
                            $old_sekali_at = Attendance::findOrFail($new_old_d->id);
                            // print("\t\tOVERTIME : ".$old_sekali_at->duration_overtime)."\n";
                            $jumlah_hari_kerja_tmp += 1;
                            $tanggal_apa_aja[] = $p->format('Y-m-d');
                            if (($old_sekali_at->duration_overtime != null) && ($old_sekali_at->duration_overtime > 0)) {

                                // if($p->format('Y-m-d') == "2023-08-22"){
                                //     return [$vv->datetime_start,$vv->datetime_end,$duration_overtime];
                                // }



                                if ($old_sekali_at->is_koreksi_lembur == 0) {
                                    $hour_lembur_x = $old_sekali_at->duration_overtime % 60;
                                    $hour_lembur_y =  \floor($old_sekali_at->duration_overtime / 60);



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


                                    if (($hour_lembur_x > 29) && ($hour_lembur_x < 45) && ($jumlah_jam_lembur_tmp > 0)) {
                                        $jumlah_jam_lembur_tmp += 2 * 0.5;
                                        $kali_2 += 2 * 0.5;
                                    }

                                    if (($hour_lembur_x >= 45) && ($jumlah_jam_lembur_tmp > 0)) {
                                        $jumlah_jam_lembur_tmp += 2.00;
                                        $kali_2 += 2.00;
                                    }

                                    Attendance::where('id', $new_old_d->id)->update([
                                        'lembur_kali_satu_lima' => $kali_1,
                                        'lembur_kali_dua' => $kali_2,
                                        'lembur_kali_tiga' => $kali_3,
                                        'lembur_kali_empat' => $kali_4,
                                        'roster_daily_id' => $roster_daily->id ?? null,
                                        'roster_status_initial' => $roster_daily->roster_status_initial ?? null
                                    ]);
                                } else {
                                    $jumlah_jam_lembur_tmp = $new_old_d->id->lembur_kali_satu_lima + $new_old_d->id->lembur_kali_dua + $new_old_d->id->lembur_kali_tiga + $new_old_d->id->lembur_kali_empat;
                                }
                            }

                            // print_r([
                            //     'lembur_kali_satu_lima' => $kali_1,
                            //     'lembur_kali_dua' => $kali_2,
                            //     'lembur_kali_tiga' => $kali_3,
                            //     'lembur_kali_empat' => $kali_4,
                            //     'roster_daily_id' => $roster_daily->id ?? null,
                            //     'roster_status_initial' => $roster_daily->roster_status_initial ?? null
                            // ]);
                        } else {

                            // return "A";
                            // return ;

                            // if($p->format('Y-m-d') == "2023-07-26"){

                            // }elseif($p->format('Y-m-d') == "2023-07-27") {
                            // }elseif($p->format('Y-m-d') == "2023-07-28") {
                            // }elseif($p->format('Y-m-d') == "2023-07-29") {
                            // }elseif($p->format('Y-m-d') == "2023-07-30") {


                            // }   else{
                            //     return $new_old_d;
                            // }

                            $is_weekend = 0;
                            $is_vacation = 0;
                            $is_absen = 0;

                            if (isset($roster_daily->id)) {
                                if ($roster_daily->roster_status_initial == 'M') {

                                    // return "M";


                                    $vacations = Vacation::where('employee_id', $employee->id)
                                        ->whereYear('date_start', Carbon::now()->format('Y'))
                                        ->where('status', 'accept')
                                        ->select(
                                            DB::raw('DATEDIFF(date_end, date_start) AS jumlah_hari_cuti')
                                        )
                                        ->get();

                                    $jumlah_hari_cuti = 0;

                                    foreach ($vacations as $key => $v) {
                                        $jumlah_hari_cuti += $v->jumlah_hari_cuti;
                                    }

                                    // return $jumlah_hari_cuti + 1;
                                    // return $jumlah_hari_cuti;

                                    if (($jumlah_hari_cuti + 1) > $employee->day_vacation) {

                                        // return "off fin";
                                        $jumlah_hari_tidak_masuk_tmp += 1;
                                        $is_absen = 1;

                                        salaryAdjustmentDetail::where('employee_id', $employee_id)
                                            ->where('is_absen_potong_gaji', 1)
                                            ->where('tanggal_absen_potong_gaji', $p->format('Y-m-d'))
                                            ->delete();

                                        $new_SA = salaryAdjustment::firstOrCreate([
                                            'employee_base' => 'choose_employee',
                                            'name' => 'HARI KERJA -1 ' . $p->format('d-m-Y'),
                                            'type_time' => 'base_time',
                                            'is_month_end' => 0,
                                            'type_amount' => 'percent',
                                            'amount' => 3.846153846,
                                            'type_adjustment' => 'deduction',
                                            'note' => 'Potong Gaji Tidak Hadir Tanggal ' . $p->format('d-m-Y'),
                                            'month_start' => $period_payroll->date_end,
                                            'is_thr' => 0,
                                            'type_incentive' => 'deduction',
                                        ]);

                                        salaryAdjustmentDetail::create([
                                            'salary_adjustment_id' => $new_SA->id,
                                            'employee_id' => $employee_id,
                                            'type_amount' => 'percent',
                                            'amount' => 3.846153846,
                                            'type_time' => 'base_time',
                                            'month_start' => $period_payroll->date_end,
                                            'type_incentive' => 'deduction',
                                            'tanggal_absen_potong_gaji' => $p->format('Y-m-d'),
                                            'is_absen_potong_gaji' => 1,
                                        ]);
                                    } else {


                                        //cek apakah tanggal merah atau bukan
                                        $n_cek_tanggal_merah = TanggalMerah::whereDate('tanggal', $p->format('Y-m-d'))->count();

                                        if ($n_cek_tanggal_merah < 1) {
                                            $n_cutin_cek = Vacation::where('employee_id', $employee->id)
                                                ->where('date_start', $p->format('Y-m-d'))
                                                ->where('date_end', $p->format('Y-m-d'))
                                                ->where('note', 'CUTI AUTO APPROVE SYSTEM')
                                                ->where('status', 'accept')
                                                ->count();

                                            if ($n_cutin_cek < 1) {
                                                Vacation::create([
                                                    'employee_id' => $employee->id,
                                                    'date_start' => $p->format('Y-m-d'),
                                                    'date_end' => $p->format('Y-m-d'),
                                                    'note' => 'CUTI AUTO APPROVE SYSTEM',
                                                    'status' => 'accept'
                                                ]);
                                            }
                                        }
                                    }

                                    //////////////////////////////////////







                                    ///////////////////////////////////////



                                    // $jumlah_hari_cuti

                                    //cari cutinya belum
                                }
                            }


                            $new_at_tidak_hadir  = Attendance::firstOrCreate([
                                'employee_id' => $employee_id,
                                'date' => $p->format('Y-m-d'),
                            ]);



                            $new_at_tidak_hadir->update([
                                'is_absen' => $is_absen,
                                // 'employee_id' => $employee->id,
                                // 'date' => $p->format('Y-m-d'),
                                'cloud_id' => 'TIDAK HADIR',
                                'pin' => 'TIDAK HADIR',
                                'roster_daily_id' => $roster_daily->id ?? null,
                                'roster_status_initial' => $roster_daily->roster_status_initial ?? null
                            ]);
                            // AttendancePayrol::create([
                            //     ''
                            // ]);
                        }
                    }

                    // return $jumlah_hari_kerja_tmp;
                    // return $tanggal_apa_aja;

                    $total_tambahan_dari_sa = 0;
                    $pemotongan_potongan_lain_lain = 0;
                    $tambahan_baru_untuk_incentive_rekap = 0;
                    $sa_percents =  salaryAdjustmentDetail::where('salary_adjustment_id','>',0)->whereMonth('month_start', $tanggal_tambahan_lain->format('m'))
                        ->whereYear('month_start', $tanggal_tambahan_lain->format('Y'))
                        // ->where('type_amount','nominal')
                        ->where('type_time', 'base_time')
                        ->where('employee_id', $employee->id)
                        ->get();

                    foreach ($sa_percents as $key => $v) {

                        //////////////////////////////////////////////////
                        /////// DETAI PAYROL TENTANG TAMBAHAN non thr////////
                        //////////////////////////////////////////////////////  


                        if ($v->type_incentive == 'deduction') {
                            // $pemotongan_potongan_lain_lain

                            if ($v->type_amount == 'nominal') {
                                $pemotongan_potongan_lain_lain += $v->amount;
                            } else {
                                $pemotongan_potongan_lain_lain += ($v->amount / 100) * $employee->basic_salary;
                            }
                        } else {

                            if ($v->type_incentive == 'incentive') {
                                // $tambahan_baru_untuk_incentive_rekap
                                if ($v->type_amount == 'nominal') {
                                    $tambahan_baru_untuk_incentive_rekap += $v->amount;
                                } else {
                                    $tambahan_baru_untuk_incentive_rekap += ($v->amount / 100) * $employee->basic_salary;
                                }



                            }


                            if ($v->type_amount == 'nominal') {
                                $total_tambahan_dari_sa += $v->amount;
                            } else {
                                $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                            }
                        }
                    }



                    // $sa_percents =  salaryAdjustmentDetail::where('salary_adjustment_id','>',0)->whereMonth('month_start',$tanggal_tambahan_lain->format('m'))
                    // ->whereYear('month_start',$tanggal_tambahan_lain->format('Y'))
                    // ->where('type_amount','percent')
                    // ->where('type_time','base_time')
                    // ->where('employee_id',$employee->id)
                    // ->get();

                    // foreach ($sa_percents as $key => $v) {
                    //     $total_tambahan_dari_sa += ($v->amount/100) * $employee->basic_salary;
                    // }

                    $sa_percents =  salaryAdjustmentDetail::whereNull('is_thr')->whereDate('month_start', '>=', $tanggal_tambahan_lain)
                        ->whereDate('month_end', '<=', $tanggal_tambahan_lain)
                        // ->where('type_amount','percent')
                        ->where('type_time', 'base_time')
                        ->where('employee_id', $employee->id)
                        ->get();

                    foreach ($sa_percents as $key => $v) {

                        //////////////////////////////////////////////////
                        /////// DETAI PAYROL TENTANG TAMBAHAN non thr////////
                        //////////////////////////////////////////////////////  



                        if ($v->type_amount == 'nominal') {
                            $total_tambahan_dari_sa += $v->amount;
                        } else {
                            $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                        }
                    }


                    $sa_percents =  salaryAdjustmentDetail::whereNull('is_thr')->whereNull('month_start')
                        ->whereNull('month_end')
                        ->where('type_time', 'forever')
                        ->where('employee_id', $employee->id)
                        ->get();

                        foreach ($sa_percents as $key => $v) {

                            //////////////////////////////////////////////////
                            /////// DETAI PAYROL TENTANG TAMBAHAN thr////////
                            //////////////////////////////////////////////////////  
    
                            if ($v->type_incentive == 'deduction') {
                                if ($v->type_amount == 'nominal') {
                                    $pemotongan_potongan_lain_lain += $v->amount;
                                } else {
                                    $pemotongan_potongan_lain_lain += ($v->amount / 100) * $employee->basic_salary;
                                }
                            } else {
                                if ($v->type_amount == 'nominal') {
                                    $total_tambahan_dari_sa += $v->amount;
                                } else {
                                    $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                                }
                            }
                        }


                    $jumlah_thr = 0;
                    $sa_percents =  salaryAdjustmentDetail::where('is_thr', 1)->whereDate('month_start', '>=', $tanggal_tambahan_lain)
                        ->whereDate('month_end', '<=', $tanggal_tambahan_lain)
                        // ->where('type_amount','percent')
                        ->where('type_time', 'base_time')
                        ->where('employee_id', $employee->id)
                        ->get();

                    foreach ($sa_percents as $key => $v) {

                        if ($v->type_amount == 'nominal') {
                            $jumlah_thr += $v->amount;
                        } else {
                            $jumlah_thr += ($v->amount / 100) * $employee->basic_salary;
                        }
                    }




                    $jumlah_hari_tidak_hadir = Attendance::where('employee_id', $employee->id)
                        ->whereDate('date', '>=', $period_payroll->date_start)
                        ->whereDate('date', '<=', $period_payroll->date_end)
                        ->where('pin', 'TIDAK HADIR')
                        ->count();


                    // return [$jumlah_hari_kerja_tmp, $jumlah_hari_tidak_hadir];
                    // $jumlah_hari_kerja  = $jumlah_hari_kerja_tmp - $jumlah_hari_tidak_hadir;

                    //nanti perbaikin fin ini urgent kalo sudah aman dihapus
                    // $jumlah_hari_kerja  = $jumlah_hari_kerja_tmp;
                    $jumlah_hari_kerja = $jumlah_hari_kerja_real_absen;
                    $pendapatan_tambahan_lain_lain = $total_tambahan_dari_sa;

                    // $jumlah_jam_rate_lembur = 109.0; //contoh
                    // $pendapatan_tambahan_lain_lain = 2645923; //contoh
                    // $jumlah_hari_kerja = 20; //contoh


                    $pendapatan_uang_makan = $jumlah_hari_kerja * $employee->meal_allowance_per_attend;
                    $pendapatan_lembur = $jumlah_jam_lembur_tmp * $employee->overtime_rate_per_hour;

                    $jumlah_pendapatan = $employee->basic_salary + $employee->allowance + $pendapatan_uang_makan + $pendapatan_lembur + $pendapatan_tambahan_lain_lain;



                    $jht_perusahaan_persen = 0;
                    $jht_karyawan_persen = 0;
                    $jht_perusahaan_rupiah = 0;
                    $jht_karyawan_rupiah = 0;

                    if ($employee->bpjs_jht == 'Y') {
                        $jht_perusahaan_persen  = $bpjs_jht->company_percent ?? 0;
                        $jht_karyawan_persen    = $bpjs_jht->employee_percent ?? 0;
                        $jht_perusahaan_rupiah  = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jht->company_percent) / 100);
                        $jht_karyawan_rupiah    = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jht->employee_percent) / 100);
                    }

                    $jkk_perusahaan_persen = 0;
                    $jkk_karyawan_persen = 0;
                    $jkk_perusahaan_rupiah = 0;
                    $jkk_karyawan_rupiah = 0;



                    if ($employee->bpjs_jkk == 'Y') {
                        $jkk_perusahaan_persen  = $bpjs_jkk->company_percent ?? 0;
                        $jkk_karyawan_persen    = $bpjs_jkk->employee_percent ?? 0;
                        $jkk_perusahaan_rupiah  = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jkk->company_percent) / 100);
                        $jkk_karyawan_rupiah    = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jkk->employee_percent) / 100);
                    }

                    $jkm_perusahaan_persen = 0;
                    $jkm_karyawan_persen = 0;
                    $jkm_perusahaan_rupiah = 0;
                    $jkm_karyawan_rupiah = 0;




                    if ($employee->bpjs_jkm == 'Y') {
                        $jkm_perusahaan_persen  = $bpjs_jkm->company_percent ?? 0;
                        $jkm_karyawan_persen    = $bpjs_jkm->employee_percent ?? 0;
                        $jkm_perusahaan_rupiah  = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jkm->company_percent) / 100);
                        $jkm_karyawan_rupiah    = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jkm->employee_percent) / 100);
                    }


                    $jp_perusahaan_persen = 0;
                    $jp_karyawan_persen = 0;
                    $jp_perusahaan_rupiah = 0;
                    $jp_karyawan_rupiah = 0;




                    if ($employee->bpjs_jp == 'Y') {
                        $jp_perusahaan_persen  = $bpjs_jp->company_percent ?? 0;
                        $jp_karyawan_persen    = $bpjs_jp->employee_percent ?? 0;
                        $jp_perusahaan_rupiah  = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jp->company_percent) / 100);
                        $jp_karyawan_rupiah    = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jp->employee_percent) / 100);
                    }



                    $kes_perusahaan_persen  = 0;
                    $kes_karyawan_persen    = 0;
                    $kes_perusahaan_rupiah  = 0;
                    $kes_karyawan_rupiah    = 0;



                    if ($employee->bpjs_kes == 'Y') {
                        $kes_perusahaan_persen  = $bpjs_kes->company_percent ?? 0;
                        $kes_karyawan_persen    = $bpjs_kes->employee_percent ?? 0;
                        $kes_perusahaan_rupiah  = \round(($dasar_updah_bpjs_kes * $bpjs_kes->company_percent) / 100);
                        $kes_karyawan_rupiah    = \round(($dasar_updah_bpjs_kes * $bpjs_kes->employee_percent) / 100);
                    }

                    $bpjs_perusahaan_persen = $kes_perusahaan_persen;
                    $bpjs_karyawan_persen   =  $kes_karyawan_persen;
                    $bpjs_perusahaan_rupiah =  $kes_perusahaan_rupiah;
                    $bpjs_karyawan_rupiah   = $kes_karyawan_rupiah;

                    // return $kes_karyawan_rupiah;


                    $total_bpjs_perusahaan_persen = $jht_perusahaan_persen + $jkk_perusahaan_persen + $jkm_perusahaan_persen + $jp_perusahaan_persen + $kes_perusahaan_persen;
                    $total_bpjs_karyawan_persen = $jht_karyawan_persen + $jkk_karyawan_persen + $jkm_karyawan_persen + $jp_karyawan_persen + $kes_karyawan_persen;

                    $total_bpjs_perusahaan_rupiah = $jht_perusahaan_rupiah + $jkk_perusahaan_rupiah + $jkm_perusahaan_rupiah + $jp_perusahaan_rupiah + $kes_perusahaan_rupiah;
                    $total_bpjs_karyawan_rupiah = $jht_karyawan_rupiah + $jkk_karyawan_rupiah + $jkm_karyawan_rupiah + $jp_karyawan_rupiah + $kes_karyawan_rupiah;



                    $ptkp = 54000000;

                    if ($employee->ptkp == 'TK/0') {
                        $ptkp = 54000000;
                    }

                    if ($employee->ptkp == 'TK/1') {
                        $ptkp = 58500000;
                    }

                    if ($employee->ptkp == 'TK/2') {
                        $ptkp = 63000000;
                    }

                    if ($employee->ptkp == 'TK/3') {
                        $ptkp = 67500000;
                    }

                    if ($employee->ptkp == 'K/0') {
                        $ptkp = 58500000;
                    }

                    if ($employee->ptkp == 'K/1') {
                        $ptkp = 63000000;
                    }

                    if ($employee->ptkp == 'K/2') {
                        $ptkp = 67500000;
                    }

                    if ($employee->ptkp == 'K/3') {
                        $ptkp = 72000000;
                    }

                    if ($employee->ptkp == 'K/I/0') {
                        $ptkp = 112500000;
                    }

                    if ($employee->ptkp == 'K/I/1') {
                        $ptkp = 117000000;
                    }

                    if ($employee->ptkp == 'K/I/2') {
                        $ptkp = 121500000;
                    }

                    if ($employee->ptkp == 'K/I/3') {
                        $ptkp = 126000000;
                    }


                    $pemotongan_bpjs_dibayar_karyawan  = $total_bpjs_karyawan_rupiah;

                    $pemotongan_tidak_hadir  = $jumlah_hari_tidak_masuk_tmp * ($employee->basic_salary / 26);






                    // SalaryAdvanceDetail



                    $pajak_gaji_kotor_kurang_potongan = $jumlah_pendapatan - ($pemotongan_potongan_lain_lain + $pemotongan_tidak_hadir);
                    $pajak_bpjs_dibayar_perusahaan = $total_bpjs_perusahaan_rupiah;


                    $pajak_total_penghasilan_kotor = $pajak_gaji_kotor_kurang_potongan + $pajak_bpjs_dibayar_perusahaan;

                    $n_tahun_enter = Carbon::parse($employee->enter_date)->format('Y');
                    $n_tahun_now = Carbon::now()->format('Y');

                    $jumlah_bulan_kerja = 12;


                    // 2023  < 2022
                    if ($n_tahun_now <= $n_tahun_enter) {
                        $jumlah_bulan_kerja  = 13 - Carbon::parse($employee->enter_date)->format('m');
                    }

                    // $enter_month = ;

                    // if($enter_month > 1){
                    //     $jumlah_bulan_kerja = 13 - $enter_month;
                    // }


                    $pajak_biaya_jabatan = min(500000, (0.05 * $pajak_total_penghasilan_kotor)) * $jumlah_bulan_kerja;
                    $pajak_bpjs_dibayar_karyawan = ($total_bpjs_karyawan_rupiah - $kes_karyawan_rupiah) * $jumlah_bulan_kerja ;

                    if($pajak_bpjs_dibayar_karyawan < 0){
                        $pajak_bpjs_dibayar_karyawan = 0;
                    }
                    // $xyzb;
                    $pajak_total_pengurang = $pajak_biaya_jabatan + $pajak_bpjs_dibayar_karyawan;

                    //////////////////////////////////////////////////////////////
                    ////// JIKA BULAN DESEMBER //////////////////


                    $gaji_jan_nov               = 0;
                    $gaji_des                   = 0;
                    $pph_yang_dipotong_jan_nov  = 0;
                    $pph_yang_dipotong_des      = 0;

                    $now_bulan = Carbon::parse($period_payroll->period)->format('m');
                    $now_tahun = Carbon::parse($period_payroll->period)->format('Y');
                    if ($now_bulan == '12') {
                        $pemotongan_pph_dua_satu_jan_nov = 0;
                        $pajak_gaji_bersih_setahun = 0;
                        $gaji_des = (($pajak_total_penghasilan_kotor * 1)  - $pajak_total_pengurang);
                        $des = Payroll::whereIn('period_payroll_id', function ($q) use ($now_tahun) {
                            $q->select('id')
                                ->from(with(new PeriodPayroll())->getTable())
                                ->whereDate('period', '>=', $now_tahun . '-01-01')
                                ->whereDate('period', '<=', $now_tahun . '-11-30');
                        })
                            ->where('employee_id', $employee->id)

                            ->get();

                        // $gaji_jan_nov = 0;
                        foreach ($des as $key => $d) {
                            $gaji_jan_nov += ($d->pajak_total_penghasilan_kotor - $d->pajak_total_pengurang);
                            $pemotongan_pph_dua_satu_jan_nov += $d->pemotongan_pph_dua_satu;
                        }

                        $pajak_gaji_bersih_setahun = $gaji_des + $gaji_jan_nov;
                    } else {
                        $pajak_gaji_bersih_setahun = (($pajak_total_penghasilan_kotor * $jumlah_bulan_kerja)  - $pajak_total_pengurang);
                    }





                    $pkp_setahun = $pajak_gaji_bersih_setahun - $ptkp;


                    $pkp_lima_persen = 0;
                    $pkp_lima_belas_persen = 0;
                    $pkp_dua_puluh_lima_persen = 0;
                    $pkp_tiga_puluh_persen = 0;




                    if ($pkp_setahun > 0) {
                        // print("EMPLOYE ID".$employee->id."MASUK PKP => ".$pkp_setahun."\n ");
                        //menghitung pkp 5%
                        $pkp_lima_persen  = \max(0, $pkp_setahun > 60000000 ? ((60000000 - 0) * 0.05) : (($pkp_setahun - 0) * 0.05));
                        $pkp_lima_belas_persen  = \max(0, $pkp_setahun > 250000000 ? ((250000000 - 60000000) * 0.15) : (($pkp_setahun - 60000000) * 0.15));
                        $pkp_dua_puluh_lima_persen  = \max(0, $pkp_setahun > 500000000 ? ((500000000 - 250000000) * 0.25) : (($pkp_setahun - 250000000) * 0.25));
                        $pkp_tiga_puluh_persen  = \max(0, $pkp_setahun > 1000000000 ? ((1000000000 - 500000000) * 0.30) : (($pkp_setahun - 500000000) * 0.30));
                    } else {
                        $pkp_setahun = 0;
                    }




                    $pajak_pph_dua_satu_setahun = $pkp_lima_persen + $pkp_lima_belas_persen + $pkp_dua_puluh_lima_persen + $pkp_tiga_puluh_persen;


                    if ($now_bulan == '12') {
                        $pemotongan_pph_dua_satu =  \abs($pajak_pph_dua_satu_setahun - $pemotongan_pph_dua_satu_jan_nov);
                        $jumlah_pemotongan = $pemotongan_bpjs_dibayar_karyawan + $pemotongan_pph_dua_satu + $pemotongan_potongan_lain_lain;
                        $gaji_bersih = $jumlah_pendapatan - $jumlah_pemotongan;
                    } else {
                        $pemotongan_pph_dua_satu = $pajak_pph_dua_satu_setahun / $jumlah_bulan_kerja;
                        $jumlah_pemotongan = $pemotongan_bpjs_dibayar_karyawan + $pemotongan_pph_dua_satu + $pemotongan_potongan_lain_lain;
                        $gaji_bersih = $jumlah_pendapatan - $jumlah_pemotongan;
                    }



                    ///////////////////////////////////////////////////////////////////
                    /////////////////////PERHITUNGAN KHUSUS THR///////////////////////

                    // $jumlah_thr = 0;
                    $pkp_thr_setahun = 0;
                    $pkp_lima_persen_thr = 0;
                    $pkp_lima_belas_persen_thr = 0;
                    $pkp_dua_puluh_lima_persen_thr = 0;
                    $pkp_tiga_puluh_persen_thr = 0;
                    $pajak_pph_dua_satu_setahun_thr = 0;
                    $total_pph_dipotong = 0;


                    if ($jumlah_thr > 0) {
                        // $jumlah_thr = 2000000;
                        $pkp_thr_setahun = $jumlah_thr - $pajak_biaya_jabatan - $ptkp;
                        /////--------------
                        //menghitung pkp 5%
                        $pkp_lima_persen_thr  = \max(0, $pkp_thr_setahun > 60000000 ? ((60000000 - 0) * 0.05) : (($pkp_thr_setahun - 0) * 0.05));
                        $pkp_lima_belas_persen_thr  = \max(0, $pkp_thr_setahun > 250000000 ? ((250000000 - 60000000) * 0.15) : (($pkp_thr_setahun - 60000000) * 0.15));
                        $pkp_dua_puluh_lima_persen_thr  = \max(0, $pkp_thr_setahun > 500000000 ? ((500000000 - 250000000) * 0.25) : (($pkp_thr_setahun - 250000000) * 0.25));
                        $pkp_tiga_puluh_persen_thr  = \max(0, $pkp_thr_setahun > 1000000000 ? ((1000000000 - 500000000) * 0.30) : (($pkp_thr_setahun - 500000000) * 0.30));

                        $pajak_pph_dua_satu_setahun_thr = $pkp_lima_persen_thr + $pkp_lima_belas_persen_thr + $pkp_dua_puluh_lima_persen_thr + $pkp_tiga_puluh_persen_thr;

                        $total_pph_dipotong = $pajak_pph_dua_satu_setahun - $pajak_pph_dua_satu_setahun_thr;

                        $pemotongan_pph_dua_satu = $total_pph_dipotong;
                    }


                    ////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////













                    /////////simulasi naik gaji

                    // if ($period_payroll->period == '2022-06-01' && $employee->id == 1) {
                    //     $employee->basic_salary = 4000000;
                    //     $employee->save();
                    // }

                    // return ['bpjs_perusahaan_persen' => $bpjs_perusahaan_persen,
                    // 'bpjs_karyawan_persen' => $bpjs_karyawan_persen,];

                    $new_payroll->update([
                        'pendapatan_gaji_dasar' => $employee->basic_salary,
                        'pendapatan_tunjangan_tetap' => $employee->allowance,
                        'pendapatan_uang_makan' => $pendapatan_uang_makan,
                        'pendapatan_lembur' => $pendapatan_lembur,
                        'pendapatan_tambahan_lain_lain' => $pendapatan_tambahan_lain_lain,
                        'jumlah_pendapatan' => $jumlah_pendapatan,
                        'pajak_pph_dua_satu_setahun' => $pajak_pph_dua_satu_setahun,


                        // 'pemotongan_bpjs_dibayar_karyawan' => 0,
                        // 'pemotongan_pph_dua_satu' => 0,
                        // 'pemotongan_potongan_lain_lain' => 0,
                        // 'jumlah_pemotongan' => 0,

                        'gaji_bersih' => $gaji_bersih - $jumlah_hutang,
                        'bulan' => $period_payroll->period,
                        'posisi' => "",
                        'gaji_dasar' => $employee->basic_salary,
                        'tunjangan_tetap' => $employee->allowance,


                        'rate_lembur' => $employee->overtime_rate_per_hour,
                        'jumlah_jam_rate_lembur' => $jumlah_jam_lembur_tmp,

                        'tunjangan_makan' => $employee->meal_allowance_per_attend,
                        'jumlah_hari_tunjangan_makan' => $jumlah_hari_kerja,



                        'tunjangan_transport' => $employee->transport_allowance_per_attend,
                        'jumlah_hari_tunjangan_transport' => $jumlah_hari_kerja,



                        'tunjangan_kehadiran' => $employee->attend_allowance_per_attend,
                        'jumlah_hari_tunjangan_kehadiran' => $jumlah_hari_kerja,


                        'ptkp_karyawan' => $ptkp,
                        'jumlah_cuti_ijin_per_bulan' => 0,
                        'sisa_cuti_tahun' => 0,

                        'dasar_updah_bpjs_tk' => $bpjs_dasar_updah_bpjs_tk,
                        'dasar_updah_bpjs_kes' => $dasar_updah_bpjs_kes,



                        'jht_perusahaan_persen' => $jht_perusahaan_persen,
                        'jht_karyawan_persen' => $jht_karyawan_persen,
                        'jht_perusahaan_rupiah' => $jht_perusahaan_rupiah,
                        'jht_karyawan_rupiah' => $jht_karyawan_rupiah,

                        'jkk_perusahaan_persen' => $jkk_perusahaan_persen,
                        'jkk_karyawan_persen' => $jkk_karyawan_persen,
                        'jkk_perusahaan_rupiah' => $jkk_perusahaan_rupiah,
                        'jkk_karyawan_rupiah' => $jkk_karyawan_rupiah,

                        'jkm_perusahaan_persen' => $jkm_perusahaan_persen,
                        'jkm_karyawan_persen' => $jkm_karyawan_persen,
                        'jkm_perusahaan_rupiah' => $jkm_perusahaan_rupiah,
                        'jkm_karyawan_rupiah' => $jkm_karyawan_rupiah,

                        'jp_perusahaan_persen' => $jp_perusahaan_persen,
                        'jp_karyawan_persen' => $jp_karyawan_persen,
                        'jp_perusahaan_rupiah' => $jp_perusahaan_rupiah,
                        'jp_karyawan_rupiah' => $jp_karyawan_rupiah,

                        'bpjs_perusahaan_persen' => $bpjs_perusahaan_persen,
                        'bpjs_karyawan_persen' => $bpjs_karyawan_persen,

                        'bpjs_perusahaan_rupiah' => $bpjs_perusahaan_rupiah,
                        'bpjs_karyawan_rupiah' => $bpjs_karyawan_rupiah,

                        'total_bpjs_perusahaan_persen' => $total_bpjs_perusahaan_persen,
                        'total_bpjs_karyawan_persen' => $total_bpjs_karyawan_persen,
                        'total_bpjs_perusahaan_rupiah' => $total_bpjs_perusahaan_rupiah,
                        'total_bpjs_karyawan_rupiah' => $total_bpjs_karyawan_rupiah,


                        'jumlah_pemotongan' => $jumlah_pemotongan + $jumlah_hutang,

                        'pemotongan_bpjs_dibayar_karyawan' => $pemotongan_bpjs_dibayar_karyawan,
                        'pemotongan_pph_dua_satu' => $pemotongan_pph_dua_satu,
                        'pemotongan_potongan_lain_lain' => $pemotongan_potongan_lain_lain + $jumlah_hutang,


                        'pajak_gaji_kotor_kurang_potongan' => $pajak_gaji_kotor_kurang_potongan,
                        'pajak_bpjs_dibayar_perusahaan' => $pajak_bpjs_dibayar_perusahaan,
                        'pajak_total_penghasilan_kotor' => $pajak_total_penghasilan_kotor,
                        'pajak_biaya_jabatan' => $pajak_biaya_jabatan,
                        'pajak_bpjs_dibayar_karyawan' => $pajak_bpjs_dibayar_karyawan,
                        'pajak_total_pengurang' => $pajak_total_pengurang,
                        'pajak_gaji_bersih_setahun' => $pajak_gaji_bersih_setahun,
                        'pkp_setahun' => $pkp_setahun,

                        'pkp_lima_persen' => $pkp_lima_persen,
                        'pkp_lima_belas_persen' => $pkp_lima_belas_persen,
                        'pkp_dua_puluh_lima_persen' => $pkp_dua_puluh_lima_persen,
                        'pkp_tiga_puluh_persen' => $pkp_tiga_puluh_persen,



                        'jumlah_thr' => $jumlah_thr,
                        'pkp_thr_setahun' => $pkp_thr_setahun,
                        'pkp_lima_persen_thr' => $pkp_lima_persen_thr,
                        'pkp_lima_belas_persen_thr' => $pkp_lima_belas_persen_thr,
                        'pkp_dua_puluh_lima_persen_thr' => $pkp_dua_puluh_lima_persen_thr,
                        'pkp_tiga_puluh_persen_thr' => $pkp_tiga_puluh_persen_thr,
                        'pajak_pph_dua_satu_setahun_thr' => $pajak_pph_dua_satu_setahun_thr,
                        'total_pph_dipotong' => $total_pph_dipotong,


                        'gaji_jan_nov' => $gaji_jan_nov,
                        'gaji_des' => $gaji_des,
                        'pph_yang_dipotong_jan_nov' => $pph_yang_dipotong_jan_nov,
                        'pph_yang_dipotong_des' => $pph_yang_dipotong_des,

                        'jumlah_hutang' => $jumlah_hutang,
                        'pemotongan_tidak_hadir' => $pemotongan_tidak_hadir,
                        'jumlah_bulan_kerja' => $jumlah_bulan_kerja,

                        //baru ditambah

                        'tambahan_baru_untuk_incentive_rekap'=>$tambahan_baru_untuk_incentive_rekap,
                    ]);

                    // return $new_payroll;

                    AttendancePayrol::whereDate('date', '>=', $period_payroll->date_start)
                        ->whereDate('date', '<=', $period_payroll->date_end)
                        ->where('employee_id', $employee->id)
                        // ->where(function ($query) {
                        //     $query->where('hour_start', '!=', NULL)->orWhere('hour_end', '!=', NULL);
                        // })
                        ->update([
                            'payroll_id' => $new_payroll->id
                        ]);
                }


                $unik_name_excel = Str::replace('/', ' ', $employee_real_data->name) . '_periode_' . $period_payroll->period . '.xlsx';


                DB::commit();




                return Excel::download(new PayrollExportPerEmployee($period_payroll, $employee_real_data), $unik_name_excel);


                // return 'true';
            }



            //////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////

            if ($employee_real_data->employee_type_id == 3) {
                $period_payroll = PeriodPayroll::whereMonth('period', $bulan)->whereYear('period', $tahun)->first();

                if (!isset($period_payroll->id)) {
                    $period_payroll = PeriodPayroll::create([
                        'period' => $tahun . "-" . $bulan . "-01",
                        'date_start' => Carbon::parse($tahun . "-" . $bulan . "-01")->addMonth(-1)->format('Y-m-') . "26",
                        'date_end' => $tahun . "-" . $bulan . "-25",
                    ]);
                }

                /////bersih bersih absen
                Attendance::whereDate('attendance.date', '>=', $period_payroll->date_start)
                    ->whereDate('attendance.date', '<=', $period_payroll->date_end)
                    ->where('employee_id', $employee_id)
                    ->update([
                        'jam_masuk_real' => null,
                        'jam_pulang_real' => null,
                        'jam_istirahat_keluar_real' => null,
                        'jam_istirahat_masuk_real' => null,
                        'jam_pulang_lembur' => null,
                        'jam_mulai_lembur' => null,
                        'duration_work' => null,
                        'hour_start' => null,
                        'hour_end' => null,
                        'cloud_id' => null,
                        'pin' => null,


                        'duration_overtime' => null,
                        'hour_overtime_start' => null,
                        'hour_overtime_end' => null,

                    ]);



                $dtn = Carbon::parse($period_payroll->date_start);
                $dtn_enter_date = Carbon::parse($employee_real_data->enter_date);

                if ($dtn->lessThan($dtn_enter_date)) {
                    $period_payroll->date_start = $employee_real_data->enter_date;
                }








                //isi apa aja dulu
                // $query_where = ' AND e.id="' . $employee_id . '" ';

                // return $employee_real_data;

                $tanggals = [];

                foreach ($employee_real_data->data_finger as $key => $df) {
                    // return $df;

                    $hadirs = DB::table('attendance_fingerspots')
                        ->select(
                            'attendance_fingerspots.*',
                            DB::raw('DATE(attendance_fingerspots.scan_date) as tanggal'),
                            DB::raw('TIME(attendance_fingerspots.scan_date) as jam'),
                        )
                        // ->whereBetween('attendance_fingerspots.scan_date', [$period_payroll->date_start, $period_payroll->date_end])
                        ->whereDate('attendance_fingerspots.scan_date', '>=', $period_payroll->date_start)
                        ->whereDate('attendance_fingerspots.scan_date', '<=', $period_payroll->date_end)
                        ->where('attendance_fingerspots.pin', $df->pin)
                        ->where('attendance_fingerspots.cloud_id', $df->finger_tool->cloud_id)
                        ->where('attendance_fingerspots.status_scan', '<', 4)
                        ->orderBy('attendance_fingerspots.scan_date', 'asc')
                        ->get();



                    // return $hadirs->groupBy('tanggal')->map( function($q){
                    //     return $q->groupBy('scan_status');
                    // });
                    foreach ($hadirs as $key => $hadir) {
                        $tanggals[] = $hadir->tanggal;

                        if ($hadir->status_scan == 0) {
                            $start = Carbon::createFromTimeString('16:30:00');
                            $end = Carbon::createFromTimeString('19:00:00')->addDay();

                            $time_now = Carbon::createFromTimeString($hadir->jam);

                            if ($time_now->between($start, $end)) {
                                $hadir->status_scan = 1;
                            }
                        }




                        $new_attendance_lost = Attendance::firstOrCreate([
                            'employee_id' => $employee_id,
                            'date' => $hadir->tanggal,
                        ]);


                        if ($hadir->status_scan == 0) {
                            //jam masuk
                            $new_attendance_lost->update([
                                'jam_masuk_real' => $hadir->jam,
                                'hour_start' => $hadir->jam,
                            ]);
                        } elseif ($hadir->status_scan == 1) {
                            $new_attendance_lost->update([
                                'jam_pulang_real' => $hadir->jam
                            ]);
                        } elseif ($hadir->status_scan == 2) {
                            $new_attendance_lost->update([
                                'jam_istirahat_keluar_real' => $hadir->jam
                            ]);
                        } elseif ($hadir->status_scan == 3) {
                            $new_attendance_lost->update([
                                'jam_istirahat_masuk_real' => $hadir->jam
                            ]);
                        }
                    }
                }




                // return 'SELECT TIME(af.scan_date) AS jam, DATE(af.scan_date) AS tanggal, af.scan_date, af.status_scan, af.pin, f.employee_id FROM employees AS e, attendance_fingerspots AS af, fingers AS f
                // WHERE e.id=f.employee_id
                // AND f.id_finger=af.pin
                // AND (DATE(af.scan_date) BETWEEN "' . $period_payroll->date_start . '" AND "' . $period_payroll->date_end . '")
                // AND af.status_scan < 4
                // GROUP BY e.id, af.status_scan
                // ORDER BY f.employee_id ASC , af.status_scan asc, af.scan_date ASC';

                // $tanggals =  \collect($datas_real)->groupBy('tanggal')->keys();

                $jumlah_hari_kerja_real_absen =  count(array_values(array_unique($tanggals)));
                $new_datax = [];

                // return array_values(array_unique($tanggals));
                $masuk_tanggal_merah =  TanggalMerah::whereIn('tanggal', array_values(array_unique($tanggals)))->get();

                // $employee_real_data->basic_salary/26

                // foreach ($masuk_tanggal_merah as $key => $xx) {

                //     salaryAdjustmentDetail::where('employee_id', $employee_id)
                //         ->where('is_tanggal_merah', 1)
                //         ->where('tanggal_merah', $xx->tanggal)
                //         ->delete();


                //     $new_SA = salaryAdjustment::firstOrCreate([
                //         'employee_base' => 'choose_employee',
                //         'name' => 'HARI KERJA +1',
                //         'type_time' => 'base_time',
                //         'is_month_end' => 0,
                //         'type_amount' => 'percent',
                //         'amount' => 3.846153846,
                //         'type_adjustment' => 'addition',
                //         'note' => $xx->keterangan,
                //         'month_start' => $period_payroll->date_end,
                //         'is_thr' => 0,
                //         'type_incentive' => 'incentive',
                //     ]);

                //     salaryAdjustmentDetail::create([
                //         'salary_adjustment_id' => $new_SA->id,
                //         'employee_id' => $employee_id,
                //         'type_amount' => 'percent',
                //         'amount' => 3.846153846,
                //         'type_time' => 'base_time',
                //         'month_start' => $period_payroll->date_end,
                //         'type_incentive' => 'another',
                //         'tanggal_merah' => $xx->tanggal,
                //         'is_tanggal_merah' => 1,
                //     ]);
                // }




                // return ;
                foreach ($tanggals as $key => $tanggal) {
                    $dtx = Carbon::parse($tanggal)->addDays(1)->format('Y-m-d');

                    $new_x = DB::table('attendance_fingerspots')
                        ->select(
                            'attendance_fingerspots.*',
                            DB::raw('DATE(attendance_fingerspots.scan_date) as tanggal'),
                            DB::raw('TIME(attendance_fingerspots.scan_date) as jam'),
                        )
                        // ->whereNull('attendance_fingerspots.deleted_at')
                        ->whereBetween('attendance_fingerspots.scan_date', [$tanggal . " 16:00:00 ", $dtx . " 09:00:00"])
                        // ->whereDate('attendance_fingerspots.scan_date','>=',$period_payroll->date_start)
                        // ->whereDate('attendance_fingerspots.scan_date','<=',$period_payroll->date_end)
                        ->where('attendance_fingerspots.pin', $df->pin)
                        ->where('attendance_fingerspots.cloud_id', $df->finger_tool->cloud_id)
                        ->where('attendance_fingerspots.status_scan', '>=', 4)
                        ->orderBy('attendance_fingerspots.scan_date', 'asc')
                        ->get();



                    // $new_x = collect(DB::select('SELECT TIME(MIN(af.scan_date)) AS jam, DATE(af.scan_date) AS tanggal, af.scan_date, af.status_scan, af.pin, f.employee_id 
                    //  FROM employees AS e, attendance_fingerspots AS af, fingers AS f
                    //  WHERE e.id=f.employee_id
                    //  AND f.id_finger=af.pin
                    //  AND (af.scan_date BETWEEN "' . $tanggal . ' 17:00:00" AND "' . $dtx . ' 09:00:00")
                    //  AND af.status_scan > 3 ' . $query_where . '
                    //  GROUP BY DATE(af.scan_date),e.id,af.status_scan 
                    //  ORDER BY f.employee_id ASC , af.status_scan asc, af.scan_date ASC'));

                    ////////////////////////////////////////////////////////////////////////


                    if ($new_x->count() > 0) {
                        // return $new_x;
                        foreach ($new_x as $key => $x) {
                            $x->tanggal_attendance = $tanggal;


                            $new_attendance_lost = Attendance::firstOrCreate([
                                'employee_id' => $employee_id,
                                'date' => $tanggal,
                            ]);

                            if ($x->status_scan == 5) {
                                $new_attendance_lost->update([
                                    'jam_pulang_lembur' => $x->jam
                                ]);
                            } elseif ($x->status_scan == 4) {
                                $new_attendance_lost->update([
                                    'jam_mulai_lembur' => $x->jam
                                ]);
                            }

                            $js = JobStatusHasParent::where('employee_id', $employee_id)
                                ->whereDate('datetime_start', $tanggal)
                                ->where('status', 'overtime')
                                ->get();

                            foreach ($js as $key => $j) {
                                $new_attendance_lost->update([
                                    'is_nonstop' => $j->is_overtime_rest
                                ]);
                            }
                        }
                    }
                }

                // return $new_datax;

                // foreach ($datas_real as $key => $d) {
                //     $new_attendance_lost = Attendance::firstOrCreate([
                //         'employee_id' => $d->employee_id,
                //         'date' => $d->tanggal,
                //     ]);


                //     if ($d->status_scan == 0) {
                //         //jam masuk
                //         $new_attendance_lost->update([
                //             'jam_masuk_real' => $d->jam
                //         ]);
                //     } elseif ($d->status_scan == 1) {
                //         $new_attendance_lost->update([
                //             'jam_pulang_real' => $d->jam_pulang
                //         ]);
                //     } elseif ($d->status_scan == 2) {
                //         $new_attendance_lost->update([
                //             'jam_istirahat_keluar_real' => $d->jam
                //         ]);
                //     } elseif ($d->status_scan == 3) {
                //         $new_attendance_lost->update([
                //             'jam_istirahat_masuk_real' => $d->jam
                //         ]);
                //     }
                // }


                //isi data lembur terlebih dahulu
                $js = JobStatusHasParent::select(
                    'job_status_has_parents.*',
                    DB::raw('DATE(job_status_has_parents.datetime_start) as tanggal')

                )
                    ->whereNotNull('employee_id')
                    ->whereNotNull('datetime_end')
                    ->whereNull('deleted_at')
                    ->whereDate('datetime_start', '>=', $period_payroll->date_start)
                    ->whereDate('datetime_start', '<=', $period_payroll->date_end)
                    ->where('status', 'overtime')
                    ->where('employee_id', $employee_id)
                    ->get();

                // ;

                $tmp_grpby =  $js->groupBy('tanggal');
                foreach ($tmp_grpby as $key => $v) {

                    $tanggal_tmp = $key;
                    # code...
                    // return $key;

                    // if($key == "2023-08-11"){
                    //     return $v;
                    // }


                    $duration_overtime_last = 0;
                    foreach ($v as $key => $vv) {
                        $start_carbon = Carbon::parse($vv->datetime_start);
                        $end_carbon = Carbon::parse($vv->datetime_end);

                        // if($tanggal_tmp == "2023-08-17"){
                        //     return [$vv->datetime_start,$vv->datetime_end];
                        // }



                        $duration_overtime = $start_carbon->diffInMinutes($end_carbon);

                        // if($tanggal_tmp == "2023-08-17"){
                        //     return [$vv->datetime_start,$vv->datetime_end,$duration_overtime];
                        // }



                        if ($vv->is_overtime_rest) {
                            $duration_overtime  = $duration_overtime - 60;
                        }

                        $duration_overtime_last = $duration_overtime_last + $duration_overtime;
                    }

                    Attendance::where('employee_id', $vv->employee_id)
                        ->whereDate('date', Carbon::parse($vv->datetime_start)->format('Y-m-d'))
                        ->update([
                            'hour_overtime_start' => $vv->datetime_start,
                            'hour_overtime_end' => $vv->datetime_end,
                            'duration_overtime' => $duration_overtime_last
                        ]);
                }

                // return $tmp_grpby;


                // foreach ($js as $key => $s) {



                //     // if($s->employee_id == 6){
                //     //     return "nemu fin";
                //     // }



                // }




                // if ($period_payroll->is_final == 1) {
                //     if ($this->period_payrol_month_year != null) {
                //         Log::error('sudah ada');
                //         print("Sudah Ada");
                //         return false;
                //     } else {
                //         Log::error('sudah ada');
                //         print("Sudah Ada");
                //         return false;
                //     }
                // }
                // $period_payroll->save();


                // AttendancePayrol::whereDate('date','>=',$period_payroll->date_start)
                // ->whereDate('date','<=',$period_payroll->date_end)
                // ->get();
                // AttendancePayrol::create();

                // if ($message == 'ditambahkan') {
                //     DB::select("
                //     INSERT INTO attendance(pin, cloud_id,employee_id,date,hour_start,hour_end,duration_work,hour_rest_start,hour_rest_end,duration_rest,hour_overtime_start,hour_overtime_end,duration_overtime,hour_overtime_job_order_start,hour_overtime_job_order_end,duration_overtime_job_order,is_weekend,is_vacation,is_payroll_use,payroll_id)
                //     SELECT pin, cloud_id,employee_id,date,hour_start,hour_end,duration_work,hour_rest_start,hour_rest_end,duration_rest,hour_overtime_start,hour_overtime_end,duration_overtime,hour_overtime_job_order_start,hour_overtime_job_order_end,duration_overtime_job_order,is_weekend,is_vacation,is_payroll_use,payroll_id
                //     FROM attendance_has_employees where date(date) >= '" . $period_payroll->date_start . "' AND date(date) <= '" . $period_payroll->date_end . "'
                //     ");
                // }











                $employees = Employee::whereIn('id', [$employee_id])->orderBy('name', 'asc')->get();

                $bpjs_jht = BpjsCalculation::where('code', 'jht')->first();
                $bpjs_jkk = BpjsCalculation::where('code', 'jkk')->first();
                $bpjs_jkm = BpjsCalculation::where('code', 'jkm')->first();
                $bpjs_jp = BpjsCalculation::where('code', 'jp')->first();
                $bpjs_kes = BpjsCalculation::where('code', 'kes')->first();

                ///////////////////

                $bpjs_jht->company_percent = $employee_real_data->bpjs_jht_company_percent;
                $bpjs_jht->employee_percent = $employee_real_data->bpjs_jht_employee_percent;

                $bpjs_jkk->company_percent = $employee_real_data->bpjs_jkk_company_percent;
                $bpjs_jkk->employee_percent = $employee_real_data->bpjs_jkk_employee_percent;

                $bpjs_jkm->company_percent = $employee_real_data->bpjs_jkm_company_percent;
                $bpjs_jkm->employee_percent = $employee_real_data->bpjs_jkm_employee_percent;

                $bpjs_jp->company_percent = $employee_real_data->bpjs_jp_company_percent;
                $bpjs_jp->employee_percent = $employee_real_data->bpjs_jp_employee_percent;

                $bpjs_kes->company_percent = $employee_real_data->bpjs_kes_company_percent;
                $bpjs_kes->employee_percent = $employee_real_data->bpjs_kes_employee_percent;

                // return [$employee_real_data->bpjs_kes_company_percent, $employee_real_data->bpjs_kes_employee_percent];






                $bpjs_dasar_updah_bpjs_tk = $employee_real_data->bpjs_dasar_updah_bpjs_tk ?? 0;
                $dasar_updah_bpjs_kes = $employee_real_data->dasar_updah_bpjs_kes ?? 0;

                // print("\n\n\n FFFFFFFFFFFFFFFFFFFFF \n");


                $tanggal_tambahan_lain =  Carbon::parse($period_payroll->period . "-30");

                // return [$period_payroll->date_start,$period_payroll->date_end];

                $period = CarbonPeriod::create($period_payroll->date_start, $period_payroll->date_end);

                // print("masuk sini -----------------");
                foreach ($employees as $key => $employee) {
                    $employee_id = $employee->id;
                    $start_date = $period_payroll->date_start;
                    $end_date =  $period_payroll->date_end;

                    $new_payroll = Payroll::firstOrCreate([
                        'employee_id' => $employee->id,
                        'period_payroll_id' => $period_payroll->id,
                    ]);



                    // AttendanceHasEmployee

                    // return [$start_date,$end_date];
                    $attende_fingers = AttendanceHasEmployee::where('employee_id', $employee_id)
                        ->whereDate('date', '>=', $start_date)
                        ->whereDate('date', '<=', $end_date)
                        // ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();

                    // return $sql = Str::replaceArray('?', $attende_fingers->getBindings(), $attende_fingers->toSql());

                    foreach ($attende_fingers as $key => $v) {
                        $new_at  = Attendance::firstOrCreate([
                            'employee_id' => $employee_id,
                            'date' => $v->date,
                        ]);

                        if ($new_at->is_koreksi == 0) {
                            $new_at->update([
                                'hour_start' => $v->hour_start,
                                'hour_end' => $v->hour_end,
                                'duration_work' => $v->duration_work,

                                'hour_rest_start' => $v->hour_rest_start,
                                'hour_rest_end' => $v->hour_rest_end,
                                'duration_rest' => $v->duration_rest,

                                // 'hour_overtime_start' => $v->hour_overtime_start,
                                // 'hour_overtime_end' => $v->hour_overtime_end,
                                // 'duration_overtime' => $v->duration_overtime,
                            ]);
                        }


                        //validasi lembur
                    }



                    $data_absens = Attendance::where('employee_id', $employee->id)
                        ->whereDate('date', '>=', $period_payroll->date_start)
                        ->whereDate('date', '<=', $period_payroll->date_end)
                        ->get();

                    // print("-----------");
                    // print_r(json_encode($data_absens->pluck('date')));
                    // print("-----------");

                    $jumlah_jam_lembur_tmp = 0;
                    $jumlah_hari_kerja_tmp = 0;
                    $jumlah_hari_tidak_masuk_tmp = 0;


                    $jumlah_hutang  = 0;
                    $tanggal_apa_aja  = [];


                    // print("MASUK PERIODCONTROLER -----xxxxx");

                    // return [$period_payroll->date_start, $period_payroll->date_end];


                    //masalah lagi hutang ini, secepatnya di perbaiki
                    $jumlah_hutang =  SalaryAdvanceDetail::whereDate('salary_advance_details.date_start', '<=', $period_payroll->date_end)
                        // ->whereDate('salary_advance_details.date_end', '<=', $period_payroll->date_end)
                        ->where('employee_id', $employee_id)
                        ->whereDate('date_end', '>=', $period_payroll->date_end)
                        ->sum('amount');

                    //////////////////////////////////////////
                    /////// DETAI PAYROL TENTANG HUTANG////////
                    ///////////////////////////////////////////  

                    // $iterxxxxxxxxxxxxx=1;
                    $employee->basic_salary =  $employee->basic_salary * $jumlah_hari_kerja_real_absen;

                    // return $jumlah_hari_kerja_tmp;
                    // return $tanggal_apa_aja;

                    $total_tambahan_dari_sa = 0;
                    $pemotongan_potongan_lain_lain = 0;
                    $tambahan_baru_untuk_incentive_rekap = 0;
                    $sa_percents =  salaryAdjustmentDetail::where('salary_adjustment_id','>',0)->whereMonth('month_start', $tanggal_tambahan_lain->format('m'))
                        ->whereYear('month_start', $tanggal_tambahan_lain->format('Y'))
                        // ->where('type_amount','nominal')
                        ->where('type_time', 'base_time')
                        ->where('employee_id', $employee->id)
                        ->get();

                    foreach ($sa_percents as $key => $v) {

                        //////////////////////////////////////////////////
                        /////// DETAI PAYROL TENTANG TAMBAHAN non thr////////
                        //////////////////////////////////////////////////////  


                        if ($v->type_incentive == 'deduction') {
                            // $pemotongan_potongan_lain_lain

                            if ($v->type_amount == 'nominal') {
                                $pemotongan_potongan_lain_lain += $v->amount;
                            } else {
                                $pemotongan_potongan_lain_lain += ($v->amount / 100) * $employee->basic_salary;
                            }
                        } else {
                            if ($v->type_amount == 'nominal') {
                                $total_tambahan_dari_sa += $v->amount;
                            } else {
                                $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                            }
                        }
                    }



                    // $sa_percents =  salaryAdjustmentDetail::where('salary_adjustment_id','>',0)->whereMonth('month_start',$tanggal_tambahan_lain->format('m'))
                    // ->whereYear('month_start',$tanggal_tambahan_lain->format('Y'))
                    // ->where('type_amount','percent')
                    // ->where('type_time','base_time')
                    // ->where('employee_id',$employee->id)
                    // ->get();

                    // foreach ($sa_percents as $key => $v) {
                    //     $total_tambahan_dari_sa += ($v->amount/100) * $employee->basic_salary;
                    // }

                    $sa_percents =  salaryAdjustmentDetail::whereNull('is_thr')->whereDate('month_start', '>=', $tanggal_tambahan_lain)
                        ->whereDate('month_end', '<=', $tanggal_tambahan_lain)
                        // ->where('type_amount','percent')
                        ->where('type_time', 'base_time')
                        ->where('employee_id', $employee->id)
                        ->get();

                    foreach ($sa_percents as $key => $v) {

                        //////////////////////////////////////////////////
                        /////// DETAI PAYROL TENTANG TAMBAHAN non thr////////
                        //////////////////////////////////////////////////////  



                        if ($v->type_amount == 'nominal') {
                            $total_tambahan_dari_sa += $v->amount;
                        } else {
                            $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                        }
                    }


                    $sa_percents =  salaryAdjustmentDetail::whereNull('is_thr')->whereNull('month_start')
                        ->whereNull('month_end')
                        ->where('type_time', 'forever')
                        ->where('employee_id', $employee->id)
                        ->get();

                    foreach ($sa_percents as $key => $v) {

                        //////////////////////////////////////////////////
                        /////// DETAI PAYROL TENTANG TAMBAHAN thr////////
                        //////////////////////////////////////////////////////  

                        if ($v->type_incentive == 'deduction') {
                            if ($v->type_amount == 'nominal') {
                                $pemotongan_potongan_lain_lain += $v->amount;
                            } else {
                                $pemotongan_potongan_lain_lain += ($v->amount / 100) * $employee->basic_salary;
                            }
                        } else {
                            if ($v->type_amount == 'nominal') {
                                $total_tambahan_dari_sa += $v->amount;
                            } else {
                                $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                            }
                        }
                    }


                    $jumlah_thr = 0;
                    $sa_percents =  salaryAdjustmentDetail::where('is_thr', 1)->whereDate('month_start', '>=', $tanggal_tambahan_lain)
                        ->whereDate('month_end', '<=', $tanggal_tambahan_lain)
                        // ->where('type_amount','percent')
                        ->where('type_time', 'base_time')
                        ->where('employee_id', $employee->id)
                        ->get();

                    foreach ($sa_percents as $key => $v) {

                        if ($v->type_amount == 'nominal') {
                            $jumlah_thr += $v->amount;
                        } else {
                            $jumlah_thr += ($v->amount / 100) * $employee->basic_salary;
                        }
                    }




                    $jumlah_hari_tidak_hadir = Attendance::where('employee_id', $employee->id)
                        ->whereDate('date', '>=', $period_payroll->date_start)
                        ->whereDate('date', '<=', $period_payroll->date_end)
                        ->where('pin', 'TIDAK HADIR')
                        ->count();


                    // return [$jumlah_hari_kerja_tmp, $jumlah_hari_tidak_hadir];
                    // $jumlah_hari_kerja  = $jumlah_hari_kerja_tmp - $jumlah_hari_tidak_hadir;

                    //nanti perbaikin fin ini urgent kalo sudah aman dihapus
                    // $jumlah_hari_kerja  = $jumlah_hari_kerja_tmp;
                    $jumlah_hari_kerja = $jumlah_hari_kerja_real_absen;
                    $pendapatan_tambahan_lain_lain = $total_tambahan_dari_sa;

                    // $jumlah_jam_rate_lembur = 109.0; //contoh
                    // $pendapatan_tambahan_lain_lain = 2645923; //contoh
                    // $jumlah_hari_kerja = 20; //contoh


                    $pendapatan_uang_makan = $jumlah_hari_kerja * $employee->meal_allowance_per_attend;
                    $pendapatan_lembur = $jumlah_jam_lembur_tmp * $employee->overtime_rate_per_hour;

                    $jumlah_pendapatan = $employee->basic_salary + $employee->allowance + $pendapatan_uang_makan + $pendapatan_lembur + $pendapatan_tambahan_lain_lain;



                    $jht_perusahaan_persen = 0;
                    $jht_karyawan_persen = 0;
                    $jht_perusahaan_rupiah = 0;
                    $jht_karyawan_rupiah = 0;

                    if ($employee->bpjs_jht == 'Y') {
                        $jht_perusahaan_persen  = $bpjs_jht->company_percent ?? 0;
                        $jht_karyawan_persen    = $bpjs_jht->employee_percent ?? 0;
                        $jht_perusahaan_rupiah  = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jht->company_percent) / 100);
                        $jht_karyawan_rupiah    = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jht->employee_percent) / 100);
                    }

                    $jkk_perusahaan_persen = 0;
                    $jkk_karyawan_persen = 0;
                    $jkk_perusahaan_rupiah = 0;
                    $jkk_karyawan_rupiah = 0;



                    if ($employee->bpjs_jkk == 'Y') {
                        $jkk_perusahaan_persen  = $bpjs_jkk->company_percent ?? 0;
                        $jkk_karyawan_persen    = $bpjs_jkk->employee_percent ?? 0;
                        $jkk_perusahaan_rupiah  = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jkk->company_percent) / 100);
                        $jkk_karyawan_rupiah    = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jkk->employee_percent) / 100);
                    }

                    $jkm_perusahaan_persen = 0;
                    $jkm_karyawan_persen = 0;
                    $jkm_perusahaan_rupiah = 0;
                    $jkm_karyawan_rupiah = 0;




                    if ($employee->bpjs_jkm == 'Y') {
                        $jkm_perusahaan_persen  = $bpjs_jkm->company_percent ?? 0;
                        $jkm_karyawan_persen    = $bpjs_jkm->employee_percent ?? 0;
                        $jkm_perusahaan_rupiah  = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jkm->company_percent) / 100);
                        $jkm_karyawan_rupiah    = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jkm->employee_percent) / 100);
                    }


                    $jp_perusahaan_persen = 0;
                    $jp_karyawan_persen = 0;
                    $jp_perusahaan_rupiah = 0;
                    $jp_karyawan_rupiah = 0;




                    if ($employee->bpjs_jp == 'Y') {
                        $jp_perusahaan_persen  = $bpjs_jp->company_percent ?? 0;
                        $jp_karyawan_persen    = $bpjs_jp->employee_percent ?? 0;
                        $jp_perusahaan_rupiah  = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jp->company_percent) / 100);
                        $jp_karyawan_rupiah    = \round(($bpjs_dasar_updah_bpjs_tk * $bpjs_jp->employee_percent) / 100);
                    }



                    $kes_perusahaan_persen  = 0;
                    $kes_karyawan_persen    = 0;
                    $kes_perusahaan_rupiah  = 0;
                    $kes_karyawan_rupiah    = 0;



                    if ($employee->bpjs_kes == 'Y') {
                        $kes_perusahaan_persen  = $bpjs_kes->company_percent ?? 0;
                        $kes_karyawan_persen    = $bpjs_kes->employee_percent ?? 0;
                        $kes_perusahaan_rupiah  = \round(($dasar_updah_bpjs_kes * $bpjs_kes->company_percent) / 100);
                        $kes_karyawan_rupiah    = \round(($dasar_updah_bpjs_kes * $bpjs_kes->employee_percent) / 100);
                    }

                    $bpjs_perusahaan_persen = $kes_perusahaan_persen;
                    $bpjs_karyawan_persen   =  $kes_karyawan_persen;
                    $bpjs_perusahaan_rupiah =  $kes_perusahaan_rupiah;
                    $bpjs_karyawan_rupiah   = $kes_karyawan_rupiah;

                    // return $kes_karyawan_rupiah;


                    $total_bpjs_perusahaan_persen = $jht_perusahaan_persen + $jkk_perusahaan_persen + $jkm_perusahaan_persen + $jp_perusahaan_persen + $kes_perusahaan_persen;
                    $total_bpjs_karyawan_persen = $jht_karyawan_persen + $jkk_karyawan_persen + $jkm_karyawan_persen + $jp_karyawan_persen + $kes_karyawan_persen;
                    $total_bpjs_perusahaan_rupiah = $jht_perusahaan_rupiah + $jkk_perusahaan_rupiah + $jkm_perusahaan_rupiah + $jp_perusahaan_rupiah + $kes_perusahaan_rupiah;
                    $total_bpjs_karyawan_rupiah = $jht_karyawan_rupiah + $jkk_karyawan_rupiah + $jkm_karyawan_rupiah + $jp_karyawan_rupiah + $kes_karyawan_rupiah;



                    $ptkp = 54000000;

                    if ($employee->ptkp == 'TK/0') {
                        $ptkp = 54000000;
                    }

                    if ($employee->ptkp == 'TK/1') {
                        $ptkp = 58500000;
                    }

                    if ($employee->ptkp == 'TK/2') {
                        $ptkp = 63000000;
                    }

                    if ($employee->ptkp == 'TK/3') {
                        $ptkp = 67500000;
                    }

                    if ($employee->ptkp == 'K/0') {
                        $ptkp = 58500000;
                    }

                    if ($employee->ptkp == 'K/1') {
                        $ptkp = 63000000;
                    }

                    if ($employee->ptkp == 'K/2') {
                        $ptkp = 67500000;
                    }

                    if ($employee->ptkp == 'K/3') {
                        $ptkp = 72000000;
                    }

                    if ($employee->ptkp == 'K/I/0') {
                        $ptkp = 112500000;
                    }

                    if ($employee->ptkp == 'K/I/1') {
                        $ptkp = 117000000;
                    }

                    if ($employee->ptkp == 'K/I/2') {
                        $ptkp = 121500000;
                    }

                    if ($employee->ptkp == 'K/I/3') {
                        $ptkp = 126000000;
                    }


                    $pemotongan_bpjs_dibayar_karyawan  = $total_bpjs_karyawan_rupiah;

                    $pemotongan_tidak_hadir  = $jumlah_hari_tidak_masuk_tmp * ($employee->basic_salary / 26);






                    // SalaryAdvanceDetail



                    $pajak_gaji_kotor_kurang_potongan = $jumlah_pendapatan - ($pemotongan_potongan_lain_lain + $pemotongan_tidak_hadir);
                    $pajak_bpjs_dibayar_perusahaan = $total_bpjs_perusahaan_rupiah;


                    $pajak_total_penghasilan_kotor = $pajak_gaji_kotor_kurang_potongan + $pajak_bpjs_dibayar_perusahaan;

                    $n_tahun_enter = Carbon::parse($employee->enter_date)->format('Y');
                    $n_tahun_now = Carbon::now()->format('Y');

                    $jumlah_bulan_kerja = 12;


                    // 2023  < 2022
                    if ($n_tahun_now <= $n_tahun_enter) {
                        $jumlah_bulan_kerja  = 13 - Carbon::parse($employee->enter_date)->format('m');
                    }

                    // $enter_month = ;

                    // if($enter_month > 1){
                    //     $jumlah_bulan_kerja = 13 - $enter_month;
                    // }


                    $pajak_biaya_jabatan = min(500000, (0.05 * $pajak_total_penghasilan_kotor)) * $jumlah_bulan_kerja;
                    $pajak_bpjs_dibayar_karyawan = ($total_bpjs_karyawan_rupiah - $kes_karyawan_rupiah) * $jumlah_bulan_kerja;

                    
                    $pajak_total_pengurang = $pajak_biaya_jabatan + $pajak_bpjs_dibayar_karyawan;



                    //////////////////////////////////////////////////////////////
                    ////// JIKA BULAN DESEMBER //////////////////


                    $gaji_jan_nov               = 0;
                    $gaji_des                   = 0;
                    $pph_yang_dipotong_jan_nov  = 0;
                    $pph_yang_dipotong_des      = 0;

                    $now_bulan = Carbon::parse($period_payroll->period)->format('m');
                    $now_tahun = Carbon::parse($period_payroll->period)->format('Y');
                    if ($now_bulan == '12') {
                        $pemotongan_pph_dua_satu_jan_nov = 0;
                        $pajak_gaji_bersih_setahun = 0;
                        $gaji_des = (($pajak_total_penghasilan_kotor * 1)  - $pajak_total_pengurang);
                        $des = Payroll::whereIn('period_payroll_id', function ($q) use ($now_tahun) {
                            $q->select('id')
                                ->from(with(new PeriodPayroll())->getTable())
                                ->whereDate('period', '>=', $now_tahun . '-01-01')
                                ->whereDate('period', '<=', $now_tahun . '-11-30');
                        })
                            ->where('employee_id', $employee->id)

                            ->get();

                        // $gaji_jan_nov = 0;
                        foreach ($des as $key => $d) {
                            $gaji_jan_nov += ($d->pajak_total_penghasilan_kotor - $d->pajak_total_pengurang);
                            $pemotongan_pph_dua_satu_jan_nov += $d->pemotongan_pph_dua_satu;
                        }

                        $pajak_gaji_bersih_setahun = $gaji_des + $gaji_jan_nov;
                    } else {
                        $pajak_gaji_bersih_setahun = (($pajak_total_penghasilan_kotor * $jumlah_bulan_kerja)  - $pajak_total_pengurang);
                    }





                    $pkp_setahun = $pajak_gaji_bersih_setahun - $ptkp;


                    $pkp_lima_persen = 0;
                    $pkp_lima_belas_persen = 0;
                    $pkp_dua_puluh_lima_persen = 0;
                    $pkp_tiga_puluh_persen = 0;




                    if ($pkp_setahun > 0) {
                        // print("EMPLOYE ID".$employee->id."MASUK PKP => ".$pkp_setahun."\n ");
                        //menghitung pkp 5%
                        $pkp_lima_persen  = \max(0, $pkp_setahun > 60000000 ? ((60000000 - 0) * 0.05) : (($pkp_setahun - 0) * 0.05));
                        $pkp_lima_belas_persen  = \max(0, $pkp_setahun > 250000000 ? ((250000000 - 60000000) * 0.15) : (($pkp_setahun - 60000000) * 0.15));
                        $pkp_dua_puluh_lima_persen  = \max(0, $pkp_setahun > 500000000 ? ((500000000 - 250000000) * 0.25) : (($pkp_setahun - 250000000) * 0.25));
                        $pkp_tiga_puluh_persen  = \max(0, $pkp_setahun > 1000000000 ? ((1000000000 - 500000000) * 0.30) : (($pkp_setahun - 500000000) * 0.30));
                    } else {
                        $pkp_setahun = 0;
                    }




                    $pajak_pph_dua_satu_setahun = $pkp_lima_persen + $pkp_lima_belas_persen + $pkp_dua_puluh_lima_persen + $pkp_tiga_puluh_persen;


                    if ($now_bulan == '12') {
                        $pemotongan_pph_dua_satu =  \abs($pajak_pph_dua_satu_setahun - $pemotongan_pph_dua_satu_jan_nov);
                        $jumlah_pemotongan = $pemotongan_bpjs_dibayar_karyawan + $pemotongan_pph_dua_satu + $pemotongan_potongan_lain_lain;
                        $gaji_bersih = $jumlah_pendapatan - $jumlah_pemotongan;
                    } else {
                        $pemotongan_pph_dua_satu = $pajak_pph_dua_satu_setahun / $jumlah_bulan_kerja;
                        $jumlah_pemotongan = $pemotongan_bpjs_dibayar_karyawan + $pemotongan_pph_dua_satu + $pemotongan_potongan_lain_lain;
                        $gaji_bersih = $jumlah_pendapatan - $jumlah_pemotongan;
                    }



                    ///////////////////////////////////////////////////////////////////
                    /////////////////////PERHITUNGAN KHUSUS THR///////////////////////

                    // $jumlah_thr = 0;
                    $pkp_thr_setahun = 0;
                    $pkp_lima_persen_thr = 0;
                    $pkp_lima_belas_persen_thr = 0;
                    $pkp_dua_puluh_lima_persen_thr = 0;
                    $pkp_tiga_puluh_persen_thr = 0;
                    $pajak_pph_dua_satu_setahun_thr = 0;
                    $total_pph_dipotong = 0;


                    if ($jumlah_thr > 0) {
                        // $jumlah_thr = 2000000;
                        $pkp_thr_setahun = $jumlah_thr - $pajak_biaya_jabatan - $ptkp;
                        /////--------------
                        //menghitung pkp 5%
                        $pkp_lima_persen_thr  = \max(0, $pkp_thr_setahun > 60000000 ? ((60000000 - 0) * 0.05) : (($pkp_thr_setahun - 0) * 0.05));
                        $pkp_lima_belas_persen_thr  = \max(0, $pkp_thr_setahun > 250000000 ? ((250000000 - 60000000) * 0.15) : (($pkp_thr_setahun - 60000000) * 0.15));
                        $pkp_dua_puluh_lima_persen_thr  = \max(0, $pkp_thr_setahun > 500000000 ? ((500000000 - 250000000) * 0.25) : (($pkp_thr_setahun - 250000000) * 0.25));
                        $pkp_tiga_puluh_persen_thr  = \max(0, $pkp_thr_setahun > 1000000000 ? ((1000000000 - 500000000) * 0.30) : (($pkp_thr_setahun - 500000000) * 0.30));

                        $pajak_pph_dua_satu_setahun_thr = $pkp_lima_persen_thr + $pkp_lima_belas_persen_thr + $pkp_dua_puluh_lima_persen_thr + $pkp_tiga_puluh_persen_thr;

                        $total_pph_dipotong = $pajak_pph_dua_satu_setahun - $pajak_pph_dua_satu_setahun_thr;

                        $pemotongan_pph_dua_satu = $total_pph_dipotong;
                    }


                    ////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////













                    /////////simulasi naik gaji

                    // if ($period_payroll->period == '2022-06-01' && $employee->id == 1) {
                    //     $employee->basic_salary = 4000000;
                    //     $employee->save();
                    // }

                    // return ['bpjs_perusahaan_persen' => $bpjs_perusahaan_persen,
                    // 'bpjs_karyawan_persen' => $bpjs_karyawan_persen,];

                    $new_payroll->update([
                        'pendapatan_gaji_dasar' => $employee->basic_salary,
                        'pendapatan_tunjangan_tetap' => $employee->allowance,
                        'pendapatan_uang_makan' => $pendapatan_uang_makan,
                        'pendapatan_lembur' => $pendapatan_lembur,
                        'pendapatan_tambahan_lain_lain' => $pendapatan_tambahan_lain_lain,
                        'jumlah_pendapatan' => $jumlah_pendapatan,
                        'pajak_pph_dua_satu_setahun' => $pajak_pph_dua_satu_setahun,


                        // 'pemotongan_bpjs_dibayar_karyawan' => 0,
                        // 'pemotongan_pph_dua_satu' => 0,
                        // 'pemotongan_potongan_lain_lain' => 0,
                        // 'jumlah_pemotongan' => 0,

                        'gaji_bersih' => $gaji_bersih - $jumlah_hutang,
                        'bulan' => $period_payroll->period,
                        'posisi' => "",
                        'gaji_dasar' => $employee->basic_salary,
                        'tunjangan_tetap' => $employee->allowance,


                        'rate_lembur' => $employee->overtime_rate_per_hour,
                        'jumlah_jam_rate_lembur' => $jumlah_jam_lembur_tmp,

                        'tunjangan_makan' => $employee->meal_allowance_per_attend,
                        'jumlah_hari_tunjangan_makan' => $jumlah_hari_kerja,



                        'tunjangan_transport' => $employee->transport_allowance_per_attend,
                        'jumlah_hari_tunjangan_transport' => $jumlah_hari_kerja,



                        'tunjangan_kehadiran' => $employee->attend_allowance_per_attend,
                        'jumlah_hari_tunjangan_kehadiran' => $jumlah_hari_kerja,


                        'ptkp_karyawan' => $ptkp,
                        'jumlah_cuti_ijin_per_bulan' => 0,
                        'sisa_cuti_tahun' => 0,

                        'dasar_updah_bpjs_tk' => $bpjs_dasar_updah_bpjs_tk,
                        'dasar_updah_bpjs_kes' => $dasar_updah_bpjs_kes,



                        'jht_perusahaan_persen' => $jht_perusahaan_persen,
                        'jht_karyawan_persen' => $jht_karyawan_persen,
                        'jht_perusahaan_rupiah' => $jht_perusahaan_rupiah,
                        'jht_karyawan_rupiah' => $jht_karyawan_rupiah,

                        'jkk_perusahaan_persen' => $jkk_perusahaan_persen,
                        'jkk_karyawan_persen' => $jkk_karyawan_persen,
                        'jkk_perusahaan_rupiah' => $jkk_perusahaan_rupiah,
                        'jkk_karyawan_rupiah' => $jkk_karyawan_rupiah,

                        'jkm_perusahaan_persen' => $jkm_perusahaan_persen,
                        'jkm_karyawan_persen' => $jkm_karyawan_persen,
                        'jkm_perusahaan_rupiah' => $jkm_perusahaan_rupiah,
                        'jkm_karyawan_rupiah' => $jkm_karyawan_rupiah,

                        'jp_perusahaan_persen' => $jp_perusahaan_persen,
                        'jp_karyawan_persen' => $jp_karyawan_persen,
                        'jp_perusahaan_rupiah' => $jp_perusahaan_rupiah,
                        'jp_karyawan_rupiah' => $jp_karyawan_rupiah,

                        'bpjs_perusahaan_persen' => $bpjs_perusahaan_persen,
                        'bpjs_karyawan_persen' => $bpjs_karyawan_persen,

                        'bpjs_perusahaan_rupiah' => $bpjs_perusahaan_rupiah,
                        'bpjs_karyawan_rupiah' => $bpjs_karyawan_rupiah,

                        'total_bpjs_perusahaan_persen' => $total_bpjs_perusahaan_persen,
                        'total_bpjs_karyawan_persen' => $total_bpjs_karyawan_persen,
                        'total_bpjs_perusahaan_rupiah' => $total_bpjs_perusahaan_rupiah,
                        'total_bpjs_karyawan_rupiah' => $total_bpjs_karyawan_rupiah,


                        'jumlah_pemotongan' => $jumlah_pemotongan + $jumlah_hutang,

                        'pemotongan_bpjs_dibayar_karyawan' => $pemotongan_bpjs_dibayar_karyawan,
                        'pemotongan_pph_dua_satu' => $pemotongan_pph_dua_satu,
                        'pemotongan_potongan_lain_lain' => $pemotongan_potongan_lain_lain + $jumlah_hutang,


                        'pajak_gaji_kotor_kurang_potongan' => $pajak_gaji_kotor_kurang_potongan,
                        'pajak_bpjs_dibayar_perusahaan' => $pajak_bpjs_dibayar_perusahaan,
                        'pajak_total_penghasilan_kotor' => $pajak_total_penghasilan_kotor,
                        'pajak_biaya_jabatan' => $pajak_biaya_jabatan,
                        'pajak_bpjs_dibayar_karyawan' => $pajak_bpjs_dibayar_karyawan,
                        'pajak_total_pengurang' => $pajak_total_pengurang,
                        'pajak_gaji_bersih_setahun' => $pajak_gaji_bersih_setahun,
                        'pkp_setahun' => $pkp_setahun,

                        'pkp_lima_persen' => $pkp_lima_persen,
                        'pkp_lima_belas_persen' => $pkp_lima_belas_persen,
                        'pkp_dua_puluh_lima_persen' => $pkp_dua_puluh_lima_persen,
                        'pkp_tiga_puluh_persen' => $pkp_tiga_puluh_persen,



                        'jumlah_thr' => $jumlah_thr,
                        'pkp_thr_setahun' => $pkp_thr_setahun,
                        'pkp_lima_persen_thr' => $pkp_lima_persen_thr,
                        'pkp_lima_belas_persen_thr' => $pkp_lima_belas_persen_thr,
                        'pkp_dua_puluh_lima_persen_thr' => $pkp_dua_puluh_lima_persen_thr,
                        'pkp_tiga_puluh_persen_thr' => $pkp_tiga_puluh_persen_thr,
                        'pajak_pph_dua_satu_setahun_thr' => $pajak_pph_dua_satu_setahun_thr,
                        'total_pph_dipotong' => $total_pph_dipotong,


                        'gaji_jan_nov' => $gaji_jan_nov,
                        'gaji_des' => $gaji_des,
                        'pph_yang_dipotong_jan_nov' => $pph_yang_dipotong_jan_nov,
                        'pph_yang_dipotong_des' => $pph_yang_dipotong_des,

                        'jumlah_hutang' => $jumlah_hutang,
                        'pemotongan_tidak_hadir' => $pemotongan_tidak_hadir,
                        'jumlah_bulan_kerja' => $jumlah_bulan_kerja,

                        //baru ditambah

                        'tambahan_baru_untuk_incentive_rekap'=>$tambahan_baru_untuk_incentive_rekap,
                    ]);

                    // return $new_payroll;

                    AttendancePayrol::whereDate('date', '>=', $period_payroll->date_start)
                        ->whereDate('date', '<=', $period_payroll->date_end)
                        ->where('employee_id', $employee->id)
                        // ->where(function ($query) {
                        //     $query->where('hour_start', '!=', NULL)->orWhere('hour_end', '!=', NULL);
                        // })
                        ->update([
                            'payroll_id' => $new_payroll->id
                        ]);
                }


                $unik_name_excel = Str::replace('/', ' ', $employee_real_data->name) . '_periode_' . $period_payroll->period . '.xlsx';


                DB::commit();




                return Excel::download(new PayrollExportPerEmployee($period_payroll, $employee_real_data), $unik_name_excel);


                // return 'true';
            }
        } catch (\Exception $e) {
            DB::rollback();
            return [$e->getMessage(), $e->getLine(), $e->getTrace()];
            // print_r([$e->getMessage(), $e->getLine()]);

            // $routeAction = Route::currentRouteAction();
            // $log = new LogController;
            // $log->store($e->getMessage(), $routeAction);

            // return false;
        }

        // print("ID : ".$employee_real_data->id."Type : ".$employee_real_data->employee_type_id);

        return [$employee_real_data->employee_type_id];
    }
}
