<template>
  <div>
    <b-modal
      id="detail_modal"
      ref="detail_modal"
      :title="title"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col>
          <span>{{getForm.employee_name}} ({{getForm.position_name}})</span>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <DatatableClient
            :data="getData"
            :columns="columns"
            :options="options"
            nameStore="attendance"
            nameLoading="detail"
            :filter="true"
            :footer="false"
            bordered
          >
            <template v-slot:filter>
              <b-col cols>
                <b-button variant="danger" size="sm" @click="onDelete()">Hapus Data Finger</b-button>
              </b-col>
            </template>
            <template v-slot:tbody="{ filteredData }">
              <b-tr v-for="(item, index) in filteredData" :key="index">
                <b-td>{{item.datetime_readable}}</b-td>
              </b-tr>
            </template>
          </DatatableClient>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import DatatableClient from "../../components/DatatableClient";

export default {
  data() {
    return {
      is_loading: false,
      title: "Daftar jam absensi",
      options: {
        perPage: 5,
        // perPageValues: [5, 10, 25, 50, 100],
      },
      columns: [
        {
          label: "Waktu",
          field: "datetime_readable",
          width: "200px",
          class: "",
        },
      ],
    };
  },
  components: {
    DatatableClient,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user.id;
    },
    getData() {
      return this.$store.state.attendance.data.detail;
    },
    getLoading() {
      return this.$store.state.attendance.loading.base_employee;
    },
    getForm() {
      return this.$store.state.attendance.form;
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("detail_modal");
    },
    async onDelete() {
      const data = this.getForm;
      //   console.info(this.getForm);

      const Swal = this.$swal;
      await Swal.fire({
        title: "Perhatian!!!",
        html: `Anda yakin ingin hapus <h2><b>${data.employee_name}</b> pada tanggal ${data.date_selected} ?</h2>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya hapus",
        cancelButtonText: "Batal",
      }).then(async (result) => {
        if (result.isConfirmed) {
          await axios
            .post(`${this.getBaseUrl}/api/v1/attendance/delete`, {
              employee_id: data.employee_id,
              date: data.date_selected,
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

                this.$bvModal.hide("detail_modal");
                this.$store.dispatch("attendance/fetchData");
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
  },
};
</script>

<style lang="scss" scoped>
</style>
