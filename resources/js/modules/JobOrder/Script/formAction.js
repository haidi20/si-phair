import axios from "axios";
import moment from "moment";

import { imageToBase64, checkNull, listStatus } from "../../../utils";

import EmployeeHasParent from "../../EmployeeHasParent/view/employeeHasParent";
import ModalOvertimeFinishConfirm from "../View/modalOvertimeFinishConfirm";

import io from "socket.io-client";

export default {
    data() {
        return {
            is_loading: false,
            title_form: "Konfirmasi Lembur Selesai",
        };
    },
    components: {
        EmployeeHasParent,
        ModalOvertimeFinishConfirm,
    },
    computed: {
        getBaseUrl() {
            return this.$store.state.base_url;
        },
        getUserId() {
            return this.$store.state.user?.id;
        },
        getUserGroupName() {
            return this.$store.state.user?.group_name;
        },
        getTitleForm() {
            return this.$store.state.jobOrder.form.form_title;
        },
        getFormKind() {
            return this.$store.state.jobOrder.form.form_kind;
        },
        getEmployeeSelecteds() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        getOptionOvertimeRest() {
            return this.$store.state.jobOrder.options.overtime_rests;
        },
        form() {
            return this.$store.state.jobOrder.form;
        },
    },
    methods: {
        onShowEmployee() {
            this.$bvModal.show("data_employee");
        },
        onCloseModal() {
            this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                form_title: "Job Order",
                form_kind: null,
            });
            this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                value: false,
            });
        },
        onConfirm() {
            if (this.form.form_kind == 'overtime_finish') {
                this.$store.commit("jobOrder/UPDATE_FORM_IS_OVERTIME_REST", {
                    value: true,
                });

                this.$bvModal.show("overtime_finish_confirm_modal");
            } else {
                this.onSend();
            }
        },
        async onSend() {
            const Swal = this.$swal;
            // const statusBaseOnFormKind = listStatus[this.getFormKind] ? listStatus[this.getFormKind].status_last : this.getFormKind;
            let getEmployeeSelecteds = this.getEmployeeSelecteds
                .filter(item =>
                    item.hasOwnProperty('status_last')
                );

            if (this.getFormKind == 'overtime') {
                getEmployeeSelecteds = getEmployeeSelecteds.filter(item =>
                    item.status == 'overtime'
                );
            } else if (this.getFormKind == 'assessment') {
                getEmployeeSelecteds = getEmployeeSelecteds.filter(item =>
                    item.status == 'finish'
                );
            }

            this.$store.commit("jobOrder/INSERT_FORM_STATUS", {
                status: this.getFormKind,
            });

            // console.info(this.getFormKind);
            // console.info(this.getEmployeeSelecteds, getEmployeeSelecteds);

            const request = {
                id: this.form.id,
                date: moment(this.form.date).format("YYYY-MM-DD"),
                hour: this.form.hour,
                status: this.form.status,
                status_last: this.form.status_last,
                status_finish: this.form.status_finish,
                status_note: this.form.status_note,
                is_assessment_qc: this.form.is_assessment_qc,
                is_overtime_rest: this.form.is_overtime_rest,
                employee_selecteds: [...getEmployeeSelecteds],
                user_id: this.getUserId,
            };

            if (this.form.image != null) {
                request.image = await imageToBase64(this.form.image);
            }

            let urlAction = "store-action";

            if (request.status == 'assessment') {
                urlAction = "store-action-assessment";
            }

            // console.info(request);
            // return false;
            this.is_loading = true;

            await axios
                .post(`${this.getBaseUrl}/api/v1/job-order/${urlAction}`, request)
                .then((responses) => {
                    // console.info(responses);
                    this.is_loading = false;
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

                        this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                            form_title: "Job Order",
                            form_kind: null,
                        });
                        this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                            value: false,
                        });
                        this.$store.dispatch("jobOrder/fetchData");
                    }

                    this.onSendNotif();
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
        onSendNotif() {
            // console.info("send notif");
            const baseUrlService = this.$store.getters["getBaseUrlService"](this.getBaseUrl);

            this.socket = io.connect(`${baseUrlService}`, {}); // replace with your server URL

            this.socket.emit(`send_user_id`, {
                user_id: this.getUserId,
            });
        },
        getConditionDisableDate() {
            let result = false;
            const listStatus = ["overtime", 'pending', 'pending_finish'];


            if (listStatus.some((item) => item == this.form.form_kind)) {
                result = true;
            }

            // console.info(this.form.form_kind);

            return result;
        },
        getConditionImage() {
            let result = true;

            // console.info(this.form.status);

            if (this.form.status == 'pending' || this.form.status_last == 'pending') {
                result = false;
            }

            return result;
        },
        getLabelNote() {
            let result = "Catatan";

            if (this.getFormKind == 'assessment') {
                result = "Catatan Penilaian";
            }

            return result;
        },
        getConditionIsAssessmentQc() {
            let result = false;

            // console.info(this.form);

            if (this.getUserGroupName != 'Quality Control' && this.form.form_kind == 'assessment') {
                result = true;
            }

            return result;
        },
    },
};
