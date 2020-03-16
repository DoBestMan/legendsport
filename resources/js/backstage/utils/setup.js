import Vue from "vue";
import axios from "axios";
import ToastsPlugin from "vue-bootstrap-toasts";
import VMoney from "v-money";

export const setup = () => {
    axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
    Vue.use(ToastsPlugin);
    Vue.use(VMoney);
};
