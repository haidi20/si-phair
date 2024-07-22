import VueSelect from "vue-select";

export default {
    data() {
        return {
            is_show_form_overtime_finish: false,
            getOptionOvertimeRest: [
                {
                    id: true,
                    name: "Ya",
                },
                {
                    id: false,
                    name: "Tidak",
                },
            ],
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
        getOptionEmplyeeBases() {
            return this.$store.state.employeeHasParent.options.employee_bases;
        },
        getOptionEmployees() {
            return this.$store.state.employeeHasParent.data.options;
        },
        getOptionPositions() {
            return this.$store.state.master.data.positions;
        },
        getJobOrderStatus() {
            return this.$store.state.jobOrder.form.status;
        },
        getJobOrderStatusClone() {
            return this.$store.state.jobOrder.form.status_clone;
        },
        getJobOrderFormKind() {
            return this.$store.state.jobOrder.form.form_kind;
        },
        getOptionJobOrders() {
            return this.$store.state.jobOrder.data.map((item) => ({
                ...item,
                name: item.project_name,
            }));
        },
        getIsMobile() {
            return this.$store.state.employeeHasParent.is_mobile;
        },
        getData() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        form() {
            return this.$store.state.employeeHasParent.form;
        },
    },
    watch: {
        getData(value, oldValue) {
            //
        },
    },
    methods: {
        onChoose() {
            if (this.form.employee_id == null || this.form.employee_id == "")
                return false;

            const checkData = this.getData.find(
                (item) => item.employee_id == this.form.employee_id
            );

            // jika sudah ada datanya tidak perlu di masukkan lagi
            if (!checkData) {
                // console.info(this.getJobOrderFormKind);
                const getEmployee = this.getOptionEmployees.find(
                    (item) => item.id == this.form.employee_id
                );
                const employee = {
                    employee_id: getEmployee.id,
                    employee_name: getEmployee.name,
                    position_name: getEmployee.position_name,
                    created_by: this.getUserId,
                    status: "active",
                    status_data: 'new',
                };

                // console.info(getEmployee);
                this.$store.commit("employeeHasParent/INSERT_DATA_SELECTED", { employee });

                this.$store.commit("employeeHasParent/DELETE_FORM_EMPLOYEE_ID");
            } else {
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

                // console.info(checkData);

                Toast.fire({
                    icon: "warning",
                    title: `Maaf, karyawan atas nama ${checkData.employee_name} sudah dipilih`,
                });
            }
        },
        getConditionTime() {
            let result = false;
            const getEmployeeStatusActive = this.getData.some(item => item.status == 'active');
            const getEmployeeStatusFinish = this.getData.some(item => item.status == 'finish' && item.status != item.status_clone);
            console.info(this.getData);

            if ((this.getJobOrderStatus == 'overtime' && getEmployeeStatusActive) || getEmployeeStatusFinish) {
                result = true;
            }

            // result = true;

            return result;
        },
        getConditionNontop() {
            let result = false;
            const getEmployeeStatusOvertime = this.getData.some(item => item.status_clone == 'overtime' && item.status == 'active');

            if (getEmployeeStatusOvertime) {
                result = true;
            }

            return result;
        }
    },
};
