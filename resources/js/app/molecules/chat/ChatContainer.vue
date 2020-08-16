<template>
    <div class="layout__content__sidebar__chat">
        <div class="layout__content__sidebar__chat__cta">
            <div class="layout__content__sidebar__chat__cta__title">
                <i class="icon icon--micro icon--chat icon--color--light-1 m--r--2"></i>
                <!-- Todo: count of messages -->
                CHAT({{ messages.length }})
            </div>
            <div class="layout__content__sidebar__chat__cta__action" @click="handleChatExpand">
                <i class="icon icon--micro icon--expand icon--color--light-2"></i>
            </div>
        </div>

        <div v-show="isChatExpanded">
            <div class="layout__content__sidebar__chat__container">
                <div class="layout__content__sidebar__chat__container__messages">
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
            </div>

            <form
                v-if="canSendMessages"
                class="layout__content__sidebar__chat__container__input"
                @submit.prevent="sendMessage"
            >
                <div class="form">
                    <div class="form__control">
                        <input
                            class="input"
                            placeholder="Send Message..."
                            v-model="text"
                            required
                        />
                    </div>
                </div>
                <button class="button button--small button--yellow m--l--4 f--1">SEND</button>
            </form>
        </div>
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
            isChatExpanded: true,
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

        handleChatExpand(): void {
            this.isChatExpanded = !this.isChatExpanded;
        },
    },
});
</script>
