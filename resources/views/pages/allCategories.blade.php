@extends('main')

@section('celebritiesActive','active')
@section('title',' | Celebrities | Categories')

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
</style>

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <ul class="nav nav-tabs">
                <li role="presentation" id="allTab" class="" ><a href="/celebrities/all" >All</a></li>
                <li role="presentation" id="regionTab" class="" ><a href="/celebrities/region" >Region</a></li>
                <li role="presentation" id="categoriesTab" class="active" ><a href="#" >Categories</a></li>
            </ul>
        </div>
    </div>

    <div class="row" id="region" style="display: block">
        <div class="col-md-10">

            <br>

            @foreach( $categories as $category)

                <div class="col-md-2" style="margin-bottom: 10px;">
                    <a href="/celebrities/category/{{ $category->id }}" class="btn btn-list"> <h3> {{ $category->category }} </h3> </a>
                </div>

            @endforeach

        </div>
    </div>

@endsection