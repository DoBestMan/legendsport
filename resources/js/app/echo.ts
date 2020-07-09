import { Echo } from "./utils/websockets/Echo";

declare var echo: any;

export default new Echo({
    broadcaster: "pusher",
    key: echo.key,
    namespace: "",
    wsHost: "ws." + window.location.hostname,
    wsPort: echo.port,
    disableStats: true,
    enabledTransports: ['ws', 'wss']
});
