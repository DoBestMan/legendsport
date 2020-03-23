import intersection from "lodash.intersection";

export const intersects = (a?: any[], b?: any[]): boolean => !empty(intersection(a || [], b || []));

export const empty = (a: any): boolean => !a?.length;

export const mapEnumToSelecOptions = (value: object) =>
    Object.values(value).map(value => ({ id: value, name: value }));

export const isReallyNaN = (value: any) => value !== value;

export const asNumber = (value: any): number | null => {
    if (value === null || value === undefined || value === "") {
        return null;
    }

    const output = Number(value);

    return isReallyNaN(output) ? null : output;
};

export const groupBy = <T, K extends string | number>(
    items: ReadonlyArray<T>,
    cb: (item: T) => K,
): Record<K, T[]> => {
    return items.reduce((carry, item) => {
        const key = cb(item);
        carry[key] = [...(carry[key] ?? []), item];
        return carry;
    }, {} as Record<K, T[]>);
};
