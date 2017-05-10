<div class="media" style="background-color: #eee;padding:5px; ">
    <div class="media-left">
        <img src="{{ URL::asset("$usrimg") }}" class="media-object" style="max-width:45px">
    </div>
    <div class="media-body ">
        <h4 class="media-heading"><a href="/timeline/{{$usrname}}" style="color:inherit">{{ $usrname }}</a></h4>
        <p style="margin-bottom: 5px"><span class="fa fa-{{ $postapp }} media-object" style="padding-right: 10px; padding-top: 5px; float: left" ></span>{{ $date }}</p>

        <div>
            <div class="thumbnail center-block" style="max-width:500px;">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" width="640" height="480" src="{{ $postvid }}" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="caption">
                    <p>{{ $cap }}</p>
                </div>
            </div>
        </div>
    </div>
</div>