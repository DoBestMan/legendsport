<template>
    <div class="chat-frm">
        <div class="title">
            Chat
        </div>

        <div class="content">
            <template v-for="message in messages">
                <IncomingMessage
                    v-if="isIncoming(message)"
                    :key="message.id"
                    :timestamp="message.timestamp"
                    :user="message.userName"
                    :message="message.message"
                    :participant="message.isParticipant"
                />

                <OutcomingMessage v-else :key="message.id" :message="message.message" />
            </template>
        </div>

        <form v-if="canSendMessages" class="footer" @submit.prevent="sendMessage">
            <div class="input-group">
                <input
                    class="form-control input-message"
                    placeholder="Message..."
                    v-model="text"
                    required
                />
                <button type="submit" class="btn button-action">Send</button>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { ChatMessage } from "../../types/chat";
import IncomingMessage from "./IncomingMessage.vue";
import OutcomingMessage from "./OutcomingMessage.vue";

export default Vue.extend({
    name: "ChatContainer",
    components: { IncomingMessage, OutcomingMessage },

    props: {
        messages: Array as PropType<ChatMessage[]>,
    },

    data() {
        return {
            text: "",
        };
    },

    computed: {
        canSendMessages(): boolean {
            return !!this.$stock.state.user.user;
        },
    },

    methods: {
        sendMessage() {
            this.$emit("sendMessage", this.text);
            this.text = "";
        },

        isIncoming(message: ChatMessage): boolean {
            const user = this.$stock.state.user.user;
            return !user || message.userId !== user.id;
        },
    },
});
</script>
