@extends('main')

@section('celebritiesActive','active')
@section('title',' | Celebrities')

<style>
    .btn-list{

        padding: 25px;
        -webkit-transition: all 0.25s ease-in-out;
        -moz-transition: all 0.25s ease-in-out;
        -ms-transition: all 0.25s ease-in-out;
        -o-transition: all 0.25s ease-in-out;
        transition: all 0.25s ease-in-out;
        border:1px solid #777;
        color:#e7e7e7;
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
        color:#c7c7c7!important;
    }

</style>

@section('content')

    @include('partials._celebNav',[
                                   'categoriesActive'=>'active',
                                   'allActive' => '',
                                   'regionActive' => ''
                                   ])

    <div class="row" id="region" style="display: block">
        <div class="col-md-10">

            <br>

            @foreach( $categories as $category)

                <div class="col-lg-2 col-md-3 col-xs-5" style="margin-bottom: 10px;">
                    <a href="/celebrities/category/{{ $category->id }}" class="btn btn-list center-block" > <h3> {{ $category->category }} </h3> </a>
                </div>

            @endforeach

        </div>
    </div>

@endsection