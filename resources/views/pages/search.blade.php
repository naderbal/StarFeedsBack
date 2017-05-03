@extends('main')

@section('title', '| Search')

@section('content')

    <h1>Search Result for {{ $search }}</h1>
    <hr>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            @foreach($result as $result)

                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-lg-pull-1 col-md-pull-1 col-md-offset-1 col-sm-offset-1 col-sm-pull-2 col-xs-pull-2 col-xs-offset-2" style="margin-bottom: 5px;">
                    <div class="card" style="width: 150%; background-color: #eee; overflow: hidden;">
                        <div style="margin:5px;">
                            <img class="card-img-top img-circle center-block img-responsive" style="width:150px;  max-height:150px;" src="{{ $result['celeb']->imageProfile }}" alt="Card image cap">
                            <div class="card-block">
                                <h4 class="card-title text-center"><a href="/{{$user->id}}/timeline/{{ $result['celeb']->id }}">{{ $result['celeb']->name }}</a></h4>
                                @if($result['is_followed'] = 'false')
                                    <a href="/{{$user->id}}/follow/{{$result['celeb']->id}}" class="btn btn-success center-block" style="width:60%"><span class="spn"></span>Follow</a>
                                @else
                                    <a href="/{{$user->id}}/unfollow/{{$result['celeb']->id}}" class="btn btn-info center-block" style="width:60%;"><span class="glyphicon glyphicon-ok"></span> Followed</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>

@endsection