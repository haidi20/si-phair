<template>
  <div>
    <DatatableClientSide
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="jobOrder"
      nameLoading="data"
      :filter="true"
      bordered
    >
      <template v-slot:filter>
        <b-col cols>
          <b-form-group label="Tanggal" label-for="date" class="place_filter_table">
            <DatePicker
              id="date"
              v-model="params.date_range"
              format="YYYY-MM-DD"
              type="date"
              range
              placeholder="pilih tanggal"
            />
          </b-form-group>
          <b-button
            class="place_filter_table"
            variant="success"
            size="sm"
            @click="onFilter()"
            :disabled="getIsLoadingData || is_loading_export"
          >Kirim</b-button>
          <!-- <span v-if="getIsLoadingData">Loading...</span> -->
          <b-button
            v-if="getCan('export excel laporan surat perintah lembur')"
            class="place_filter_table ml-4"
            variant="success"
            size="sm"
            @click="onExport()"
            :disabled="is_loading_export || getIsLoadingData"
          >
            <i class="fas fa-file-excel"></i>
            Export
          </b-button>
          <span v-if="is_loading_export">Loading...</span>
          <b-button
            v-if="getCan('lembur job order')"
            variant="warning"
            size="sm"
            class="ml-4"
            @click="onOpenOvertime()"
          >SPL</b-button>
        </b-col>
      </template>
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <!-- <b-td style="text-align: center">
            <ButtonAction class="cursor-pointer" type="click">
              <template v-slot:list_detail_button>
                <a href="#" @click="onEdit(item)">Ubah</a>
              </template>
            </ButtonAction>
          </b-td>-->
          <b-td style="text-align: center">
            <i
              v-if="getCan('ubah laporan surat perintah lembur')"
              class="bi bi-pencil cursor-pointer"
              @click="onEdit(item)"
              style="color: #31D2F2;"
            ></i>
            <i
              v-if="getCan('hapus laporan surat perintah lembur')"
              class="bi bi-trash cursor-pointer"
              @click="onDelete(item)"
              style="color: #C82333; margin-left: 10px"
            ></i>
          </b-td>
          <template v-for="(column, index) in getColumns()">
            <b-td :key="`col-${index}`">{{ item[column.field] }}</b-td>
          </template>
        </b-tr>
      </template>
    </DatatableClientSide>
    <modalOvertime />
  </div>
</template>

<script>
import _ from "lodash";
import axios from "axios";
import moment from "moment";
import DatePicker from "vue2-datepicker";

import ButtonAction from "../../components/ButtonAction";
import DatatableClientSide from "../../components/DatatableClient";
import ModalOvertime from "../JobOrder/View/modalOvertime.vue";

export default {
  data() {
    return {
      is_loading_export: false,
      options: {
        perPage: 10,
        // perPageValues: [5, 10, 25, 50, 100],
        filterByColumn: true,
        texts: {
          filter: "",
          count: " ",
        },
      },
      columns: [
        {
          label: "",
          field: "",
          width: "10px",
          class: "",
        },
        {
          label: "Nama Karyawan",
          field: "employee_name",
          width: "100px",
          class: "",
        },
        {
          label: "Jabatan",
          field: "position_name",
          width: "100px",
          class: "",
        },
        {
          label: "Pekerjaan",
          field: "job_name",
          width: "100px",
          class: "",
        },
        {
          label: "Waktu Mulai",
          field: "datetime_start_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Waktu Selesai",
          field: "datetime_end_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Durasi",
          field: "duration_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Catatan",
          field: "note_start",
          width: "100px",
          class: "",
        },
      ],
    };
  },
  components: {
    DatePicker,
    ButtonAction,
    DatatableClientSide,
    ModalOvertime,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getData() {
      return this.$store.state.jobOrder.data;
    },
    getIsLoadingData() {
      return this.$store.state.jobOrder.loading.data;
    },
    params() {
      return this.$store.state.jobOrder.params;
    },
  },
  methods: {
    onFilter() {
      this.$store.dispatch("jobOrder/fetchDataOvertimeReport");
    },
    onEdit(form) {
      this.$store.commit("jobOrder/INSERT_FORM", { form });
      this.$bvModal.show("overtime_report_form");
    },
    onRead(data) {
      //
    },
    onOpenOvertime() {
      this.$bvModal.show("overtime_modal");
    },
    async onDelete(data) {
      // console.info(data);

      const Swal = this.$swal;
      await Swal.fire({
        title: "Perhatian!!!",
        html: `Anda yakin ingin hapus SPL karyawan <h2><b>${data.employee_name}</b> ?</h2>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya hapus",
        cancelButtonText: "Batal",
      }).then(async (result) => {
        if (result.isConfirmed) {
          await axios
            .post(`${this.getBaseUrl}/api/v1/job-status-has-parent/delete`, {
              id: data.id,
              user_id: this.getUserId,
            })
            .then((responses) => {
              console.info(responses);
              const data = responses.data;

              const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener("mouseenter", Swal.stopTimer);
                  toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
              });

              if (data.success == true) {
                Toast.fire({
                  icon: "success",
                  title: data.message,
                });

                this.$store.dispatch("jobOrder/fetchDataOvertimeReport");
              }
            })
            .catch((err) => {
              console.info(err);

              const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener("mouseenter", Swal.stopTimer);
                  toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
              });

              Toast.fire({
                icon: "error",
                title: err.response.data.message,
              });
            });
        }
      });
    },
    async onExport() {
      const Swal = this.$swal;

      const params = {
        user_id: this.getUserId,
        date_start: moment(this.params.date_range[0]).format("Y-MM-DD"),
        date_end: moment(this.params.date_range[1]).format("Y-MM-DD"),
      };

      //   console.info(params);
      //   return false;
      this.is_loading_export = true;

      //   console.info(moment(this.params.month).format("Y-MM"));

      await axios
        .get(`${this.getBaseUrl}/report/overtime/export`, {
          params: params,
        })
        .then((responses) => {
          //   console.info(responses);
          this.is_loading_export = false;
          const data = responses.data;

          if (data.success) {
            window.open(data.linkDownload, "_blank");
          }
        })
        .catch((err) => {
          this.is_loading_export = false;
          console.info(err);
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });
          Toast.fire({
            icon: "error",
            title: err.response.data.message,
          });
        });
    },
    getColumns() {
      const columns = this.columns.filter((item) => item.label != "");
      return columns;
    },
    getCan(permissionName) {
      const getPermission = this.$store.getters["getCan"](permissionName);

      return getPermission;
    },
  },
};
</script>

<style lang="css">
.VueTables__search-field {
  display: none;
}

.place_filter_table {
  align-items: self-end;
  margin-bottom: 0;
  display: inline-block;
}

.table-wrapper {
  overflow-x: auto;
}
</style>
