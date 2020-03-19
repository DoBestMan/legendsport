import { Route } from "vue-router";
import { Position, PositionResult } from "vue-router/types/router";

export const scrollBehavior = (
    to: Route,
    _from: Route,
    savedPosition: Position | void,
): PositionResult => {
    if (savedPosition) {
        return savedPosition;
    }

    if (to.hash) {
        return {
            selector: to.hash,
            offset: { x: 0, y: 10 },
        };
    }

    return { x: 0, y: 0 };
};
