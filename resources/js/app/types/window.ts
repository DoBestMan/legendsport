import { Tournament } from "./tournament";
import { StorableWindow } from "../utils/local-storage/LocalStorageManager";

export interface Window extends Required<StorableWindow> {
    tournament: Tournament;
}
