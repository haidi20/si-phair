import axios from "axios";
import moment from "moment";

const defaultForm = {
    employee_id: null,
    employee_name: null,
    position_id: null,
    position_name: null,
    date_selected: null,
}

const Attendance = {
    namespaced: true,
    state: {
        base_url: null,
        data: {
            main: [],
            base_employee: [],
            finger: [],
            detail: [],
        },
        params: {
            main: {
                position_id: 'all',
                company_id: 'all',
                month: new Date(),
            },
            base_employee: {
                employee_id: null,
                month: new Date(),
            },
            finger: {
                month: new Date(),
                date: new Date(),
            },
        },
        form: { ...defaultForm },
        options: {
            //
        },
        loading: {
            main: false,
            base_employee: false,
            finger: false,
            detail: false,
        },
        date_range: [],
    },
    mutations: {
        INSERT_BASE_URL(state, payload) {
            state.base_url = payload.base_url;
        },
        INSERT_DATA_MAIN(state, payload) {
            state.data.main = payload.data;
        },
        INSERT_DATA_BASE_EMPLOYEE(state, payload) {
            state.data.base_employee = payload.data;
        },
        INSERT_DATA_FINGER(state, payload) {
            state.data.finger = payload.data;
        },
        INSERT_DATA_DETAIL(state, payload) {
            state.data.detail = payload.data;
        },
        INSERT_DATE_RANGE(state, payload) {
            state.date_range = payload.date_range;
        },
        INSERT_FORM(state, payload) {
            state.form = { ...payload.data };
            state.form.date_selected = payload.date;
        },
        UPDATE_LOADING_MAIN(state, payload) {
            state.loading.main = payload.value;
        },
        UPDATE_LOADING_BASE_EMPLOYEE(state, payload) {
            state.loading.base_employee = payload.value;
        },
        UPDATE_LOADING_FINGER(state, payload) {
            state.loading.finger = payload.value;
        },
        UPDATE_LOADING_DETAIL(state, payload) {
            state.loading.detail = payload.value;
        },
    },
    actions: {
        fetchData: async (context, payload) => {
            context.commit("UPDATE_LOADING_MAIN", { value: true });

            const params = {
                ...context.state.params.main,
                month: moment(context.state.params.main.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/attendance/fetch-data-main`, {
                    params: { ...params },
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA_MAIN", {
                        data: data.data,
                    });
                    context.commit("INSERT_DATE_RANGE", {
                        date_range: data.dateRange,
                    });
                    context.commit("UPDATE_LOADING_MAIN", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_MAIN", { value: false });
                    console.info(err);
                });
        },
        fetchBaseEmployee: async (context, payload) => {
            context.commit("UPDATE_LOADING_BASE_EMPLOYEE", { value: true });

            const params = {
                ...context.state.params.base_employee,
                month: moment(context.state.params.base_employee.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/attendance/fetch-data-base-employee`, {
                    params: { ...params },
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA_BASE_EMPLOYEE", {
                        data: data.data,
                    });
                    context.commit("UPDATE_LOADING_BASE_EMPLOYEE", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_BASE_EMPLOYEE", { value: false });
                    console.info(err);
                });
        },
        fetchDataBaseFinger: async (context, payload) => {
            context.commit("UPDATE_LOADING_FINGER", { value: true });

            context.commit("INSERT_DATA_FINGER", {
                data: [],
            });

            const params = {
                ...context.state.params.finger,
                month: moment(context.state.params.finger.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/attendance/fetch-data-finger`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;
                    // console.info(typeof data.data, typeof data.dateRange);
                    // const data_fingers = Object.keys(data.data).map(key => ({ key, value: data.data[key] }));

                    // console.info(data.data);

                    context.commit("INSERT_DATA_FINGER", {
                        data: data.data,
                    });

                    context.commit("UPDATE_LOADING_FINGER", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_FINGER", { value: false });
                    console.info(err);
                });
        },
        fetchDataDetail: async (context, payload) => {
            context.commit("UPDATE_LOADING_DETAIL", { value: true });
            const data_finger = payload.data_finger?.map(item => ({ pin: item.pin, cloud_id: item.cloud_id, }));

            const params = {
                data_finger: payload.data_finger ? data_finger : null,
                date: moment(payload.date).format("Y-MM-DD"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/attendance/fetch-data-detail`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    if (data.data.length) {
                        context.commit("INSERT_DATA_DETAIL", {
                            data: data.data,
                        });
                    } else {
                        context.commit("INSERT_DATA_DETAIL", {
                            data: [],
                        });
                    }

                    context.commit("UPDATE_LOADING_DETAIL", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_DETAIL", { value: false });
                    console.info(err);
                });
        },
    }
}

export default Attendance;
