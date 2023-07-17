<script>
    $(document).ready(function() {
        let conversationItem = '.conversationItem';
        let selectedConversation = '.selectedConversaction';
        let chatDiv = '.chat-about';
        let chatList = '.chat-list';
        let searchInput = '.searchInput';
        let getMessagesRoute = "{{ route('chat.getMessages') }}";
        let storeMessageRoute = "{{ route('chat.storeMessage') }}";
        let updateCounterRoute = "{{ route('chat.updateCounter', 'record') }}";
        let userId = '{{$userId}}';
        let chatHistory = '.chat-history';
        let historyList = '.historyList';
        let messageInput = '.messageInput';
        let submitMessageClass = '.submitMessage';
        let disabledDivClass = 'disabledDiv';
        let badgesCounter = '.badgesCounter';
        let lastMessage = 'lastMessage';
        var leftPanelList = $(chatList).html();
        const socket = io('http://localhost:3000');
        $(document).on('click', conversationItem, function() {
            $(chatList).html(leftPanelList);
            $(searchInput).val("");
            $(conversationItem).parent().find('.active').removeClass('active');
            let chatId = $(this).attr('chatId');
            $(conversationItem).parent().find('.'+chatId+'_chatItem').addClass('active');
            $(selectedConversation).attr('from', $(this).attr('from'));
            $(selectedConversation).attr('to', $(this).attr('to'));
            $(selectedConversation).attr('chatId', chatId);
            $(selectedConversation).find('img').attr('src', $(this).attr('image'));
            $(selectedConversation).find('.'+lastMessage).text($(this).attr(lastMessage));
            $(chatDiv).find('.name').text($(this).attr('name'));
            $('.historyList').empty()
            getMessages(chatId);
            setTimeout(() => {scrollToBottom();}, 100);
            $(submitMessageClass).parent().parent().find('.'+disabledDivClass).removeClass(disabledDivClass);
            setBadge(chatId,0);
            updateTheCounter(chatId,0);
            setTimeout(() => {leftPanelList = $(chatList).html();}, 100);
        });
        async function appendData(data) {
            if (data && data.list.length > 0) {
                var historyList = $('.historyList');
                data.list.forEach(function(message) {
                    var messageClass = message.sended_from == userId ? 'message other-message float-right' : 'message other-message';
                    var messageHtml = '<li class="clearfix"><div class="' + messageClass + '">' + message.content + '</div></li>';
                    historyList.append(messageHtml);
                });
            }
        }
        async function setBadge(chatId,counter) {
            var parentElement = $(conversationItem).parent();
            var selector = '.' + chatId + '_chatItem ' + badgesCounter;
            var badgesCounterElement = parentElement.find(selector);
            var textNumber = badgesCounterElement.text();
            textNumber = (textNumber ? parseInt(textNumber) + 1 : 1);
            var returnNumber = (parseInt(counter) >= 0 ? counter :textNumber);
            badgesCounterElement.text((returnNumber == 0 ? '' : returnNumber));
            return returnNumber;
        }
        const submitMessage = document.getElementsByClassName('submitMessage')[0];
        const innerInput = document.querySelector(messageInput);
        submitMessage.addEventListener('click', handleSubmit);
        innerInput.addEventListener('keydown', handleKeyDown);
        function handleSubmit(e) {
            e.preventDefault();
            const getMessageInput = document.querySelector(messageInput);
            const message = getMessageInput.value.trim();
            if (message) {
                let sedingData = {from: $(selectedConversation).attr('from'), to: $(selectedConversation).attr('to'), chatId: $(selectedConversation).attr('chatId'), message: message}
                socket.emit('newMessage', JSON.stringify(sedingData));
                getMessageInput.value = '';
                storeMessage(sedingData);
            }
        }
        function handleKeyDown(e) {
            if (e.key === 'Enter') {
                handleSubmit(e);
            }
        }
        socket.on('newMessage', (response) => {
            refreshOpenChat(response);
            setTimeout(() => {scrollToBottom();}, 100);
        });
        async function getMessages(chatId) {
            try {
                const response = await axios.get(getMessagesRoute, {
                    params: {chatId: chatId}
                });
                appendData(response.data)
            } catch (error) {
                console.error('Error storing message:', error);
            }
        }
        async function scrollToBottom() {
            innerchatHistory = document.querySelector(chatHistory);
            innerchatHistory.scrollTop = innerchatHistory.scrollHeight;
            // $(chatHistory).scrollTop($(chatHistory)[0].scrollHeight)
        }
        async function storeMessage(messageData) {
            try {
                const response = await axios.post(storeMessageRoute,messageData);
            } catch (error) {
                console.error('Error storing message:', error);
            }
        }
        async function updateTheCounter(chatId,counter) {
            try {
                let url = updateCounterRoute.replace('record', chatId);
                const response = await axios.put(url,{chatId: parseInt(chatId), counter:counter});
            } catch (error) {
                console.error('Error storing message:', error);
            }
        }
        async function refreshOpenChat(data) {
            let openChat = $(selectedConversation).attr('chatId');
            let newMessage = data.message;
            let responseChatId = data.chatId;
            if(openChat == responseChatId && newMessage) {
                let selectedFrom = $(selectedConversation).attr('from');
                $(selectedConversation).find('.'+lastMessage).text(newMessage);
                var historyList = $('.historyList');
                var messageClass = data.from == selectedFrom ? 'message other-message float-right' : 'message other-message';
                var messageHtml = '<li class="clearfix"><div class="' + messageClass + '">' + newMessage + '</div></li>';
                historyList.append(messageHtml);
                var selector = '.' + openChat + '_chatItem ' + badgesCounter;
            } else {
                openChat = responseChatId;
                let textNumber = await setBadge(openChat,"");
                updateTheCounter(openChat,textNumber);
            }
            $(conversationItem).parent().find('.'+openChat+'_chatItem').find('.'+lastMessage).text(newMessage);
            var listItem = $('.' + openChat + '_chatItem ');
            listItem.prependTo(listItem.parent());
            leftPanelList = $(chatList).html();
        }
        $(searchInput).on('keyup', function() {
            var searchKeyword = $(this).val().trim().toLowerCase();
            if (searchKeyword !== '') {
                var filteredList = $(chatList+' li').filter(function() {
                    var name = $(this).find('.name').text().trim().toLowerCase();
                    return name.includes(searchKeyword);
                });
                $(chatList).html(filteredList);

            } else {
                $(chatList).html(leftPanelList);
            }
        });
    });
</script>
@stack('script')
