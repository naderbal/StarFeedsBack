@extends('main')

@section('celebritiesActive','active')
@section('title',' | Celebrities')

<style>
    i.fa {
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
        border-radius: 50%;
        padding: 25px;
        -webkit-transition: all 0.25s ease-in-out;
        -moz-transition: all 0.25s ease-in-out;
        -ms-transition: all 0.25s ease-in-out;
        -o-transition: all 0.25s ease-in-out;
        transition: all 0.25s ease-in-out;
        border:1px solid #aaa;
        background-color: #aaa;
        color:#fff;
        max-width:150px;

    }

    i.fa:hover{
        background-color: #fff;
        -webkit-transition: all 0.25s ease-in-out;
        -moz-transition: all 0.25s ease-in-out;
        -ms-transition: all 0.25s ease-in-out;
        -o-transition: all 0.25s ease-in-out;
        transition: all 0.25s ease-in-out;
        color:#aaa;
    }

    .btn-list{

        padding: 25px;
        -webkit-transition: all 0.25s ease-in-out;
        -moz-transition: all 0.25s ease-in-out;
        -ms-transition: all 0.25s ease-in-out;
        -o-transition: all 0.25s ease-in-out;
        transition: all 0.25s ease-in-out;
        border:1px solid #aaa;
        color:#fff;
        background-color: #aaa;
        max-width:200px;
    }

    .btn-list:hover{
        background-color: #fff;
        border:1px solid #aaa;
        -webkit-transition: all 0.25s ease-in-out;
        -moz-transition: all 0.25s ease-in-out;
        -ms-transition: all 0.25s ease-in-out;
        -o-transition: all 0.25s ease-in-out;
        transition: all 0.25s ease-in-out;
        color:#aaa;
    }
</style>

@section('content')

    @include('partials._celebNav',[
                                   'categoriesActive'=>'',
                                   'allActive' => 'active',
                                   'regionActive' => ''
                                   ])

   <div class="row">
        <br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            @foreach($celebrities as $result)

                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-lg-pull-1 col-md-pull-1 col-md-offset-1 col-sm-offset-1 col-sm-pull-2 col-xs-pull-2 col-xs-offset-2" style="margin-bottom: 5px;">
                    <div class="card" style="width: 150%; background-color: #eee; overflow: hidden;">
                        <div style="margin:5px;">
                            <img class="card-img-top img-circle center-block img-responsive" style="width:150px;  max-height:150px;" src="{{ $result['celeb']->fb_profile_url }}" alt="Card image cap">
                            <div class="card-block">
                                <h4 class="card-title text-center"><a href="/timeline/{{ $result['celeb']->name }}">{{ $result['celeb']->name }}</a></h4>
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

        </div>
    </div>


@endsection