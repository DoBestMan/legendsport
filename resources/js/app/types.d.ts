import { Store } from "vuex";
import { RootState } from "./store/types";
import { Echo } from "./utils/websockets/Echo";

declare module "vue/types/vue" {
    interface Vue {
        $stock: Store<RootState>;
        $echo: Echo;
    }
}
