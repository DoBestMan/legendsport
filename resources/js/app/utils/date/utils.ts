import moment from "moment";

export const toDateTime = (value: string): string =>
    value ? moment(value).format("MMM, DD [AT] HH:mm [ET]") : "n/a";
