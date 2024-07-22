<template>
  <div>
    <b-modal
      id="vacation_report_form"
      ref="vacation_report_form"
      :title="getTitleForm"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label="Tanggal Mulai" label-for="date_start">
            <DatePicker id="date_start" v-model="form.date_start" format="YYYY-MM-DD" type="date" />
          </b-form-group>
        </b-col>
        <b-col cols>
          <b-form-group label="Tanggal Selesai" label-for="date_end">
            <DatePicker id="date_end" v-model="form.date_end" format="YYYY-MM-DD" type="date" />
          </b-form-group>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col style="text-align: -webkit-right;">
          <span v-if="is_loading">Loading...</span>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
        <b-col style="text-align: -webkit-right;">
          <b-button variant="danger" @click="onApproval('reject')" :disabled="is_loading">Tolak</b-button>
          <b-button
            variant="success"
            @click="onApproval('accept')"
            style="margin-left: 20px;"
            :disabled="is_loading"
          >Terima</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";

export default {
  data() {
    return {
      is_loading: false,
      getTitleForm: "Persetujuan Cuti",
    };
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    form() {
      return this.$store.state.vacationReport.form;
    },
  },
  components: {
    //
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("vacation_report_form");
    },
    async onApproval(status) {
      //   console.info(status, this.form);

      const Swal = this.$swal;

      // mengambil data hexa saja
      const request = {
        ...this.form,
        status: status,
        date_vacation_start: moment(this.form.date_start).format("Y-MM-DD"),
        date_vacation_end: moment(this.form.date_end).format("Y-MM-DD"),
        user_id: this.getUserId,
      };

      //   console.info(request);

      //   return false;
      this.is_loading = true;
      await axios
        .post(`${this.getBaseUrl}/api/v1/vacation/storeApproval`, request)
        .then((responses) => {
          //   console.info(responses);
          const data = responses.data;
          this.is_loading = false;

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

            this.$bvModal.hide("vacation_report_form");
            this.$store.dispatch("vacationReport/fetchData");
          }
        })
        .catch((err) => {
          console.info(err);
          this.is_loading = false;

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
  },
};
</script>

<style lang="scss" scoped>
</style>
