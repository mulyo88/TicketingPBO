$(function(){
    var baseurl = $('#containerMessage').data('baseurl');
    var imageProfile = baseurl+'images/chat/default.jpg'
    var btnBubbleChat = $('#btnBubble');
    var containerMessageKanan = $('.messageAtas')
    var poepleContainer = $('.people-container')
    var btnClosePeople = $('#btnClosePeople')
    var btnShowPeople = $('#btnShowPeople')
    var btnCloseChat = $('#btnCloseChat')
    var containerValueMessage = $('.value-message')
    var containerListMessage = $('.list-message')
    var roomListPeople = $('.list-people')
    var contResult = $('.result-search-people')
    var targetid = null
    var userid = $('#containerMessage').data('userlogin')
    var fileContainer = $('.file-container')
    var btnAtchFIle = $('.attch-file')
    var inputHiden = $('#inputHiden')
    var fileName = ''
    var btnCancelFile = $('.cancel-file')
    var iconBacktoHome = $('.back-to-home-message')
    var hakAkses = $('#containerMessage').data('hakakses')
    var footerChat = $('.messageAtas .footer')
    // const socket = io("http://localhost:3000");
    // const socket = io("http://127.0.0.1:3000/");
    // const socket = io("http://192.168.88.91:3000");
    const socket = io("http://147.139.178.49:3889");
    const audionotif = baseurl+'css/notif.mp3'; 
    const audio = new Audio(audionotif);
    const bubbleNotif = $('.notif-red')

    socket.on("connect", () => {
        console.log(socket.id);
    });

    function socketGetPeopleWithMessage(){
        socket.emit('getPeopleWithMessage', {idUser: userid})
        socket.on('getPeopleWithMessage', (data) => {
            containerListMessage.empty()
            $.each(data, (_, val) => {
                const sendData = {
                    idReceiver: val.receiver,
                    usernameReceiver: val.usernameReceiver,
                    message: val.latest_message,
                    is_login: val.is_login,
                }
                containerListMessage.append(templateListPeople(sendData))
            })
        })
    }

    function socketGetALlChat(targetId)
    {
        socket.off('getAllChat')
        socket.emit('getAllChat', {sender: userid, receiver:targetId})
        socket.once('getAllChat', (data) => {
            if (data.length >= 1) {
                $.each(data, (index, val) => {
                    var isoDateTime = parsingTime(val.time_chat)            
                    const cleanedSenderMessageId = val.sender_message_id.trim().toLowerCase();
                    const cleanedUsername = userid.trim().toLowerCase();
                    if (cleanedSenderMessageId == cleanedUsername) {
                        typeChat = 'send';
                    } else {
                        typeChat = 'receive';
                    }
                    if(val.type_message == 'text'){
                        containerValueMessage.append(templateChat(val.message, isoDateTime,  typeChat))
                    }else{
                        containerValueMessage.append(templateChatFile(val.message, isoDateTime, val.kode_file, typeChat))
                    }
                })
            }
        })
    }
    
    function socketGetALlUser(){
        socket.emit('getAllUser', {idUser: userid, hakAkses: hakAkses })
        socket.on('getAllUser', (data) =>{
           roomListPeople.empty()
            $.each(data, (_, val) => {
                roomListPeople.append(templateCardPeople(val))
            })
        })
    }

    socketGetPeopleWithMessage()

    socket.on('chat_message', (data) => {
        if (data.receiver == userid) {
            if(btnBubbleChat.css('display') == 'flex'){
                toastr.success(data.message, data.userNameSender, {timeOut: 5000,  positionClass: 'toast-bottom-right'})
            }else{
                toastr.success(data.message, data.userNameSender, {timeOut: 5000,  positionClass: 'toast-top-right'})
            }
            if(data.typeMessage == 'text'){
                containerValueMessage.append(templateChat(data.message, '','receive'))
            }else{
                containerValueMessage.append(templateChatFile(data.message, data.kodeFile, 'receive'))
            }
            scrollToBottom()
            // socket.emit('getPeopleWithMessage', {idUser: userid})
            audio.play().catch(function(error) {
                console.error("Error playing audio:", error.message);
            });
            bubbleNotif.css('display', 'block')
            socketGetPeopleWithMessage()
        }
    });

    $(document).on('click', '.card-people', function(e){
        e.preventDefault()
        containerMessageKanan.css('display', 'flex')
        containerValueMessage.empty()
        $('.name-profile').html($(this).data('targetname'))
        targetid = $(this).data('targetid')
        socketGetALlChat(targetid)
        iconBacktoHome.css('display', 'block')
        contResult.empty()
        contResult.css('display', 'none')
        containerListMessage.css('display', 'none')
        containerValueMessage.css('display', 'flex')
        footerChat.show()
        scrollToBottom()
    })
    $(document).on('click', '.card-people-message', function(e){
        e.preventDefault()
        containerMessageKanan.css('display', 'flex')
        containerValueMessage.empty()
        containerListMessage.css('display', 'none')
        containerValueMessage.css('display', 'flex')
        $('.name-profile').html($(this).data('targetname'))
        targetid = $(this).data('targetid')
        socketGetALlChat(targetid)
        iconBacktoHome.css('display', 'block')
        contResult.empty()
        contResult.css('display', 'none')
        footerChat.show()
        scrollToBottom()
    })


    $('#btn-send').click(function(e){
        e.preventDefault()
        var input = $(this).closest('.footer-container').find('input')
        if(fileContainer.css('display') == 'none'){
            var message = input.val()
            var typeMessage = 'text'
        }else{
            var typeMessage = 'file'
            var message = fileName
            var isiFile = $('#inputHiden')[0].files[0]
            var url = $('#inputHiden').data('urlform')
            var formData = new FormData()
            var kodeFile = generateUniqueCode()
            var extFile = fileName.split('.')
            var kodeFileJadi =  kodeFile + '.' + extFile[extFile.length - 1]
            formData.append('file', isiFile);
            formData.append('namafile',kodeFileJadi);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
        socket.emit('chat_message', {
            sender: userid,
            receiver: targetid,
            message: message,
            typeMessage: typeMessage,
            kodeFile: kodeFileJadi
        })
        if(containerValueMessage.css('display') == 'flex'){
            if(fileContainer.css('display') == 'none'){
                containerValueMessage.append(templateChat(message, '', 'send'))
            }else{
                containerValueMessage.append(templateChatFile(message, '', kodeFileJadi, 'send'))
            }
        }
        // socketGetALlChat(targetid)
        scrollToBottom()
        input.val('')
        fileContainer.css('display', 'none')
        socket.emit('getPeopleWithMessage', {idUser: userid})
    })

    $('#searchInput').on('input', function(e){
        var value = $(this).val()
        var hakAkses = $(this).data('hakakses')
        if(value.length >= 3){
            contResult.empty()
            socket.emit('searchuser', {value, hakAkses})
            socket.once('searchuser', (data) => {
                if(data.length >= 1){
                    $.each(data, (index, val) => {
                        contResult.append(templateCardUser(val, userid))
                    })
                    contResult
                    .css('display', 'flex')
                    .hide()
                    .fadeIn(500);
                }
            })
        }else{
            contResult.fadeOut(500, function () {
                $(this).empty().css('display', 'none');
            });
        }
    })

    $('#searchInput').focusout(function(){
        $(this).val('')
         contResult.fadeOut(500, function () {
                $(this).empty().css('display', 'none');
            });
    })

    $(document).on('click','#downloadFile',function(e){
        var kodeFile = $(this).attr('data-kodefile')
        var namaFile = $(this).attr('data-namafile')
        var url = baseurl+ 'images/chat/file/'+kodeFile;
        $.ajax({
            url: url,
            method: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = namaFile;
                document.body.append(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    })

    $(document).on('click', '.back-to-home-message', function(e){
        e.preventDefault()
        iconBacktoHome.css('display', 'none')
        socketGetPeopleWithMessage()
        containerValueMessage.css('display', 'none')
        footerChat.hide()
        $('.name-profile').html('Chat User')
        containerListMessage.css('display', 'flex')
    })

    btnBubbleChat.click(function(e){
        e.preventDefault()
        btnBubbleChat.css('display', 'none')
        poepleContainer.css('display', 'none')
        containerMessageKanan.css('display', 'flex')
        containerListMessage.css('display', 'flex')
        iconBacktoHome.css('display', 'none')
        bubbleNotif.css('display', 'none')
        btnShowPeople.css('display', 'flex')
        footerChat.hide()
    })
    btnShowPeople.click(function(e){
        $(this).hide()
        socketGetALlUser()
        poepleContainer.css('display', 'flex')
    })
    btnClosePeople.click(function(e){
        poepleContainer.css('display', 'none')
        if (containerMessageKanan.css('display') === 'flex') {
            btnBubbleChat.css('display', 'none');
            btnShowPeople.css('display', 'flex')
        } else {
            btnBubbleChat.css('display', 'flex');
        }
    })
    btnCloseChat.click(function(e){
        e.preventDefault()
        containerMessageKanan.css('display', 'none')
        poepleContainer.css('display', 'none')
        btnShowPeople.css('display', 'none')
        btnBubbleChat.css('display', 'flex')
        containerValueMessage.css('display', 'none')
        $('.name-profile').html('Chat User')
        socketGetPeopleWithMessage()
    })
    btnAtchFIle.click(function(e){
        inputHiden.click()
    })
    inputHiden.change(function(e){
        fileName = e.target.files[0].name
        var getFileName = fileContainer.find('.file-name')
        if(fileName.length >= 10){
             var truncatedText = fileName.substring(0, 20) + '...';
        }else{
            var truncatedText = fileName
        }
        fileContainer.css('display', 'flex')
        getFileName.html(truncatedText)
    })
    btnCancelFile.click(function(e){
        fileContainer.css('display', 'none')
    })


    function templateChat(message, time='', type) {
        return `<div class="${type}-message-container">
            <div><p>${message}<p></div>
            <div class="time"><p>${time}</p></div>
        </div>`
    }

    function templateChatFile(message, time, kodeFile, type){
        return `<div class="${type}-message-container">
            <div class="message-file">
                <i class="fa fa-file"></i>
                <p>${message.length > 20 ? message.substring(0,20)+'...' : message}</p>
                <button id="downloadFile" data-namafile="${message}" data-kodefile="${kodeFile}"><i class="fa fa-download"></i></button>
            </div>
            <div class="time">${time}</div>
        </div>`;
    }

    function templateCardUser(data, userId){
        return `<div class="card-people" data-targetname="${data.UserName}" data-targetid="${data.UserID}" data-userlogin="${userId}">
                    <div class="info">
                        <p class="nama">${data.UserName}</p>
                    </div>
                </div>`
    }

    function templateCardPeople(data){
        return `<div class="card-people" data-targetname="${data.UserName}" data-targetid="${data.UserId}">
            <img src="${imageProfile}" alt=""/>
            <div class="info">
                <p class="nama">${data.UserName}</p>
            </div>
            <div class="time">
                <div class="active"></div>    
            </div>
        </div>`
    }

    function templateListPeople(data){
        return `<div class="card-people-message" data-targetname="${data.usernameReceiver}" data-targetid="${data.idReceiver}">
            <div class="foto">
            ${data.is_login == 1 ? `<div class="active"></div>` : ``}
                <div class="bingkai">
                <img src="${imageProfile}" alt="" />
                </div>
            </div>
            <div class="info">
                <p class="nama">${data.usernameReceiver}</p>
                <p class="last-chat">${data.message.length > 30 ? data.message.substring(0,30)+'...' : data.message}</p>
            </div>
        </div>`;
    }


    function generateUniqueCode() {
        const timestamp = Date.now().toString(36);
        const randomChars = Math.random().toString(36).substr(2, 5);
        return timestamp + randomChars;
    }

    function scrollToBottom() {
        containerValueMessage.animate({ scrollTop: $(document).height() }, 1000);
    }

    function parsingTime(data){
        var isoDateTime = data.split('T');
        var time = isoDateTime[1]
        var timePart = time.split(':')
        var timeAsli = timePart[0] + ':'+ timePart[1]
        // var dateObj = new Date(isoDateTime);
        // var year = dateObj.getFullYear();
        // var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
        // var day = ('0' + dateObj.getDate()).slice(-2);
        // var hour = ('0' + dateObj.getHours()).slice(-2);
        // var minute = ('0' + dateObj.getMinutes()).slice(-2);
        // var second = ('0' + dateObj.getSeconds()).slice(-2);
        // var milliseconds = ('00' + dateObj.getMilliseconds()).slice(-3);
        // var formattedTime = hour + ':' + minute;
        return timeAsli;
    }

    
})