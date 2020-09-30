import Vue from "vue";
import {BNavItemDropdown, NavPlugin} from "bootstrap-vue";

Vue.use(NavPlugin);
Vue.component('b-nav-item-dropdown', BNavItemDropdown)

new Vue({
    el: "#nav",

    components: {
        BNavItemDropdown,
    }
})
