<template>
  <div>
    <div class="page-heading">
      <div class="page-title">
        <div class="row">
          <div class="col-12 col-md-6 order-md-1 order-last d-flex align-items-center">
            <h3 class="d-flex align-items-center">Dashboard</h3>
            <span>&nbsp;v1.1</span>
          </div>
        </div>
      </div>
    </div>
    <div class="page-content">
      <template v-if="getCan('hrd dashboard')">
        <section class="row">
          <div class="col-12 col-lg-12">
            <div class="row">
              <div
                :class="`col-6 col-md-6 col-lg-3`"
                v-for="(total, index) in getDataTotal"
                :key="index"
              >
                <div class="card cursor-pointer" @click="onShowData(total)">
                  <div class="card-body py-3-5">
                    <div class="row">
                      <div class="col-md-4">
                        <div :class="`stats-icon ${total.color}`">
                          <i :class="total.icon"></i>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <h6 class="text-muted font-semibold">{{total.title}}</h6>
                        <h6 class="font-extrabold mb-0">{{total.value}}</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" style="margin-bottom: 10px">
              <div class="col-md-4">
                <b-button class variant="success" size="sm" @click="onExport()">
                  <i class="fas fa-file-excel"></i>
                  Export
                </b-button>
                <span v-if="is_loading_export">Loading...</span>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-4 col-md-12">
                <div class="card">
                  <div class="card-body px-3 py-3-5">
                    <DatatableClient
                      :data="getDataEmployeeNotYetJobOrder"
                      :columns="employee_notyet_columns"
                      :options="options"
                      nameStore="dashboard"
                      nameLoading="table"
                      :filter="true"
                      :footer="false"
                      bordered
                    >
                      <template v-slot:filter>
                        <b-col cols @click="onShowSettingPosition" class="cursor-pointer">
                          Karyawan Belum Punya Job Order Per Hari Ini
                          <!-- <i class="fas fa-cogs"></i> -->
                        </b-col>
                      </template>
                      <template v-slot:tbody="{ filteredData }">
                        <b-tr v-for="(item, index) in filteredData" :key="index">
                          <b-td>{{item.name}}</b-td>
                          <b-td>{{item.position_name}}</b-td>
                        </b-tr>
                      </template>
                    </DatatableClient>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-4 col-md-12">
                <div class="card">
                  <div class="card-body px-3 py-3-5">
                    <DatatableClient
                      :data="getDataFiveEmployeeHighestJobOrder"
                      :columns="employee_columns"
                      :options="options"
                      nameStore="dashboard"
                      nameLoading="table"
                      :filter="true"
                      :footer="false"
                      bordered
                    >
                      <template v-slot:filter>
                        <b-col cols>5 Karyawan Dengan Job Order Terbanyak</b-col>
                      </template>
                      <template v-slot:tbody="{ filteredData }">
                        <b-tr v-for="(item, index) in filteredData" :key="index">
                          <b-td>{{item.employee_name}}</b-td>
                          <b-td>{{item.position_name}}</b-td>
                          <b-td>{{item.total}}</b-td>
                        </b-tr>
                      </template>
                    </DatatableClient>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-4 col-md-12">
                <div class="card">
                  <div class="card-body px-3 py-3-5">
                    <DatatableClient
                      :data="getDataTotalEmployeeBaseonPosition"
                      :columns="position_columns"
                      :options="options"
                      nameStore="dashboard"
                      nameLoading="table"
                      :filter="true"
                      :footer="false"
                      bordered
                    >
                      <template v-slot:filter>
                        <b-col cols>Total Karyawan Berdasarkan Jabatan</b-col>
                      </template>
                      <template v-slot:tbody="{ filteredData }">
                        <b-tr v-for="(item, index) in filteredData" :key="index">
                          <b-td>{{item.name}}</b-td>
                          <b-td>{{item.minimum_employee}}</b-td>
                          <b-td>{{item.actual}}</b-td>
                        </b-tr>
                      </template>
                    </DatatableClient>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </template>
      <template v-else-if="getLengthPermissions.length > 0 && !getCan('hrd dashboard')">
        <section class="row" style="height: 600px">
          <div class="col-12 col-lg-12">
            <div class="row">
              <h1>Selamat Datang di Aplikasi HRIS - KPT</h1>
            </div>
          </div>
        </section>
      </template>
    </div>
    <!-- start modal -->
    <b-modal
      id="data_total"
      ref="data_total"
      title="Data Karyawan"
      size="lg"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <DatatableClient
            :data="getDataSelecteds"
            :columns="employee_total_columns"
            :options="options"
            nameStore="dashboard"
            nameLoading="selected"
            :filter="false"
            :footer="false"
            bordered
          >
            <template v-slot:tbody="{ filteredData }">
              <b-tr v-for="(item, index) in filteredData" :key="index">
                <b-td>{{item.name != undefined ? item.name : item.employee_name}}</b-td>
                <b-td>{{item.position_name}}</b-td>
                <b-td nowrap>
                  <span v-if="type_table_total != 'notAbsence'">
                    Jam
                    {{
                    type_table_total != 'notCombackAfterRest' ? 'Datang' : 'Mulai Istirahat'
                    }}
                    :
                    {{
                    type_table_total != 'notCombackAfterRest'
                    ? getTime(item.hour_start)
                    : getTime(item.hour_rest_start)
                    }}
                  </span>
                  <br />
                  <span
                    v-if="type_table_total == 'absenceLate'"
                  >Maks. Jam Terlambat : {{item.working_hour_late}}</span>
                </b-td>
              </b-tr>
            </template>
          </DatatableClient>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col style="text-align: -webkit-right;">
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
      </b-row>
    </b-modal>
    <b-modal
      id="data_setting_position"
      ref="data_setting_position"
      title="Jabatan yang Menggunakan Job Order"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label label-for="position_id" class>
            <VueSelect
              id="position_id"
              class="cursor-pointer"
              v-model="form.position_id"
              placeholder="Pilih Jabatan"
              :options="getOptionPositions"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
        <b-col cols="3">
          <b-button variant="success" @click="onSendHasPosition()">Tambah</b-button>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <DatatableClient
            :data="getDataDashboardHasPositions"
            :columns="dashboard_has_position_columns"
            :options="options"
            nameStore="dashboard"
            nameLoading="dashboard_has_position"
            :filter="false"
            :footer="false"
            bordered
          >
            <template v-slot:tbody="{ filteredData }">
              <b-tr v-for="(item, index) in filteredData" :key="index">
                <b-td>{{item.position_name}}</b-td>
                <b-td style="text-align-last: center;">
                  <b-button variant="danger" size="sm" @click="onDeleteHasPosition(item)">Hapus</b-button>
                </b-td>
              </b-tr>
            </template>
          </DatatableClient>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col style="text-align: -webkit-right;">
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
      </b-row>
    </b-modal>
    <!-- end modal -->
  </div>
</template>

<script src="../Script/dashboard.js"></script>

<style lang="scss" scoped>
</style>
