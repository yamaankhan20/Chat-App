<template>
    <sender
        v-if="auth.id === message?.sender_id"
        :message="message"
    />
    <receiver
        v-else
        :message="message"
    />

</template>

<script>
import Receiver from "@/components/backend/receiver.vue";
import Sender from "@/components/backend/sender.vue";
import {inject} from "vue";

export default {
    name: "MessageLine",
    components: {Sender, Receiver},
    props: ['message', 'value'],

    setup(props) {

        const auth = inject('auth');

        return {
            auth
        }
    }
}
</script>
