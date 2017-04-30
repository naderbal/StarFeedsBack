<div class="media" style="background-color: #eee;padding:5px; ">
    <div class="media-left">
        <img src="{{ URL::asset("$usrimg") }}" class="media-object" style="max-width:45px">
    </div>
    <div class="media-body ">
        <a href="/{{$user->id}}/timeline/"><h4 class="media-heading">{{ $usrname }}</h4></a>
        <p style="margin-bottom: 5px;"><span class="fa fa-{{ $postapp }} media-object" style="padding-right: 10px; padding-top: 5px; float: left" >  </span> {{ $date }} </p>

        <div>
            <div class="thumbnail center-block" style="max-width:500px;">
                @if(!is_array($postimg))

                    <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg }}" style="width:100%">

                    @else
                        @if(sizeof($postimg) == 2)
                                <div class="row">
                                    <div class="col-md-6">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[0] }}" style="width:100%; margin-bottom:10%;">
                                    </div>
                                    <div class="col-md-6">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[1] }}" style="width:100%">
                                    </div>
                                </div>
                            @elseif(sizeof($postimg) == 3)
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[0] }}" style="width:100%; height:50%; margin-bottom: 10%;">
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[1] }}" style="width:100%; height:50%;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[2] }}" style="width:100%; height:50%;">
                                    </div>
                                </div>
                            @elseif(sizeof($postimg) == 4)
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[0] }}" style="width:100%; height:50%;margin-bottom: 10%;">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[2] }}" style="width:100%; height:50%; ">
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[1] }}" style="width:100%; height:50%; margin-bottom: 10%;">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[3] }}" style="width:100%; height:50%; ">
                                    </div>
                                </div>
                            @else

                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[0] }}" style="width:100%; height:50%; margin-bottom: 10%;">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[2] }}" style="width:100%; height:50%; margin-bottom: 10%;">
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[1] }}" style="width:100%; height:45%; margin-bottom: 10%;">
                                        <img id="myImg" onclick='img( this , "{{ $cap }}" )' src="{{ $postimg[3] }}" style="width:100%; height:50%">
                                    </div>
                                </div>
                        @endif

                @endif
                <div class="caption">
                    <p id="img-cap">{{ $cap }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

