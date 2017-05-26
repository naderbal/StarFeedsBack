@extends('main')

@section('title', '| Search')

@section('content')

    <h1>Search Result for {{ $search }}</h1>
    <hr>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            @foreach($result as $result)

                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-lg-pull-1 col-md-pull-1 col-md-offset-1 col-sm-offset-1 col-sm-pull-2 col-xs-pull-2 col-xs-offset-2" style="margin-bottom: 5px;">
                    <div class="card secondary">
                        <div style="margin:5px;">
                            <img class="card-img-top img-circle center-block img-responsive" style="width:80px;  max-height:150px;" src="{{ $result['celeb']->fb_profile_url }}" alt="Card image cap">
                            <div class="card-block">
                                <h4 class="card-title text-center"><a href="/timeline/{{ $result['celeb']->id }}" style="color:inherit">{{ $result['celeb']->name }}</a></h4>
                                @if(!$result['is_followed'])
                                    <a href="/follow/{{$result['celeb']->id}}" class="btn btn-success center-block" style="width:60%"><span class="spn"></span>Follow</a>
                                @else
                                    <a href="/unfollow/{{$result['celeb']->id}}" class="btn btn-info center-block" style="width:60%;"><span class="glyphicon glyphicon-ok"></span> Followed</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
            @if($result==null)

                <p>No result found</p>

            @endif

        </div>
    </div>

@endsection