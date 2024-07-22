<template>
  <div>
    <b-modal
      id="job_order_modal_image"
      ref="job_order_modal_image"
      :title="title_form"
      size="lg"
      class="modal-custom"
      hide-footer
    >
      <template v-if="getJobStatusHasParent.length > 0">
        <template v-for="(jobStatusHasParent, key) in getJobStatusHasParent">
          <b-row style="margin-bottom: 20px" :key="key">
            <b-col>
              <h4>{{jobStatusHasParent.status}}</h4>
              <b-row>
                <template v-if="jobStatusHasParent?.images.length > 0">
                  <b-col
                    cols="3"
                    v-for="(data, index) in jobStatusHasParent?.images"
                    :key="index"
                    class="place-image"
                  >
                    <img :src="data.image_review" alt class="image-review" />
                    <br />
                    <b-button
                      variant="success"
                      size="sm"
                      @click="onDownloadImage(data)"
                      style="margin-top: 10px"
                    >Download</b-button>
                  </b-col>
                </template>
                <template v-else>
                  <span>Tidak ada gambar</span>
                </template>
              </b-row>
            </b-col>
          </b-row>
          <hr :key="`hr-${key}`" />
        </template>
      </template>
      <template v-else-if="getLoadingJobStatusHasParent">
        <h4>Loading...</h4>
      </template>
      <template v-else>
        <h4>Tidak ada data</h4>
      </template>
      <br />
      <b-row class="float-right">
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      title_form: "Gambar Job Order",
    };
  },
  components: {
    //
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getJobStatusHasParent() {
      return this.$store.state.jobOrder.form.job_status_has_parent;
    },
    getLoadingJobStatusHasParent() {
      return this.$store.state.jobOrder.loading.job_status_has_parent;
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("job_order_modal_image");
    },
    onDownloadImage(data) {
      //   console.info(data);
      const url =
        this.getBaseUrl +
        `/job-status-has-parent/download-image?image_has_parent_id=${data.id}`;

      window.open(url, "_blank");
    },
  },
};
</script>

<style lang="scss" scoped>
.image-review {
  width: 10rem;
  height: 8rem;
}
.place-image {
  text-align: -webkit-center;
}
</style>
