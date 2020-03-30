declare module "vue-bootstrap-toasts" {
    import _Vue from "vue";

    export default class VueBootstrapToasts {
        static install(Vue: typeof _Vue, options: any): void;
    }

    module "vue/types/vue" {
        interface Vue {
            $toast: {
                error(message: string, options?: any): void;
                info(message: string, options?: any): void;
                success(message: string, options?: any): void;
                warning(message: string, options?: any): void;
            };
        }
    }
}
