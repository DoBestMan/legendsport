import intersection from "lodash.intersection";

export const intersects = (a?: any[], b?: any[]): boolean => !empty(intersection(a || [], b || []));

export const empty = (a: any): boolean => !a?.length;

export const mapEnumToSelecOptions = (value: object) =>
    Object.values(value).map(value => ({ id: value, name: value }));
