import moment from "moment";

export const toDateTime = (value: string): string =>
    moment(value).format("MMM, DD [AT] HH:mm [ET]");
