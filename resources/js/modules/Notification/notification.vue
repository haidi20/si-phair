<template>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ms-auto mb-lg-0">
      <li class="nav-item dropdown me-3" v-if="is_show">
        <a
          class="nav-link active dropdown-toggle text-gray-600"
          href="#"
          data-bs-toggle="dropdown"
          data-bs-display="static"
          aria-expanded="false"
        >
          <i class="bi bi-bell bi-sub fs-4" style="padding-right: 5px;"></i>
          <span
            v-if="count_data > 0"
            class="badge bg-danger rounded-circle top-0 start-100 translate-middle"
            style="padding: 0.4rem 0.6rem!important;"
          >{{count_data}}</span>
        </a>
        <ul
          class="dropdown-menu dropdown-menu-end notification-dropdown"
          aria-labelledby="dropdownMenuButton"
        >
          <li class="dropdown-header">
            <h6>Pemberitahuan</h6>
          </li>
          <!-- <li class="dropdown-item notification-item">
            <a class="d-flex align-items-center" href="#">
              <div class="notification-icon bg-success">
                <i class="bi bi-file-earmark-check"></i>
              </div>
              <div class="notification-text ms-4">
                <p class="notification-title font-bold">Job Order : PROJECT HQ</p>
                <p class="notification-subtitle font-thin text-sm">Tersisa 10 Menit Lagi</p>
              </div>
            </a>
          </li>-->
          <template v-if="data.length > 0">
            <li v-for="(item, index) in data" :key="index" class="dropdown-item notification-item">
              <a class="d-flex align-items-center" href="#">
                <div class="notification-icon bg-success">
                  <i class="bi bi-file-earmark-check"></i>
                </div>
                <div class="notification-text ms-4">
                  <p class="notification-title font-bold">Proyek : {{item.project_name}}</p>
                  <p class="notification-subtitle font-thin text-sm">
                    Jenis Pekerjaan :
                    {{item.job_name}}
                  </p>
                  <!-- <p
                    class="notification-subtitle font-thin text-sm"
                  >{{item.datetime_estimation_end_readable}}</p>-->
                  <p
                    class="notification-subtitle font-thin text-sm"
                  >{{getDifferentTimeReadable(item)}}</p>
                </div>
              </a>
            </li>
          </template>
          <template v-else>
            <div class="notification-text ms-4">
              <span>tidak ada notif</span>
            </div>
          </template>
        </ul>
      </li>
    </ul>
  </div>
</template>

<script>
import io from "socket.io-client";
import moment from "moment";

export default {
  props: {
    user: String,
    baseUrl: String,
  },
  data() {
    return {
      is_show: false,
      count_data: 0,
      data: [],
    };
  },
  mounted() {
    const user = JSON.parse(this.user);
    this.$store.commit("INSERT_BASE_URL", { base_url: this.baseUrl });
    this.$store.commit("INSERT_USER", { user });

    ["master"].map((item) => {
      this.$store.commit(`${item}/INSERT_BASE_URL`, {
        base_url: this.baseUrl,
      });
    });
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url_service;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
  },
  created() {
    if (!this.is_show) return false;
    const user = JSON.parse(this.user);
    const baseUrl = this.baseUrl;
    const timeInterval = 60000; // 1 menit
    // const timeInterval = 5000; // 5 detik

    const options = {
      //   autoConnect: false,
      //   query: `user_id=${this.user_id}`,
      query: {
        // user_id: this.user_id,
        // timestamp: timestamp,
      },
    };

    const baseUrlService = this.$store.getters["getBaseUrlService"](baseUrl);

    this.socket = io.connect(`${baseUrlService}`, options); // replace with your server URL

    this.socket.emit(`send_user_id`, {
      user_id: user.id,
    });

    // listen to events from server
    this.socket.on(`get_notification`, (data) => {
      //   console.info(data);
      this.count_data = data?.data?.length;
      this.data = data.data;
      //   this.message = data;
    });
  },
  beforeDestroy() {
    // disconnect when component is unmounted
    this.socket.disconnect();
  },
  methods: {
    getDifferentTimeReadable(data) {
      const now = moment();
      const timeEnd = moment(data.datetime_estimation_end);
      const diffDuration = moment.duration(timeEnd.diff(now));
      const days = Math.abs(Math.floor(diffDuration.asDays())); // Use absolute value for days
      const hours = Math.abs(diffDuration.hours());
      const minutes = Math.abs(diffDuration.minutes());
      //   const seconds = diffDuration.seconds();

      //   console.log(`Result: ${days} days, ${hours} hours, ${minutes} minutes`);

      let result = "";

      if (days > 0) {
        result += `${days} Hari `;
      } else {
        if (hours > 0) {
          result += `${hours} Jam `;
        }

        if (minutes > 0) {
          result += `${minutes} Menit `;
        }
      }

      const isAfter = timeEnd.isAfter(now);

      return isAfter
        ? `Tersisa ${result} Lagi selesai`
        : `Sudah lewat ${result} yang lalu. Status masih aktif`;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
