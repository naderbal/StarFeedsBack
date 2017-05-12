<div id="media" class="media" style="background-color: #eee;padding:5px; border-radius: 10px;margin-bottom: 10px;display: block;">
    <span class="close"  onclick="this.parentNode.style.display='none'">&times;</span>
    <div class="media-left">
        <img src="{{ $celebimage }}" class="media-object" style="max-width:45px">
    </div>
    <div class="media-body ">
        <h4 class="media-heading"><a href="/timeline/{{ $celebName }}" style="color:inherit">{{$celebName}}</a></h4>
        <div>
            <a href="/follow/{{$celebid}}" class="btn btn-success center-block" style="width:70%">Follow</a>
        </div>
    </div>
</div>