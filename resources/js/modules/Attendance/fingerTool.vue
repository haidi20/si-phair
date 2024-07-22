<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="attendance"
      nameLoading="finger"
      :filter="true"
      bordered
    >
      <template v-slot:filter>
        <b-col cols style="margin-bottom: 15px">
          <b-form-group label="Bulan" label-for="month" class="place_filter_table">
            <DatePicker
              id="month"
              v-model="params.month"
              format="YYYY-MM"
              type="month"
              placeholder="pilih bulan"
            />
          </b-form-group>
          <b-button
            class="place_filter_table"
            variant="success"
            size="sm"
            @click="onFilter()"
            :disabled="getIsLoadingData || is_loading_pull_data"
          >Kirim</b-button>
          <span v-if="getIsLoadingData">Loading...</span>
          <b-button
            class="place_filter_table"
            variant="info"
            size="sm"
            @click="onShowFormPullData()"
            :disabled="getIsLoadingData"
          >Tarik Data Finger</b-button>
          <span v-if="is_loading_pull_data">Loading...</span>
        </b-col>
      </template>
      <template v-slot:thead>
        <b-th
          v-for="(item, index) in getFingerTools"
          v-bind:key="`thead-${index}`"
          style="width: 30px"
        >{{ item.name }}</b-th>
      </template>
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td nowrap>{{ item.date_readable }}</b-td>
          <b-td v-for="(finger, key) in getFingerTools" :key="key">{{ item[finger.id] }}</b-td>
        </b-tr>
      </template>
    </DatatableClient>

    <b-modal
      id="form_pull_data"
      ref="form_pull_data"
      title="Tarik Data Finger"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label="Tanggal" label-for="date">
            <DatePicker
              id="date"
              v-model="params.date"
              format="YYYY-MM-DD"
              type="date"
              placeholder="pilih tanggal"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
        <b-col style="text-align: -webkit-right;">
          <span v-if="is_loading_pull_data">Loading...</span>
          <b-button
            :disabled="getIsLoadingData || is_loading_pull_data"
            variant="success"
            @click="onPullData()"
          >Kirim</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import _ from "lodash";
import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import DatatableClient from "../../components/DatatableClient";

export default {
  data() {
    return {
      is_loading_pull_data: false,
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
          label: "Tanggal",
          field: "date_readable",
          width: "100px",
          rowspan: 2,
          class: "",
        },
      ],
    };
  },
  components: {
    DatePicker,
    DatatableClient,
    VueSelect,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getData() {
      return this.$store.state.attendance.data.finger;
    },
    getFingerTools() {
      return this.$store.state.master.data.finger_tools;
    },
    getIsLoadingData() {
      return this.$store.state.attendance.loading.finger;
    },
    params() {
      return this.$store.state.attendance.params.finger;
    },
  },
  methods: {
    onFilter() {
      this.$store.dispatch("attendance/fetchDataBaseFinger");
    },
    onCloseModal() {
      this.$bvModal.hide("form_pull_data");
    },
    onShowFormPullData() {
      this.$bvModal.show("form_pull_data");
    },
    async onPullData() {
      const Swal = this.$swal;
      this.is_loading_pull_data = true;

      await axios
        .get(`${this.getBaseUrl}/api/v1/attendance/store`, {
          params: {
            user_id: this.getUserId,
            date_start: moment(this.params.date).format("Y-MM-DD"),
          },
        })
        .then((responses) => {
          //   console.info(responses);
          this.is_loading_pull_data = false;

          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "success",
            title: "Berhasil tarik data",
          });

          this.$store.dispatch("attendance/fetchData");
          this.$store.dispatch("attendance/fetchDataBaseFinger");
        })
        .catch((err) => {
          this.is_loading_pull_data = false;
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
    setLabelDate(date) {
      return moment(date).format("DD");
    },
    setLabelNameDate(date) {
      return moment(date).format("dddd");
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
.item-hour {
  height: 18px;
}
.hour-reguler {
  color: green;
}
.hour-rest {
  color: blue;
}
</style>
