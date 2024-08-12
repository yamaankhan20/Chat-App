<template>
    <div class="msg right-msg">
        <div class="msg-img"></div>
        <div class="msg-bubble">
            <div class="msg-info">
                <div class="msg-info-name">{{ message.sender.name }}</div>
                <div class="msg-info-time">{{ message.time_ago }}</div>
            </div>
            <div class="msg-text">{{ message.content }}</div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Sender",
    props: ['message'],

    setup(props){
        // console.log(props.message)
    }
}
</script>
