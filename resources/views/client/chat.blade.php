@extends('client.layout.app')
@section('content')
    <div id="container" class="col-12 ">

        <main class="">
            <header class="d-flex justify-content-end align-items-center gap-5 text-end">
                <p class="align-self-end mr-auto"><a href="{{ route('UserDash') }}"
                        class="card-link active text-warning mt-3 mb-2"> العودة
                        للرئيسية <i class="fa fa-long-arrow-left p-2 pt-1"> </i></a></p>
                <div class="col-lg-3 col-6">
                    <h2>  </h2>
                    <h3 class="d-lg-block d-none chat-count"><p class="fs-4">{{Auth::user()->name}}</p> <small class="fs-6 text-white">يوجد <span data-count="{{ $chatCount }}" class="bg-danger rounded-circle mx-2 px-1 notif-chat"> {{$chatCount}} </span> رسالة</small></h3>
                    <p class="align-self-end mr-auto d-block d-lg-none " style="font-size:12px"><a
                            href="{{ route('UserDash') }}" class="card-link active text-warning mt-3 mb-2"> العودة
                            للرئيسية <i class="fa fa-long-arrow-left p-2 pt-1"> </i></a></p>
                </div>
                <img src="{{ URL::to('images/'.Auth::user()->profile->avatar)}}" width="80" class=" rounded-circle "
                    alt="{{ Auth::user()->profile->avatar }}">

            </header>
            <ul id="chat" class="chat-notify d-flex flex-column-reverse" style="height:70vh">

                @foreach($chats as $chat)
                    @if ($chat->post_id == $auction->post_id)
                        @if ($chat->username == auth()->user()->name)
                            <li class="me">
                                <div class="entete">
                                    <h3>10:12AM, Today</h3>
                                    <h2>{{$chat->username}}</h2>
                                    <span class="status blue"></span>
                                </div>
                                <div class="triangle"></div>
                                <div class="messag">
                                    {{$chat->message}}
                                </div>
                            </li>
                        @else
                            <li class="you">
                                <div class="entete">
                                    <span class="status green"></span>
                                    <h2>{{$chat->username}}</h2>
                                    <h3>10:12AM, Today</h3>
                                </div>
                                <div class="triangle"></div>
                                <div class="messag">
                                    {{$chat->message}}
                                </div>
                            </li>
                        @endif
                    @endif
                @endforeach


            </ul>

        </main>
        <form id="myForm" name="myForm" class="form-horizontal" novalidate="">
            <input type="hidden" id="post_id" name="post_id" value="{{$auction->post_id}}">
            <input type="hidden" id="aw_user_id" name="aw_user_id" value="{{$auction->aw_user_id}}">
            <input type="hidden" id="user_id" name="user_id" value="{{Auth::id()}}">
            <input type="hidden" id="owner_user_id" name="owner_user_id" value="{{$auction->owner_user_id}}">
            <input type="hidden" id="username" name="username" value="{{ auth()->user()->name}}">
            <div class="modal-body d-flex justify-content-around col-12 mt-5">
                <input type="text" class="form-control col-10 text-end" id="message" name="message"
                    placeholder="ادخل نص الرسالة " value="">
                <div class="col-2">
                    <button type="button" class="btn btn-primary" id="btn-save" value="add" style="background-color:#E39100;color:#FFFFFF;
                    border:#E39100"> ارسال
                    </button>
                    <input type="hidden" id="todo_id" name="todo_id" value="0">
                </div>
        </form>

    </div>
@stop
@push('javascript')
    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher('9ecc8e897a93aeee0ca1', {
            encrypted: true
        });

        var notificationsWrapper = $('.chat-notify');
        var chatCount = $('.chat-count');
        var notificationsCountElem = chatCount.find('span[data-count]');
        var notificationsCount = parseInt(notificationsCountElem.data('count'));
        

        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;


        var channel = pusher.subscribe('chat-notifiction');
        channel.bind('ChatNotification', function(data) {
            var existingNotifications = notificationsWrapper.html();
            var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
            // let i = 1;
            var postid = `{{$auction->post_id}}`;
            var newNotificationHtml;
            if( typeof(data.post_id) != 'undefined' && data.post_id == postid){
            if (data.user_id == `{{ auth()->id() }}`) {
                newNotificationHtml = `
                <li class="me">
                    <div class="entete">
                        <h3>10:12AM, Today</h3>
                        <h2>` + data.username + `</h2>
                        <span class="status blue"></span>
                    </div>
                    <div class="triangle"></div>
                    <div class="messag">
                        ` + data.message + `
                    </div>
                </li>`;
            } else {
                newNotificationHtml = `
                <li class="you">
                    <div class="entete">
                        <span class="status green"></span>
                        <h2>` + data.username + `</h2>
                        <h3>10:12AM, Today</h3>
                    </div>
                    <div class="triangle"></div>
                    <div class="messag">
                        ` + data.message + `
                    </div>
                </li>`;
            }
            notificationsCount += 1;
            notificationsCountElem.attr('data-count', notificationsCount);
            chatCount.find('.notif-chat').text(notificationsCount);
            }else{
                newNotificationHtml = '';
            }
            notificationsWrapper.html(newNotificationHtml + existingNotifications);

            

        });
    </script>
