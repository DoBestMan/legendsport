import { RootState, UpdateFieldPayload } from "./types";
import Vue from "vue";

export const mapFields = <
    N extends keyof RootState = keyof RootState,
    F extends keyof RootState[N] = keyof RootState[N]
>(
    namespace: N,
    fields: Array<F>,
) => fields.reduce((carry, field) => ({ ...carry, ...mapField<N, F>(namespace, field) }), {});

export function mapField<
    N extends keyof RootState = keyof RootState,
    F extends keyof RootState[N] = keyof RootState[N]
>(namespace: N, field: F) {
    return {
        [field]: {
            get(this: Vue): any {
                return this.$stock.state[namespace][field];
            },

            set(this: Vue, value: any) {
                const payload: UpdateFieldPayload<any> = { field, value };
                this.$stock.commit(`${namespace}/updateField`, payload);
            },
        },
    };
}

export const updateField = <T>(state: T, payload: UpdateFieldPayload<T>): void => {
    state[payload.field] = payload.value;
};
