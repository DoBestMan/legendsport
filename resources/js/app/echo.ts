import { Echo } from "./utils/websockets/Echo";

export default new Echo({
    broadcaster: "pusher",
    key: window.echo.key,
    namespace: "",
    wsHost: "ws." + window.location.hostname,
    wsPort: window.echo.port,
    disableStats: true,
    enabledTransports: ['ws', 'wss']
});
