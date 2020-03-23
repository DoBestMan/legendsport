import { Tournament } from "./tournament";
import { StorableTab } from "../utils/local-storage/LocalStorageManager";

export interface Tab extends Required<StorableTab> {
    tournament: Tournament;
}
