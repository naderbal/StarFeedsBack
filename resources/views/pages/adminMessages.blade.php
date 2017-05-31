@extends('main')

@section('title',' | Admin | Messages')
@section('content')
    <div class="container">
        <h1>Messages</h1>
        <hr>
        <div class="row">

            @foreach($messages as $message)
                <div class="col-md-9 personal-info">

                    <div class="panel panel-primary" style="border-color:#ccc">

                         <a href="/delete-message/{{ $message->id }}"><span class="close" style="padding-right:5px">&times;</span></a>
                        <div class="panel-heading" style="border-color:#ccc">Subject : {{$message->subject}} <small> </small></div>
                          <div class="panel-body">
                            <p>{{$message->message}}</p>
                          </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

@endsection