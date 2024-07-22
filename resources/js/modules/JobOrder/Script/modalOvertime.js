import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";
// Main JS (in UMD format)
import VueTimepicker from 'vue2-timepicker'

// CSS
import 'vue2-timepicker/dist/VueTimepicker.css'

import DatatableClient from "../../../components/DatatableClient";

export default {
    props: {
        isJobOrder: Boolean,
    },
    data() {
        return {
            is_loading: false,
            title_form: "SPL (surat perintah lembur)",
            options: {
                perPage: 5,
                // perPageValues: [5, 10, 25, 50, 100],
            },
            columns: [
                {
                    label: "Waktu Mulai",
                    field: "datetime_start_readable",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Waktu Selesai",
                    field: "datetime_end_readable",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Durasi",
                    field: "duration_readable",
                    width: "200px",
                    class: "",
                },
            ],
        };
    },
    components: {
        VueSelect,
        VueTimepicker,
        DatatableClient,
    },
    computed: {
        getBaseUrl() {
            return this.$store.state.base_url;
        },
        getUserId() {
            return this.$store.state.user?.id;
        },
        getGroupName() {
            return this.$store.state.user?.group_name;
        },
        getDataOvertime() {
            return this.$store.state.jobOrder.table.overtime_base_user;
        },
        getOptionEmployees() {
            return this.$store.state.employeeHasParent.data.options;
        },
        getOptionOvertimeRest() {
            return this.$store.state.jobOrder.options.overtime_rests;
        },
        form() {
            return this.$store.state.jobOrder.form;
        },
    },
    methods: {
        onCloseModal() {
            this.$bvModal.hide("overtime_modal");
        },
        async onSend() {
            const Swal = this.$swal;

            let request = {
                id: this.form.id,
                note: this.form.note,
                employee_id: this.form.employee_id,
                date_start: moment(this.form.date_start).format("YYYY-MM-DD"),
                hour_start: this.form.hour_start_overtime,
                date_end: moment(this.form.date_end).format("YYYY-MM-DD"),
                hour_end: this.form.hour_end_overtime,
                user_id: this.getUserId,
                is_overtime_rest: this.form.is_overtime_rest,
                // user_id: 1000,
            };

            if (request.hour_start?.HH || request.hour_end?.HH) {
                request.hour_start = `${request.hour_start.HH}:${request.hour_start.mm}`;
                request.hour_end = `${request.hour_end.HH}:${request.hour_end.mm}`;
            }

            // console.info(request);
            // return false;
            this.is_loading = true;


            await axios
                .post(
                    `${this.getBaseUrl}/api/v1/job-status-has-parent/store-overtime`,
                    request
                )
                .then((responses) => {
                    console.info(responses);
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

                        this.$bvModal.hide("overtime_modal");
                        this.$store.dispatch("jobOrder/fetchDataOvertimeBaseUser", {
                            user_id: this.getUserId,
                        });
                        this.$store.commit("jobOrder/CLEAR_FORM");

                        // console.info(`${this.isJobOrder}`);
                        if (!this.isJobOrder) {
                            // khusus untuk laporan SPL
                            this.$store.dispatch("jobOrder/fetchDataOvertimeReport");
                        }
                    }

                    this.is_loading = false;
                })
                .catch((err) => {
                    console.info(err);

                    this.is_loading = false;

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 5000,
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
