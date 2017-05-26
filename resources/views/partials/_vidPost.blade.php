<div class="media secondary" style="border-radius:5px;border:1px solid #ccc;padding:5px; ">
    <div class="media-left">
        <img src="{{ URL::asset("$usrimg") }}" class="media-object" style="max-width:45px">
    </div>
    <div class="media-body ">
        <h4 class="media-heading"><a class="celebLink" href="/timeline/{{$usrname}}">{{ $usrname }}</a></h4>
        <p style="margin-bottom: 5px"><span class="fa fa-{{ $postapp }} media-object" style="padding-right: 10px; padding-top: 5px; float: left" ></span>{{ $date }}</p>


            <div class="thumbnail center-block" style="max-width:500px;">
                {{--<div class="embed-responsive embed-responsive-16by9">--}}
                    {{--<iframe class="" src="{{ $postvid }}" frameborder="0" allowfullscreen style="margin:0 auto;"></iframe>--}}
                {{--</div>--}}
                <div id="styled_video_container">
                    <video src="{{ $postvid }}" style="width:100%" controls muted preload="metadata">
                </div>
                <div class="caption">
                    <p>{{ $cap }}</p>
                </div>
            </div>

    </div>
</div>