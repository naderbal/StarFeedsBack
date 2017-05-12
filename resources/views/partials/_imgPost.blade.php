<div class="media secondary" style="max-height:600px;border-radius:5px;border:1px solid #ccc;padding:5px; ">
    <div class="media-left">
        <img src="{{ URL::asset("$usrimg") }}" class="media-object" style="max-width:45px">
    </div>
    <div class="media-body ">
        <a href="/timeline/{{$usrname}}" style="color:inherit"><h4 class="media-heading">{{ $usrname }}</h4></a>
        <p style="margin-bottom: 5px;"><span class="fa fa-{{ $postapp }} media-object" style="padding-right: 10px; padding-top: 5px; float: left" >  </span> {{ $date }} </p>

        <div >
            <div class="thumbnail center-block" style="max-width:400px;">
                    <img id="myImg" onclick='img( this , "{{ $cap }}" );' src="{{ $postimg }}" style="width:100%">
                <div class="caption">
                    <p id="img-cap">{{ $cap }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

