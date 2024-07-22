<template>
  <div>
    <b-modal
      id="overtime_finish_confirm_modal"
      ref="overtime_finish_confirm_modal"
      :title="title_form"
      size="sm"
      class="modal-custom"
      hide-footer
    >
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

<script>
export default {
  props: {
    onSend: Function,
  },
  data() {
    return {
      is_loading: false,
      title_form: "Konfirmasi Lembur Selesai",
    };
  },
  mounted() {
    //
  },
  computed: {
    getOptionOvertimeRest() {
      return this.$store.state.jobOrder.options.overtime_rests;
    },
    form() {
      return this.$store.state.jobOrder.form;
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("overtime_finish_confirm_modal");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
