<template>
  <div>
    <DatatableClientSide
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="vacationReport"
      nameLoading="table"
      :filter="false"
      bordered
    >
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td style="text-align: center">
            <!-- <ButtonAction class="cursor-pointer" type="click">
              <template v-slot:list_detail_button>
                <a href="#" @click="onApproval(item)">Persetujuan</a>
                <a href="#" @click="onRead(item)">Lihat</a>
              </template>
            </ButtonAction>-->
            <span class="cursor-pointer">
              <i
                v-if="getCan('persetujuan laporan cuti')"
                class="bi bi-pencil"
                @click="onApproval(item)"
                style="color: blue;"
              ></i>
              <i
                v-if="getCan('hapus laporan cuti')"
                class="bi bi-trash"
                @click="onDelete(item)"
                style="color: red;"
              ></i>
              <!-- <i class="bi bi-eye cursor-pointer" @click="onRead(item)" style="color: #28A745;"></i> -->
            </span>
          </b-td>
          <template v-for="(column, index) in getColumns()">
            <b-td v-if="column.field == 'status'" :key="`col-${index}`">
              <span :class="`badge bg-${item.status_color}`">{{ item.status_readable }}</span>
            </b-td>
            <b-td v-else :key="`col-${index}`">{{ item[column.field] }}</b-td>
          </template>
        </b-tr>
      </template>
    </DatatableClientSide>

    <FormApproval />
  </div>
</template>

<script>
import _ from "lodash";
import axios from "axios";
import moment from "moment";
import DatePicker from "vue2-datepicker";

import FormApproval from "./formApproval";

import ButtonAction from "../../components/ButtonAction";
import DatatableClientSide from "../../components/DatatableClient";

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
          width: "50px",
          class: "",
        },
        {
          label: "Di buat oleh",
          field: "creator_name",
          width: "100px",
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
          label: "Tanggal Mulai",
          field: "date_start_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Tanggal Selesai",
          field: "date_end_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Jangka Waktu",
          field: "duration_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Catatan",
          field: "note",
          width: "100px",
          class: "",
        },
        {
          label: "Status",
          field: "status",
          width: "100px",
          class: "",
        },
      ],
    };
  },
  components: {
    DatePicker,
    ButtonAction,
    FormApproval,
    DatatableClientSide,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getData() {
      return this.$store.state.vacationReport.data;
    },
    getIsLoadingData() {
      return this.$store.state.vacationReport.loading.table;
    },
    params() {
      return this.$store.state.vacationReport.params;
    },
  },
  methods: {
    onApproval(form) {
      //   console.info(form);
      this.$store.commit("vacationReport/INSERT_FORM", { form });
      this.$bvModal.show("vacation_report_form");
    },
    onRead(data) {
      //
    },
    async onDelete(data) {
      const Swal = this.$swal;
      await Swal.fire({
        title: "Perhatian!!!",
        html: `Anda yakin ingin hapus Cuti <h2><b>${data.employee_name}</b> </h2> tanggal ${data.date_start_readable} ?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya hapus",
        cancelButtonText: "Batal",
      }).then(async (result) => {
        if (result.isConfirmed) {
          await axios
            .post(`${this.getBaseUrl}/api/v1/vacation/delete`, {
              id: data.id,
              user_id: this.getUserId,
            })
            .then((responses) => {
              //   console.info(responses);
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

                this.$store.dispatch("vacationReport/fetchData");
              }
            })
            .catch((err) => {
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
        }
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
