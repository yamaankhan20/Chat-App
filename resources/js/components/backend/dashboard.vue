<template>
    <div class="container-fluid">
        <div class="row g-0">
            <div class="col-xxl-3 col-xl-4 col-md-5 box-col-5">
                <div class="left-sidebar-wrapper card">
                    <div class="left-sidebar-chat">
                        <div class="input-group"><span class="input-group-text"><i class="search-icon text-gray" data-feather="search"></i></span>
                            <input class="form-control" type="text" placeholder="Search here..">
                        </div>
                    </div>
                    <div class="advance-options">
                        <ul class="nav border-tab" id="chat-options-tab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="chats-tab" data-bs-toggle="tab" href="#chats" role="tab" aria-controls="chats" aria-selected="true">Chats</a></li>
                        </ul>
                        <div class="tab-content" id="chat-options-tabContent">
                            <div class="tab-pane fade show active" id="chats" role="tabpanel" aria-labelledby="chats-tab">
                                <div class="common-space">
                                    <p>Recent chats</p>
                                    <div class="header-top"><a class="btn badge-light-primary f-w-500" href="#!"><i class="fa fa-plus"></i></a></div>
                                </div>
                                <ul class="chats-user">
                                    <li
                                        class="common-space"

                                        v-for="user in users"
                                        v-bind:key="user.id"
                                        v-on:click.prevent="show_chat_panel(user, current_user, chat_all_messages)"
                                        :value="user.id"
                                    >
                                        <div class="chat-time">
                                            <div class="active-profile"><img class="img-fluid rounded-circle" :src="image1" alt="user">
                                                <div class="status bg-success"></div>
                                            </div>
                                            <div> <span>{{ user.name }}</span>
                                                <p>Hey, How are you?</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p>2 min </p>
                                            <div class="badge badge-light-success">15</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-9 col-xl-8 col-md-7 box-col-7">
                <ChatPanelView
                    v-for="panel in ChatPanel.panels"
                    :key="panel.selectedUser.id"
                    :user="panel.selectedUser"
                    :emittedMessage="panel.messages"
                    :current-user-id="panel.currentUserID"
                    @onCloseChat="HideChatPanel"

                />
            </div>
        </div>
    </div>
</template>

<script>

import {provide, ref, getCurrentInstance, reactive, inject, onMounted} from "vue";
import axios from 'axios';
import ChatPanelView from "@/components/backend/chatpanel.vue";
import echoInstance  from "@/bootstrap.js";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';


window.Pusher = Pusher;

export default {
    components: {ChatPanelView},
    data() {
        return {
            image1: "http://127.0.0.1:8080/dashboardassets/assets/images/avtar/3.jpg",
        };
    },
    props:['auth'],

    setup(props){

        const instance = getCurrentInstance();
        const apiUrl = instance.appContext.config.globalProperties.$apiUrl;
        const auth = props.auth;
        provide('auth', auth );

        const onlineUser = ref([]);


        // store array of chat panel when clicked per user!!!!
        let ChatPanel = reactive(
            {
                panels: [

                ]
            }
        );

        async function get_Online_users(){

            const response = await axios.get(apiUrl+'get-online-users/?user_id='+props.auth.id);
            if (response.data.users){
                onlineUser.value = response.data.users;
            }
        }
        get_Online_users();

        // onMounted(()=>{
        //     if (auth) {
        //         const channel = echoInstance.channel('chat');
        //         channel.listen('.message', (data) => {
        //             const message = data.messages;
        //             const currentUser = onlineUser.value
        //             var list_data = [];
        //
        //             currentUser.forEach((value) => {
        //                 const selectedUser = ChatPanel.panels[0]?.selectedUser;
        //                 if (selectedUser && selectedUser.id === value.id) {
        //                     list_data.push({ id: value.id });
        //                 }
        //             });
        //
        //             message.forEach((value, key) => {
        //
        //                 const isMessageForCurrentUser = list_data.some((user) => user.id === value.sender_id || user.id === value.receiver_id);
        //
        //                 if (isMessageForCurrentUser) {
        //                     const currentUser = onlineUser.value.find(user => user.id === value.sender_id || user.id === value.receiver_id);
        //                     if (currentUser) {
        //                         show_chat_panel(currentUser, props.auth.id, message);
        //                     }
        //                 }
        //             });
        //         });
        //     }
        //
        // });

        function show_chat_panel(user, currentUserID, EmittedMessage = null){
            const IsPanelOpen = ChatPanel.panels.find(panel => panel.selectedUser.id === user.id);

            // const messagesArray = Array.isArray(EmittedMessage) ? EmittedMessage : [EmittedMessage];
            if (!IsPanelOpen) {
                const userPanel = {
                    selectedUser: user,
                    messages: EmittedMessage,
                    currentUserID : currentUserID
                };
                ChatPanel.panels= [userPanel];
                return true;
            }

            const user_index = ChatPanel.panels.findIndex(panel => panel.selectedUser.id === user.id);
            ChatPanel.panels[user_index] = {...ChatPanel.panels[user_index], messages: EmittedMessage};
            return false;
        }



        function HideChatPanel(user){
            const filteredChat = [...ChatPanel.panels].filter( panel=> panel.selectedUser.id !== user.id);

            ChatPanel.panels=[...filteredChat];
        }


        return{
            users : onlineUser,
            current_user: props.auth.id,
            show_chat_panel,
            HideChatPanel,
            ChatPanel,
            chat_all_messages: ChatPanel.panels[0]
        }
    }
}
</script>
<style>
ul.chats-user {
    align-items: start !important;
}
</style>
