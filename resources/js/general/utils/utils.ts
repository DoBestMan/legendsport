import intersection from "lodash.intersection";

export const intersects = (a?: any[], b?: any[]): boolean =>
    !!intersection(a || [], b || []).length;
