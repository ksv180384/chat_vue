import { io } from 'socket.io-client';
// import {userData} from '@/helpers/helpers.js';
import router from '@/router';
import { useRoomsStore } from '@/store/chats_rooms.js';
import { useUsersOnlineStore } from '@/store/users_online.js';
import { usePageStore } from '@/store/page.js';

const connectionOptions = {
  forceNew: true,
  reconnectionAttempts: "Infinity",
  timeout: 10000,
  autoConnect: false,
  transports : ["websocket", "polling"],
};
const socket = io('http://localhost:3077', connectionOptions);

// Событие получения сообщения, получают все, кто состоит хоть в одном из чатов с отправителем
socket.on('createChat', function(data){
  const roomsStore = useRoomsStore();
  roomsStore.pushChat(data.chat);
});

// Событие получения сообщения, получают все, кто состоит хоть в одном из чатов с отправителем
socket.on('message', function(data){
  console.log(data);
  const pageStore = usePageStore();
  const currentRoute = router.currentRoute.value;
  const selectChat = currentRoute.name === 'chat' ? +currentRoute.params.id : null;
  const chatId = +data.chat_room_id;

  // console.log(chatId, selectChat);
  // console.log(data);

  if(chatId === selectChat){
    pageStore.page.messages = [...pageStore.page.messages, data];
  }
});

// Когда пользователь присоединяется к чату, все пользователи (с которыми но состоит в чате) получают это саобытие
socket.on('joinUserChat', async (data) => {
  // console.log(data);
  const roomsStore = useRoomsStore();
  const usersOnlineStore =  useUsersOnlineStore();
  roomsStore.pushChat(data.chat_data);
  usersOnlineStore.setUsersIds(data.users_online);
  // store.commit('storeChatsList/pushChats', data.chat_data);
  // store.commit('setUsersOnline', data.users_online);
});

socket.on('addUserChat', async (data) => {
  const currentRoute = router.currentRoute.value;
  const addUserChatId = +data.chat_id;
  const selectChat = currentRoute.name === 'chat' ? +currentRoute.params.id : null;

  // console.log('kkkkkkkkkkkkkkkkkkkkk');
  // console.log(selectChat);
  // console.log(addUserChatId);
  if(selectChat === addUserChatId){
    const pageStore = usePageStore();
    // console.log(pageStore.page);
    pageStore.page.users = [...pageStore.page.users, data.join_user];
    //store.commit('storeChat/setUsers', data.chat_data.users);
  }
  const usersOnlineStore =  useUsersOnlineStore();
  usersOnlineStore.addId(data.join_user.id);
  // store.commit('addUserOnline', data.join_user.id);
});

// Когда пользователь отписывается от чата, все пользователи (с которыми но состоит в чате) получают это событие
socket.on('currentUserLaveChat', async (data) => {
  const usersOnlineStore = useUsersOnlineStore();
  const roomsStore = useRoomsStore();
  const currentRoute = router.currentRoute.value;
  const selectChat = currentRoute.name === 'chat' ? +currentRoute.params.id : null;
  const chatId = +data.chat_id;

  roomsStore.removeChat(chatId);
  usersOnlineStore.setUsersIds(data.users_online);
  if(selectChat === chatId){
    router.push({ name: 'chats-list' });
  }
});

// Когда пользователь отписывается от чата, все пользователи (с которыми но состоит в чате) получают это событие
socket.on('userLaveChat', async (data) => {
  const chatId = +data.chat_id;
  const userId = +data.user_id;
  const currentRoute = router.currentRoute.value;
  const selectChatId = currentRoute.name === 'chat' ? +currentRoute.params.id : null;
  const pageStore = usePageStore();

  if(selectChatId === chatId){
    pageStore.page.users = pageStore.page.users.filter(item => item.id !== userId);
  }
});

// Когда удаляют чат, пользователи состоящие в чате получаю это событие
socket.on('removeChat', async (data) => {
  // store.commit('storeChatsList/removeChat', +chatId);

  const chatId = +data.chat_id;
  const roomsStore = useRoomsStore();
  roomsStore.removeChat(chatId);

  const currentRoute = router.currentRoute.value;
  if(currentRoute.name === 'chat' && currentRoute.params.id === chatId){
    router.push('/');
  }
});

// При подключении к чату, обратно получаем это событие со всеми пользователями онлайн
socket.on('usersOnline', async (usersOnline) => {
  console.log('usersOnline', usersOnline);
  const usersOnlineStore =  useUsersOnlineStore();
  usersOnlineStore.setUsersIds(usersOnline);
});

// Кода пользователь подключился к чату, все пользователи (с которыми но состоит в чате) получают это событие
socket.on('userConnect', async (userId) => {
  console.log('userConnect', userId);
  const usersOnlineStore =  useUsersOnlineStore();
  usersOnlineStore.addId(userId);
});

// Когда пользователю отключается от чата, все пользователи (с которыми но состоит в чате) получают это событие
socket.on('userDisconnect', async (userId) => {
  const usersOnlineStore =  useUsersOnlineStore();
  usersOnlineStore.removeId(userId);
  // store.commit('removeUserOnline', userId);
});

// Событие срабатывает при потере соединения с сервером
socket.on('disconnect', async () => {
  // store.commit('setIsSocketConnect', false);
  const usersOnlineStore =  useUsersOnlineStore();
  usersOnlineStore.clearData();
});

export default socket;
