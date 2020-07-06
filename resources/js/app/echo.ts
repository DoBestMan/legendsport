import { Echo } from "./utils/websockets/Echo";

export default new Echo({
    broadcaster: "pusher",
    key: "my-secret-random-key",
    namespace: "",
    wsHost: "ws." + window.location.hostname,
    wsPort: 80,
    disableStats: true,
});
