import BaseEcho from "laravel-echo";
import { IEvent } from "./IEvent";

export class Echo extends BaseEcho {
    public sendEvent(event: IEvent, token?: string) {
        const { eventName, ...data } = event;
        this.connector.pusher.send_event(eventName, { data, token });
    }
}
