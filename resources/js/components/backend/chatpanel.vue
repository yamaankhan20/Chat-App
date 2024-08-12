<template>
    <div class="card right-sidebar-chat">
        <div class="right-sidebar-title">
            <div class="common-space">
                <div class="chat-time">
                    <div class="active-profile"><img class="img-fluid rounded-circle"  alt="user">
                        <div class="status bg-success"></div>
                    </div>
                    <div> <span>{{ user.name }}</span>
                        <p>Online </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <div class="contact-edit chat-alert" @click="$emit('onCloseChat',user)"><i class="icon-info-alt"></i></div>
                    <div class="contact-edit chat-alert">
                        <svg class="dropdown-toggle" role="menu" data-bs-toggle="dropdown" aria-expanded="false">
                            <use></use>
                        </svg>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#!">View details</a><a class="dropdown-item" href="#!">
                            Send messages</a><a class="dropdown-item" href="#!">
                            Add to favorites</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-sidebar-Chats">
            <div class="msger">
                <div class="msger-chat" ref="chatcontentRef">

                    <MessageLine
                        v-for="(message, index) in userMessages"
                        :key="message.id"
                        :message="message"
                        :current-id="currentUserId"
                        :index="index"
                        :value="user.id"
                    />
                </div>
                <div class="msger-inputarea">
                    <div class="dropdown-form dropdown-toggle" role="main" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-plus"></i>
                        <div class="chat-icon dropdown-menu dropdown-menu-start">
                            <div class="dropdown-item mb-2">
                                <svg>
                                    <use></use>
                                </svg>
                            </div>
                            <div class="dropdown-item">
                                <svg>
                                    <use></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <input
                        class="msger-input two uk-textarea"
                        type="text"
                        placeholder="Type Message here.."
                        v-model="messageContent"
                        @keyup.enter="SendMessage"
                    >
                    <div class="open-emoji">
                        <div class="second-btn uk-button"></div>
                    </div>
                    <a
                        class="msger-send-btn"
                        @click.prevent="SendMessage"
                    ><i class="fa fa-location-arrow"></i></a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MessageLine from "@/components/backend/messageline.vue";
import {ref, getCurrentInstance, provide, onMounted, onBeforeUnmount, watch} from "vue";
import axios from "axios";
import pusher from "@/bootstrap.js";
import echoInstance  from "@/bootstrap.js";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default {
    name: "ChatPanelView",
    components: {MessageLine},
    props:{
        user: Object,
        emittedMessage: Array,
        currentUserId: Number
    },
    emits:['onCloseChat'],
    setup(props){

        const instance = getCurrentInstance();
        const apiUrl = instance.appContext.config.globalProperties.$apiUrl;

        const messageContent = ref('');
        const userMessages = ref([]);
        const chatcontentRef = ref(null);

        const {user} = props;


        const SendMessage = async () => {
            if (!messageContent.value) {
                return;
            }
            const payload = {
                current_user: props.currentUserId,
                receiver_id: props.user.id,
                message_content: messageContent.value
            };
            try {
                const response = await axios.post(`${apiUrl}send-message`, payload);
                if (response.data.status) {
                    messageContent.value = '';
                    fetchMessages();
                }
            }catch (error){
                console.error(error);
            }

        };

        const fetchMessages = async () => {
            try {
                const response = await axios.get(`${apiUrl}get-messages/?receiver_id=${props.user.id}&sender_id=${props.currentUserId}`);
                if (response.data.message) {

                    userMessages.value = response.data.message.reverse();
                    scrollchattobottom();
                }
            }catch (error){
                console.error(error);
            }

        };
        fetchMessages();
        function scrollchattobottom(){
            setTimeout(()=>{
                if (chatcontentRef && chatcontentRef.value){
                    chatcontentRef.value.scrollTop = chatcontentRef.value.scrollHeight;
                }
            },1);

        }

        onMounted(() => {

            const pusher = new Pusher('9887c2651dba1c04c0ef', {
                cluster: 'ap2',
                encrypted: true,
            });

            var map_channel = pusher.subscribe(`chat.${props.currentUserId}`);
            map_channel.bind('message', function(data) {

                console.log(data.message)
                userMessages.value.push(data.message);
                scrollchattobottom();
            });
        });
        return{
            messageContent,
            SendMessage,
            userMessages,
            chatcontentRef
        }
    }


}
</script>
