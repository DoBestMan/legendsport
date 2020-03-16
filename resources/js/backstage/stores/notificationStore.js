import Vue from "vue";

const LOCALSTORAGE_KEY = "notification";

class NotificationStore {
    constructor() {
        this._notifications = [];
    }

    info(message) {
        this._addNotification("info", message);
    }

    errorSync(message) {
        Vue.prototype.$toast.error(message, {
            showProgress: false,
            rtl: false,
            timeOut: 5000,
            closeable: true,
        });
    }

    loadAndShow() {
        this._load();

        for (const { type, message } of this._notifications) {
            Vue.prototype.$toast[type](message, {
                showProgress: false,
                rtl: false,
                timeOut: 5000,
                closeable: true,
            });
        }

        this._clear();
    }

    _addNotification(type, message) {
        this._notifications.push({ type, message });
        this._persist();
    }

    _persist() {
        localStorage.setItem(LOCALSTORAGE_KEY, JSON.stringify(this._notifications));
    }

    _load() {
        const data = localStorage.getItem(LOCALSTORAGE_KEY);

        if (data) {
            this._notifications = JSON.parse(data);
        }
    }

    _clear() {
        this._notifications = [];
        localStorage.removeItem(LOCALSTORAGE_KEY);
    }
}

const notificationStore = new NotificationStore();
export default notificationStore;
