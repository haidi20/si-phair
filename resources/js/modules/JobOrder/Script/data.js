import axios from "axios";
import moment from "moment";

import { checkNull, isMobile } from "../../../utils";
import FilterData from "../View/filter";
import DatatableClient from "../../../components/DatatableClient";
import EmployeeHasParent from "../../EmployeeHasParent/view/employeeHasParent";
import ModalOvertime from "../View/modalOvertime";
import { stubArray } from "lodash";

export default {
    data() {
        return {
            isChecked: true,
            title: "",
            messageNotif: null,
            options: {
                perPage: 5,
                // perPageValues: [5, 10, 25, 50, 100],
            },
            columns: [
                {
                    label: "Nama",
                    field: "employee_name",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Jabatan",
                    field: "position_name",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Durasi",
                    field: "duration_readable",
                    width: "200px",
                    class: "",
                },
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
            ],
        };
    },
    components: {
        FilterData,
        ModalOvertime,
        DatatableClient,
        EmployeeHasParent,
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
        getData() {
            return this.$store.state.jobOrder.data;
        },
        getDataEmployeeSelected() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        getDataOvertimeBaseEmployee() {
            return this.$store.state.jobOrder.table.overtime_base_employee;
        },
        getLoadingData() {
            return this.$store.state.jobOrder.loading.data;
        },
        getFormStatus() {
            return this.$store.state.jobOrder.form.status;
        },
        getIsMobile() {
            return isMobile();
        },
        getForm() {
            return this.$store.state.jobOrder.form;
        },
        getParams() {
            return this.$store.state.jobOrder.params;
        },
    },
    watch: {
        getBaseUrl(value) {
            if (value != null) {
                this.$store.dispatch("jobOrder/fetchData", { user_id: this.getUserId });
                this.$store.dispatch("project/fetchDataBaseRunning", { month: this.getParams.month });
            }
        },
        getUserGroupName(value) {
            if (value != null) {
                if (value == 'Quality Control') {
                    this.$store.commit("jobOrder/INSERT_PARAM_CREATED_BY", { created_by: 'another_foreman' });
                }
            }
        }
    },
    methods: {
        onOpenAction(form) {
            // console.info(form);
            this.$store.commit("jobOrder/INSERT_FORM", {
                form
            });

            this.$store.commit("employeeHasParent/UPDATE_IS_MOBILE", {
                value: true,
            });
            this.$store.commit("employeeHasParent/INSERT_DATA_ALL_SELECTED", {
                selecteds: [
                    ...form.job_order_has_employees,
                ],
            });
            this.$bvModal.show("action_list");
        },
        onShowEmployee() {
            let status = null;

            if (this.getUserId == this.getForm.created_by) {
                if (this.getFormStatus == 'overtime') {
                    status = 'read';
                }
                // else {
                //     status = "edit";
                // }
                // } else {
                //     status = "read";
            }

            // console.info(status);

            this.$store.commit("employeeHasParent/INSERT_FORM_FORM_TYPE", {
                form_type: status,
                form_type_parent: status,
            });
            this.$store.commit("employeeHasParent/UPDATE_IS_DISABLED_BTN_SAVE", {
                value: true,
            });
            this.$bvModal.show("data_employee");
            this.$bvModal.hide("action_list");
        },
        onAction(type, title) {
            this.$bvModal.hide("action_list");
            this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                form_title: "Job Order - " + title,
                form_kind: type,
            });
            // this.$store.commit("jobOrder/INSERT_FORM_STATUS", {
            //     status: type,
            // });
            this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                value: true,
            });
            this.$store.commit("jobOrder/CLEAR_FORM_ACTION");
            this.$store.commit("jobOrder/INSERT_FORM_STATUS", { status: type });
            this.$store.commit("employeeHasParent/INSERT_FORM_FORM_TYPE", {
                form_type: "read",
                // form_type_parent: "overtime",
                form_type_parent: type,
            });
            this.$store.dispatch("employeeHasParent/onUpdateStatusDataSelected", { form_type: type });

            if (type == 'overtime') {
                this.$store.commit("jobOrder/UPDATE_FORM_IS_OVERTIME_REST", {
                    value: true,
                });

                this.$store.commit("employeeHasParent/UPDATE_DATA_ALL_SELECTED_STATUS_OVERTIME");
            }

            //   console.info(this.form);
        },
        onActionAssessment(type, title) {
            const checkEmployeeStatus = this.getCheckEmployeeStatus();

            // console.info(checkEmployeeStatus);

            if (checkEmployeeStatus) {
                return this.getCheckEmployeeStatus(true);
            }

            this.$bvModal.hide("action_list");
            this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                form_title: "Job Order - " + title,
                form_kind: type,
            });
            this.$store.commit("jobOrder/INSERT_FORM_STATUS", {
                status: type,
            });
            this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                value: true,
            });
            this.$store.commit("jobOrder/CLEAR_FORM_ACTION");
            this.$store.dispatch("employeeHasParent/onUpdateStatusDataSelected", { form_type: type });

            //   console.info(this.form);

            this.$bvModal.show("job_order_form_action");
        },
        onCreate() {
            this.$store.commit("jobOrder/CLEAR_FORM");
            this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                form_title: "Tambah Job Order",
                form_kind: "create",
            });
            this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                value: true,
            });
            this.$store.commit("employeeHasParent/CLEAR_DATA_SELECTED");
            this.$store.commit("employeeHasParent/UPDATE_IS_MOBILE", {
                value: true,
            });
            this.$store.commit("employeeHasParent/INSERT_FORM_FORM_TYPE", {
                form_type: "create",
                form_type_parent: "create",
            });
        },
        onRead() {
            this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                form_title: "Lihat Job Order",
                form_kind: "read",
            });
            this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                value: true,
            });
            this.$store.commit("employeeHasParent/INSERT_FORM_FORM_TYPE", {
                form_type: "read",
                form_type_parent: "read",
            });
            this.$bvModal.hide("action_list");
        },
        onEdit() {
            this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                form_title: "Ubah Job Order",
                form_kind: "edit",
            });
            this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                value: true,
            });
            this.$store.commit("jobOrder/UPDATE_IS_DISABLED_BTN_SEND", {
                value: false,
            });
            this.$store.commit("employeeHasParent/INSERT_FORM_FORM_TYPE", {
                form_type: "edit",
                form_type_parent: "edit",
            });
            this.$bvModal.hide("action_list");
        },
        onOpenOvertime() {
            this.$store.commit("jobOrder/UPDATE_FORM_IS_OVERTIME_REST", {
                value: true,
            });

            this.$bvModal.show("overtime_modal");
        },
        onFilter() {
            this.$bvModal.show("job_order_filter");
        },
        async onDelete() {
            const data = this.getForm;
            // console.info(data);

            const Swal = this.$swal;
            await Swal.fire({
                title: "Perhatian!!!",
                html: `Anda yakin ingin hapus Job Order <h2><b>${data.project_name}</b> ?</h2>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya hapus",
                cancelButtonText: "Batal",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await axios
                        .post(`${this.getBaseUrl}/api/v1/job-order/delete`, {
                            id: data.id,
                            user_id: this.getUserId,
                        })
                        .then((responses) => {
                            //   console.info(responses);
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

                                this.$store.dispatch("jobOrder/fetchData", { user_id: this.getUserId });
                                this.$bvModal.hide("action_list");
                            }
                        })
                        .catch((err) => {
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
                }
            });
        },
        onLimitSentence(sentence) {
            const maxLength = 35;

            // console.info(sentence);

            if (checkNull(sentence) != null) {
                if (sentence.length > maxLength) {
                    sentence = sentence.substring(0, maxLength) + "...";
                }

                return sentence;
            }
        },
        getConditionActionActive() {
            let result = false;
            const listStatus = ["pending"];

            if (this.getFormStatus != null) {
                if (listStatus.some(item => item == this.getFormStatus)) {
                    result = true;
                }
            }

            return result;
        },
        getConditionPending() {
            let result = false;

            if (
                this.getFormStatus == 'active'
                && this.getForm.created_by == this.getUserId
            ) {
                result = true;
            }

            return result;
        },
        getConditionEdit() {
            let result = false;
            const listStatus = ['finish'];

            // console.info(this.getFormStatus);

            if (
                this.getFormStatus != 'finish'
                && this.getForm.created_by == this.getUserId
            ) {
                result = true;
            }

            return result;
        },
        getNameLabelAssessment() {
            // console.info(this.getUserGroupName);

            if (this.getUserGroupName == 'Pengawas') {
                return "Selesai";
            } else {
                return "Penilaian";
            }
        },
        getCheckEmployeeStatus(isShowAlert) {
            const result = this.getDataEmployeeSelected.some(item => item.status == "pending");

            // console.info(this.getData);

            if (result && isShowAlert) {
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
                    icon: "warning",
                    title: "Maaf, salah satu karyawan masih belum aktif.",
                });

                return false;
            }

            return result;
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
