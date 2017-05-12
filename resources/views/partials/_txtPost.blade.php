<div class="media secondary" style="border-radius:5px;border:1px solid #ccc;padding:5px;">
    <div class="media-left">
        <img src="{{ URL::asset("$usrimg") }}" class="media-object" style="max-width:45px">
    </div>
    <div class="media-body">
        <h4 class="media-heading"><a href="/timeline/{{$usrname}}" style="color:inherit">{{ $usrname }}</a> <small>   {{ $date }}</small></h4>
        <p style="margint-bottom:5px;"><span class="fa fa-{{ $postapp }} media-object" style="padding-right: 10px; padding-top: 5px; float: left;" ></span></p>

        <p style="font-size: medium">
            {{ $post }}
        </p>
    </div>

</div>