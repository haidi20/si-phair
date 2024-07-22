<template>
  <div>
    <b-row>
      <b-col cols>
        <b-form-group label="Bulan" label-for="month" class="place_filter_table">
          <DatePicker id="month" v-model="params.month" format="YYYY-MM" type="month" />
        </b-form-group>
        <b-button
          class="place_filter_table"
          variant="success"
          size="sm"
          @click="onFilter()"
          :disabled="getIsLoadingData || is_loading_export"
        >Kirim</b-button>
        <b-button
          v-if="getCan('ekspor excel laporan cuti')"
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
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";

export default {
  data() {
    return {
      is_loading_export: false,
    };
  },
  components: {
    VueSelect,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getIsLoadingData() {
      return this.$store.state.vacationReport.loading.table;
    },
    getOptionStatuses() {
      return this.$store.state.vacationReport.options.statuses;
    },
    params() {
      return this.$store.state.vacationReport.params;
    },
  },
  methods: {
    onFilter() {
      this.$store.dispatch("vacationReport/fetchData", {
        user_id: this.getUserId,
      });
    },
    async onExport() {
      const Swal = this.$swal;
      this.is_loading_export = true;

      await axios
        .get(`${this.getBaseUrl}/report/vacation/export`, {
          params: {
            ...this.params,
            user_id: this.getUserId,
            date_start: moment(this.params.date[0]).format("Y-MM-DD"),
            date_end: moment(this.params.date[1]).format("Y-MM-DD"),
          },
        })
        .then((responses) => {
          console.info(responses);
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
    getCan(permissionName) {
      const getPermission = this.$store.getters["getCan"](permissionName);

      return getPermission;
    },
  },
};
</script>
<style lang="scss" scoped>
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
