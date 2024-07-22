<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="titleForm">Edit Data Absensi</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {!! Form::open(['url' => action('\App\Http\Controllers\PayrollController@update_attendance', [$attendance->id]), 'method' => 'PUT', 'id' => 'attendance_edit_form']) !!}
            @csrf
            <div class="modal-body">

                <div class="form-group row">
                    <label for="name" class="col-sm-4 col-form-label">NIP Karyawan</label>
                    <div class="col-sm-8">
                        <input type="text" id="name" name="name" class="form-control" autocomplete="off" value="{{$attendance->employee->nip}}" readonly>
                    </div>
                </div>



                <div class="form-group row">
                    <label for="name" class="col-sm-4 col-form-label">Nama Karyawan</label>
                    <div class="col-sm-8">
                        <input type="text" id="name" name="name" class="form-control" autocomplete="off" value="{{$attendance->employee->name}}" readonly>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="name" class="col-sm-4 col-form-label">Tanggal</label>
                    <div class="col-sm-8">
                        <input type="text" id="name" name="name" class="form-control" autocomplete="off" value="{{\Carbon\Carbon::parse($attendance->date)->translatedFormat('l, d F Y')}}" readonly>
                    </div>
                </div>




                <input type="hidden" id="id" name="id" class="form-control">

                
                <div class="form-group row">
                    <label for="jam_masuk" class="col-sm-4 col-form-label">Jam Masuk</label>
                    <div class="col-sm-8">
                        <input type="text"   id="jam_masuk" name="hour_start" class="form-control datepicker_hour_and_minute" autocomplete="off" value="{{\Carbon\Carbon::parse($attendance->hour_start)->format('H:i')}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jam_keluar" class="col-sm-4 col-form-label">Jam Keluar </label>
                    <div class="col-sm-8">
                        <input type="text" id="jam_keluar" name="hour_end" class="form-control datepicker_hour_and_minute" value="{{\Carbon\Carbon::parse($attendance->hour_end)->format('H:i')}}" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="lembur_x1" class="col-sm-4 col-form-label">Edit Jam Lembur</label>
                    <div class="col-sm-8">
                        <select  class="form-control" name="edit_jam_lembur" id="edit_jam_lembur_select" >
                            <option value="iya">IYA</option>
                            <option value="tidak" selected>Tidak</option>
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="lembur_x1" class="col-sm-4 col-form-label">Lembur X1.5</label>
                    <div class="col-sm-8">
                        <input type="text" id="lembur_kali_satu_lima" name="lembur_kali_satu_lima" class="form-control form_jam_lembur" autocomplete="off" value="{{$attendance->lembur_kali_satu_lima}}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lembur_x2" class="col-sm-4 col-form-label">Lembur X2</label>
                    <div class="col-sm-8">
                        <input type="text" id="lembur_kali_dua" name="lembur_kali_dua" class="form-control form_jam_lembur" autocomplete="off" value="{{$attendance->lembur_kali_dua}}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lembur_x3" class="col-sm-4 col-form-label">Lembur X3</label>
                    <div class="col-sm-8">
                        <input type="text" id="lembur_kali_tiga" name="lembur_kali_tiga" class="form-control form_jam_lembur" autocomplete="off" value="{{$attendance->lembur_kali_tiga}}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lembur_x4" class="col-sm-4 col-form-label">Lembur X4</label>
                    <div class="col-sm-8">
                        <input type="text" id="lembur_kali_empat" name="lembur_kali_empat" class="form-control form_jam_lembur" autocomplete="off" value="{{$attendance->lembur_kali_empat}}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
                    <div class="col-sm-8">
                        <input type="text" id="keterangan" name="keterangan" class="form-control" autocomplete="off" required value="{{$attendance->keterangan}}">
                    </div>
                </div>
                


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-success">Kirim</button>
            </div>
        </form>
    </div>
</div>