@extends('main')

@section('title', '| Following')

@section('content')



    <div class="row">
        <ol class="breadcrumb secondary">
            <li><a href="/edit-account">Profile</a></li>
            <li class="active">Following</li>
        </ol>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            @foreach($following as $result)

                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-lg-pull-1 col-md-pull-1 col-md-offset-1 col-sm-offset-1 col-sm-pull-2 col-xs-pull-2 col-xs-offset-2" style="margin-bottom: 5px;">
                    <div class="card secondary">
                        <div style="margin:5px;">
                            <img class="card-img-top img-circle center-block img-responsive" style="width:80px;  max-height:150px;" src="{{ $result->fb_profile_url }}" alt="Card image cap">
                            <div class="card-block">
                                <h4 class="card-title text-center"><a class="celebLink" href="/timeline/{{ $result->name }}">{{ $result->name }}</a></h4>
                                <a href="/unfollow/{{$result->id}}" class="btn btn-info center-block" style="width:60%;"><span class="glyphicon glyphicon-ok"></span> Followed</a>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>

@endsection