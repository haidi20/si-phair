import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";

export default {
    props: {
        menu: {
            type: String,
            default: "kasbon",
        },
    },
    data() {
        return {
            is_loading: false,
            getTitleForm: "Buat Kasbon",
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
        getGroupName() {
            return this.$store.state.user?.group_name;
        },
        getOptionEmployees() {
            return this.$store.state.employeeHasParent.data.options;
        },
        getOptionPaymentMethods() {
            return this.$store.state.salaryAdvanceReport.options.payment_methods;
        },
        getFormSalaryAdvanceReport() {
            return this.$store.state.salaryAdvanceReport.form;
        },
        form() {
            return this.$store.state.salaryAdvance.form;
        },
        loan_amount: {
            get() {
                return this.$store.state.salaryAdvance.form.loan_amount_readable;
            },
            set(value) {
                this.$store.commit("salaryAdvance/INSERT_FORM_LOAN_AMOUNT", {
                    loan_amount: value,
                });
            },
        },
        monthly_deduction: {
            get() {
                return this.$store.state.salaryAdvanceReport.form
                    .monthly_deduction_readable;
            },
            set(value) {
                this.$store.commit(
                    "salaryAdvanceReport/INSERT_FORM_MONTHLY_DEDUCTION",
                    {
                        monthly_deduction: value,
                    }
                );
            },
        },
    },
    methods: {
        onCloseModal() {
            this.$bvModal.hide("salary_advance_form");
        },
        async onSend() {
            const Swal = this.$swal;
            let url = `${this.getBaseUrl}/api/v1/salary-advance/store`;

            const request = {
                ...this.form,
                monthly_deduction: this.getFormSalaryAdvanceReport.monthly_deduction,
                user_id: this.getUserId,
            };

            if (this.menu == "laporan kasbon") {
                url = `${this.getBaseUrl}/api/v1/salary-advance/store-by-pass-all-approval`;
            }

            // console.info(request);
            // return false;
            this.is_loading = true;
            await axios
                .post(`${url}`, request)
                .then((responses) => {
                    // console.info(responses);

                    this.is_loading = false;

                    //   return false;
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

                        if (this.menu == "laporan kasbon") {
                            this.$store.dispatch("salaryAdvanceReport/fetchData", {
                                user_id: this.getUserId,
                            });
                        } else {
                            this.$store.dispatch("salaryAdvance/fetchData", {
                                user_id: this.getUserId,
                            });
                        }

                        this.$store.commit("salaryAdvance/CLEAR_FORM");
                        this.$store.commit("salaryAdvanceReport/CLEAR_FORM");

                        // this.$bvModal.hide("salary_advance_form");
                    }
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
        getCan(permissionName) {
            const getPermission = this.$store.getters["getCan"](permissionName);

            return getPermission;
        },
    },
};
