<template>
    <div class="row">
        <div class="col-4" v-show="false">
            <div class="card card-default h-100">
                <div class="card-header">Active Users</div>
                <div class="card-body">
                    <ul>
                        <li class="py-2" v-for="(usr, index) in users" :key="index">{{ usr.name }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card card-default h-100">
                <div class="card-header">Messages</div>
                <div class="card-body p-0">
                    <ul class="list-unstyled" style="overflow-y:scroll">
                        <li class="p-2" :class="{ 'text-success': chat.user.id === user.id }" :style="{ textAlign: chat.user.id === user.id ? 'right' : 'left' }" v-for="(chat, index) in chats" :key="index">
                            <strong>{{ chat.user.name || '' }}:</strong>
                            {{ chat.message }}
                        </li>
                    </ul>
                </div>
                <input type="text" v-model="chat" @keyup.enter="sendMessage" name="message" placeholder="Enter message here..." class="form-control">
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'chat',
    //props: ['user'],
    data() {
        return {
            chats: [],
            users: [],
            chat: '',
            user: {},
        };
    },
    created() {
        this.fetchUser();
        this.fetchUsers();
        this.fetchMessages();
    },
    mounted() {
        Echo.join('chat').listen('SendChat', (event) => {
            this.chats.push(event.chat);
        });
    },
    methods: {
        sendMessage() {
            if(this.chat !== ''){
                this.chats.push({
                    user: this.user,
                    message: this.chat,
                });
                axios.post('chats', {
                    message: this.chat,
                });
                this.chat = '';
            }
        },
        fetchMessages(){
            axios.get('chats').then(res => {
                this.chats = res.data;
            });
        },
        fetchUser(){
            axios.get('user').then(res => {
                this.user = res.data;
            });
        },
        fetchUsers(){
            axios.get('users').then(res => {
                this.users = res.data;
            });
        },

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
