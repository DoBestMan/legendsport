import { Store } from "vuex";
import { RootState } from "./store/types";

declare module "vue/types/vue" {
    interface Vue {
        $stock: Store<RootState>;
    }
}
