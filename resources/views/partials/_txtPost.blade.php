<div class="media" style="background-color: #eee; padding:5px;">
    <div class="media-left">
        <img src="{{ URL::asset("$usrimg") }}" class="media-object" style="max-width:45px">
    </div>
    <div class="media-body">
        <h4 class="media-heading"><a href="/{{$user->id}}/timeline/" style="color:inherit">{{ $usrname }}</a></h4>
        <p style="margint-bottom:5px;"><span class="fa fa-{{ $postapp }} media-object" style="padding-right: 10px; padding-top: 5px; float: left;" ></span>{{ $date }}</p>

        <p>
            {{ $post }}
        </p>
    </div>

</div>