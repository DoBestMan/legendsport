import Vue from "vue";
import axios from "axios";
import ToastsPlugin from "vue-bootstrap-toasts";
import VMoney from "v-money";
import { formatChip, formatCurrency, formatDollars, toDateTime } from "../../general/utils/filters";

export const setup = () => {
    axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
    Vue.use(ToastsPlugin);
    Vue.use(VMoney);
    Vue.filter("formatChip", formatChip);
    Vue.filter("formatCurrency", formatCurrency);
    Vue.filter("formatDollars", formatDollars);
    Vue.filter("toDateTime", toDateTime);
};
