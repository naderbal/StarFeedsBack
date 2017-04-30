@extends('main')

@section('celebritiesActive','active')
@section('title',' | Celebrities ')

<style>
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

    h3::first-letter{
        text-transform: capitalize;
    }
</style>

@section('content')
    @include('partials._celebNav',[
                                   'categoriesActive'=>'',
                                   'allActive' => '',
                                   'regionActive' => 'active'
                                   ])

    <div class="row" id="region" style="display: block">
        <div class="col-md-10">

            <br>

            @foreach($regions as $region)

                <div class="col-lg-2 col-md-3 col-xs-5" style="margin-bottom: 10px;">
                    <a href="/celebrities/region/{{ $region }}" class="btn btn-list"> <h3> {{ $region }} </h3> </a>
                </div>

            @endforeach

        </div>
    </div>

@endsection