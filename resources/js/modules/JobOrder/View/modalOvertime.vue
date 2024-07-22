<template>
  <div>
    <b-modal
      id="overtime_modal"
      ref="overtime_modal"
      :title="title_form"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-tabs content-class="mt-3">
        <b-tab title="input" active>
          <b-row v-if="getCan('lihat laporan surat perintah lembur')">
            <b-col cols>
              <b-form-group label="Karyawan" label-for="employee_id" class>
                <VueSelect
                  id="employee_id"
                  class="cursor-pointer"
                  v-model="form.employee_id"
                  placeholder="Pilih Karyawan"
                  :options="getOptionEmployees"
                  :reduce="(data) => data.id"
                  label="name_and_position"
                  searchable
                  style="min-width: 180px"
                />
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols>
              <b-row>
                <b-col cols>
                  <b-form-group label="Tanggal Mulai" label-for="date_start">
                    <DatePicker
                      id="date_start"
                      v-model="form.date_start"
                      format="YYYY-MM-DD"
                      type="date"
                      placeholder="pilih Tanggal"
                      style="width: 100%"
                    />
                  </b-form-group>
                </b-col>
                <b-row>
                  <b-col cols>
                    <b-form-group label="Jam Mulai" label-for="hour_start_overtime">
                      <!-- {{getGroupName.toLowerCase()}} -->

                      <vue-timepicker
                        v-if="
                            (
                                getGroupName?.toLowerCase() == 'admin'
                            )
                        "
                        v-model="form.hour_start_overtime"
                        id="hour_start_overtime"
                        name="hour_start_overtime"
                      ></vue-timepicker>
                      <!-- getGroupName?.toLowerCase() == 'super admin' -->
                      <input
                        v-else
                        type="time"
                        class="form-control"
                        v-model="form.hour_start_overtime"
                        id="hour_start_overtime"
                        name="hour_start_overtime"
                      />
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-row>
            </b-col>
            <b-col cols>
              <b-row>
                <b-col cols>
                  <b-form-group label="Tanggal Selesai" label-for="date_end">
                    <DatePicker
                      id="date_end"
                      v-model="form.date_end"
                      format="YYYY-MM-DD"
                      type="date"
                      placeholder="pilih Tanggal"
                      style="width: 100%"
                    />
                  </b-form-group>
                </b-col>
                <b-row>
                  <b-col cols>
                    <b-form-group label="Jam Selesai" label-for="hour_end_overtime">
                      <vue-timepicker
                        v-if="
                            (
                                getGroupName?.toLowerCase() == 'admin'
                            )
                        "
                        v-model="form.hour_end_overtime"
                        id="hour_end_overtime"
                        name="hour_end_overtime"
                      ></vue-timepicker>
                      <input
                        v-else
                        type="time"
                        class="form-control"
                        v-model="form.hour_end_overtime"
                        id="hour_end_overtime"
                        name="hour_end_overtime"
                      />
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-row>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Apakah Nonstop ?" label-for="is_overtime_rest" class>
                <select
                  v-model="form.is_overtime_rest"
                  name="is_overtime_rest"
                  id="is_overtime_rest"
                  class="form-control"
                >
                  <option
                    v-for="(overtime_rest, index) in getOptionOvertimeRest"
                    :key="index"
                    :value="overtime_rest.id"
                  >{{overtime_rest.name}}</option>
                </select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Keterangan" label-for="note">
                <b-form-textarea
                  v-model="form.note"
                  id="note"
                  name="note"
                  autocomplete="off"
                  rows="3"
                  max-rows="6"
                ></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
        </b-tab>
        <b-tab title="data">
          <DatatableClient
            :data="getDataOvertime"
            :columns="columns"
            :options="options"
            nameStore="jobOrder"
            nameLoading="table_overtime_base_user"
            :filter="true"
            :footer="false"
            bordered
          >
            <template v-slot:filter>
              <b-col cols>
                Data SPL
                <!-- <i class="fas fa-cogs"></i> -->
              </b-col>
            </template>
            <template v-slot:tbody="{ filteredData }">
              <b-tr v-for="(item, index) in filteredData" :key="index">
                <b-td v-for="column in getColumns()" :key="column.label">{{ item[column.field] }}</b-td>
              </b-tr>
            </template>
          </DatatableClient>
        </b-tab>
      </b-tabs>
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
        <b-col style="text-align: right;">
          <span v-if="is_loading">Loading...</span>
          <b-button variant="success" @click="onSend()" :disabled="is_loading">Kirim</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script src="../Script/modalOvertime.js"></script>

<style lang="scss" scoped>
</style>