@endpush
</body>

</html>


<style>
    form input[type="text"] {
        border: none;
        background-color: #ffffff;
        padding: 0.6rem;
    }

    form input[type="submit"] {
        background-color: var(--yellow);
        color: white;
        text-align: center;
        border: none;
        padding: 0.6;
    }


    * {
        box-sizing: border-box;
        font-family: Tajawal;
    }

    body {
        background-color: #191919 !important;
        font-family: Arial;

        background-image: linear-gradient(rgba(0, 0, 0, .8), rgba(0, 0, 0, .8)), url('../assets/images/back.jpg');
        background-repeat: no-repeat;
        background-size: cover;

    }

    :root {
        --yellow: #E39100;
        --box1: #db9834;
        --box2: #717070;

    }

    #container {
        width: 750px;
        height: 95vh;
        background: #1C1C1C;
        margin: 0 auto;
        color: #FFFFFF;
        font-size: 0;

        background-image: linear-gradient(rgba(0, 0, 0, .8), rgba(0, 0, 0, .7)), url('../assets/images/d.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        border-radius: 5px;
        overflow: hidden;
    }

    main {

        height: 78vh;
        display: inline-block;
        font-size: 15px;
        vertical-align: top;
    }


    h2,
    h3 {
        margin: 0;
    }

    .status {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 7px;
    }

    .green {
        background-color: var(--box1)
    }

    .orange {
        background-color: #ff725d;
    }

    .blue {
        background-color: var(--box2);
        margin-right: 0;
        margin-left: 7px;
    }

    main header {
        height: 110px;


    }

    main header img:first-child {
        border-radius: 50%;
    }

    main header h2 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    main header h3 {
        font-size: 14px;
        font-weight: normal;
        color: #7e818a;
    }

    #chat {
        padding-left: 0;
        margin: 0;
        list-style-type: none;
        overflow-y: scroll;
        height: 535px;
        border-top: 2px solid #fff;

    }

    #chat li {
        padding: 10px 30px;
    }

    #chat h2,
    #chat h3 {
        display: inline-block;
        font-size: 13px;
        font-weight: normal;
    }

    #chat h3 {
        color: #bbb;
    }

    #chat .entete {
        margin-bottom: 5px;
    }

    #chat .messag {
        padding: 20px;
        color: #fff;
        line-height: 25px;
        max-width: 90%;
        display: inline-block;
        text-align: left;
        border-radius: 5px;
    }

    #chat .me {
        text-align: right;
    }

    #chat .you .messag {
        background-color: var(--box1)
    }

    #chat .me .messag {
        background-color: var(--box2)
    }

    #chat .triangle {
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 10px 10px 10px;
    }

    #chat .you .triangle {
        border-color: transparent transparent var(--box1) transparent;
        margin-left: 17px;
    }

    #chat .me .triangle {

        border-color: transparent transparent var(--box2) transparent;
        margin-left: auto;
        margin-right: 17px;
    }

    main footer {
        height: 155px;
        padding: 20px 30px 10px 20px;
    }

    main footer textarea {
        resize: none;
        border: none;
        display: block;
        width: 100%;
        height: 80px;
        border-radius: 3px;
        padding: 20px;
        font-size: 13px;
        margin-bottom: 13px;
    }

    main footer textarea::placeholder {
        color: #ddd;
    }

    main footer img {
        height: 30px;
        cursor: pointer;
    }

    main footer a {
        text-decoration: none;
        text-transform: uppercase;
        font-weight: bold;
        color: var(--box2) vertical-align:top;
        margin-left: 333px;
        margin-top: 5px;
        display: inline-block;
    }

</style>
