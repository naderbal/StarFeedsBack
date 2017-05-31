<div class="media secondary" style="border-radius:5px;border:1px solid #ccc;padding:5px; ">
    <div class="media-left">
        <img src="{{ URL::asset("$usrimg") }}" class="media-object" style="max-width:45px">
    </div>
    <div class="media-body ">
        <h4 class="media-heading"><a class="celebLink" href="/timeline/{{$usrname}}">{{ $usrname }}</a></h4>
        <p style="margin-bottom: 5px"><span class="fa fa-{{ $postapp }} media-object" style="padding-right: 10px; padding-top: 3px; float: left" ></span>{{ $date }}</p>


            <div class="thumbnail center-block" style="max-width:500px; ">
                    @if($postapp == "Facebook")

                    {{--<div class="fb-video"--}}
                         {{--data-href="{{ $postvid }}"--}}
                         {{--data-width="500"--}}
                         {{--data-allowfullscreen="true"--}}
                         {{--data-autoplay="true"--}}
                         {{--data-show-captions="true"></div>--}}
                        <iframe id="video" src="https://www.facebook.com/plugins/video.php?href={{ $postvid }}" onload="resizeIframe(this);" style="width:100%;min-height: 300px;" class="fb_{{ $postid }}" controls muted preload="metadata"></iframe>

                    @else
                        <video src="{{ $postvid }}" style="width:100%" controls muted preload="metadata"></video>

                    @endif
                <div class="caption">
                    <p>{{ $cap }}</p>
                </div>
            </div>

    </div>
</div>

<script>


    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

//    $(window).on('load', function() {
//        alert('ready');
//        $("[class^='fb_']").each(function(){
////            var vid = $(this).find('#u_0_e');
//            console.log($(this).find(document));
////            var height = vid.attr('data-video-height');
////            console.log(height);
////            $(this).attr('style','height:' + height + 'px;');
//        });
//    });


</script>



