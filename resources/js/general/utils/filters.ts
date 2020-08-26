import moment from "moment";

export const formatCurrency = (value: number): string => {
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    });
    return formatter.format(value / 100);
};

export const formatDollars = (value: number): string => {
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        minimumFractionDigits: 0,
    });
    return formatter.format(value / 100);
};

export const formatChip = (value: number): string => {
    const formatter = new Intl.NumberFormat("en-US");
    return formatter.format(Math.floor(value));
};

export const toDateTime = (value: string): string =>
    value ? moment.utc(value).local().format("MMM, DD [AT] HH:mm [ET]") : "n/a";

export const capitalize = (value: string): string => value.replace(/^\w/, c => c.toUpperCase());
