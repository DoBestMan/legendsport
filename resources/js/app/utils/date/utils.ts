import moment from "moment";

export const toTime = (value: string): string => (value ? moment(value).format("LT") : "n/a");
