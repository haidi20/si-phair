window.Vue = require("vue").default;
import store from "./stores/main";

import Vue from "vue";

import moment from "moment";
import BootstrapVue from "bootstrap-vue";

import "bootstrap-vue/dist/bootstrap-vue.css";

moment.locale("id");

Vue.component("notification", require("./modules/Notification/notification.vue").default);

Vue.use(BootstrapVue);

new Vue({
    store,
    el: "#notif",
});
