<template>
  <div>
    <b-modal
      id="job_order_filter"
      ref="job_order_filter"
      :title="getTitleForm"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols class="place-switch-button">
          <span style="margin-right: 5px">Bulan</span>
          <input
            type="checkbox"
            id="switch"
            v-model="params.is_date_filter"
            @click="onChangeTypeTime"
          />
          <label for="switch">Toggle</label>
          <span style="margin-left: 5px">Tanggal</span>
        </b-col>
        <b-col style="text-align: right;">
          <b-button variant="danger" size="sm" @click="onResetFilter()">Bersihkan Filter</b-button>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Tanggal" label-for="date" v-if="params.is_date_filter">
            <DatePicker
              id="date"
              v-model="params.date"
              format="YYYY-MM-DD"
              type="date"
              placeholder="pilih Tanggal"
              style="width: 100%"
              @change="onChangeMonth()"
            />
          </b-form-group>
          <b-form-group label="Bulan" label-for="month" v-else>
            <DatePicker
              id="month"
              v-model="params.month"
              format="YYYY-MM"
              type="month"
              placeholder="pilih Bulan"
              style="width: 100%"
              @change="onChangeMonth()"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Pilih Proyek" label-for="project_id" class>
            <VueSelect
              id="project_id"
              class="cursor-pointer"
              v-model="params.project_id"
              :options="getOptionProjects"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Pilih Status" label-for="status" class>
            <VueSelect
              id="status"
              class="cursor-pointer"
              v-model="params.status"
              :options="getOptionStatuses"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Pilih Data Berdasarkan" label-for="created_by" class>
            <VueSelect
              id="created_by"
              class="cursor-pointer"
              v-model="params.created_by"
              placeholder="Pilih Data Berdasarkan"
              :options="getOptionCreateByes"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group label="Kata Kunci" label-for="search" class>
            <input
              id="search"
              name="search"
              type="text"
              v-model="params.search"
              placeholder="search..."
              style="width: 100%"
              class="form-control"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" size="sm" @click="onCloseModal()">Tutup</b-button>
          <b-button variant="success" size="sm" class="float-end" @click="onSend()">Kirim</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import VueSelect from "vue-select";

export default {
  data() {
    return {
      getTitleForm: "Filter Data",
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
    getOptionStatuses() {
      return this.$store.state.jobOrder.options.statuses;
    },
    getOptionCreateByes() {
      return this.$store.state.jobOrder.options.create_byes;
    },
    getOptionProjects() {
      return this.$store.state.project.data_options;
    },
    params() {
      return this.$store.state.jobOrder.params;
    },
  },
  watch: {
    getOptionProjects(newValue, oldValue) {
      this.$store.commit("jobOrder/INSERT_PARAM_PROJECT_ID", {
        project_id: "all",
      });
    },
  },
  methods: {
    onResetFilter() {
      this.$store.commit("jobOrder/RESET_FILTER");
    },
    onCloseModal() {
      this.$bvModal.hide("job_order_filter");
    },
    onChangeTypeTime() {
      //   console.info(this.params.is_date_filter);
    },
    onChangeMonth() {
      //   console.info(this.params.month);

      this.$store.dispatch("project/fetchDataBaseRunning", {
        month: this.params.month,
      });
    },
    onSend() {
      this.$bvModal.hide("job_order_filter");

      this.$store.dispatch("jobOrder/fetchData");
      this.$store.dispatch("jobOrder/fetchDataOvertimeBaseUser", {
        user_id: this.getUserId,
      });
      this.$store.dispatch("jobOrder/fetchDataOvertimeBaseEmployee", {
        user_id: this.getUserId,
      });
    },
  },
};
</script>

<style lang="scss" scoped>
input[type="checkbox"] {
  height: 0;
  width: 0;
  visibility: hidden;
}

label {
  cursor: pointer;
  text-indent: -9999px;
  width: 40px;
  height: 20px;
  background: grey;
  display: block;
  border-radius: 10px;
  position: relative;
}

label:after {
  content: "";
  position: absolute;
  top: 1px;
  left: 1px;
  width: 18px;
  height: 18px;
  background: #fff;
  border-radius: 9px;
  transition: 0.3s;
}

input:checked + label {
  background: #bada55;
}

input:checked + label:after {
  left: calc(100% - 1px);
  transform: translateX(-100%);
}

label:active:after {
  width: 24px;
}

.place-switch-button {
  display: inline-flex;
}
</style>
