<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>pusher chat</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <div class="chat">
        <div class="top">
            <div>
                <img src="https://i.pravatar.cc/150?img=3" alt="Avatar">
                <p>Ra Mi</p>
                <small>Online</small>
            </div>
        </div>
        <div class="messages">
            @include('receive', ['message' => "Hey! What's up! NBSP🖐"])
        </div>
        <div class="bottom">
            <form action="">
                <input type="text" id="message" name="message" placeholder="Enter Message..." autocomplete="off">
                <button type="submit"></button>
            </form>
        </div>
    </div>

    {{-- <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;
    
        var pusher = new Pusher('3b379f132b4990c8e198', {
          cluster: 'ap1'
        });
    
        var channel = pusher.subscribe('chat');
        channel.bind('public', function(data) {
          alert(JSON.stringify(data));
        });
      </script> --}}

      <script>
        const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster:'ap1'})
        const channel = pusher.subscribe('public');
        
        channel.bind('chat', function(data){
            $.post("/receive", {
                _token: '{{csrf_token()}}',
                message: data.message,
            }).done(function(res){
                $(".messages > .message").last().after(res);
            });
        });

        // broadcast message
        $("form").submit(function(event){
            event.preventDefault();

            $.ajax({
                url:"/broadcast",
                method: "POST",
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token: '{{csrf_token()}}',
                    message: $("form #message").val(),
                }
            }).done(function(res){
                $(".messages > .message").last().after(res);
                $("form #message").val('');
                $(document).scrollTop($(document).height());
            });
        });
      </script>
</body>
</html>