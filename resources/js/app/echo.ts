import { Echo } from "./utils/websockets/Echo";

export default new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    namespace: "",
    wsHost: window.location.hostname,
    wsPort: 6001,
    disableStats: true,
});
