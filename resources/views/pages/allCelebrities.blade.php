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
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <ul class="nav nav-tabs">
                <li role="presentation" id="allTab" class="active" ><a href="#" >All</a></li>
                <li role="presentation" id="regionTab" class="" ><a href="/celebrities/region" >Region</a></li>
                <li role="presentation" id="categoriesTab" class="" ><a href="/celebrities/categories" >Categories</a></li>
            </ul>
        </div>
    </div>


   <div class="row" id="all" style="display: block">
        <br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            @foreach($celebrities as $result)

                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-lg-pull-1 col-md-pull-1 col-md-offset-1 col-sm-offset-1 col-sm-pull-2 col-xs-pull-2 col-xs-offset-2" style="margin-bottom: 5px;">
                    <div class="card" style="width: 150%; background-color: #eee; overflow: hidden;">
                        <div style="margin:5px;">
                            <img class="card-img-top img-circle center-block img-responsive" style="width:150px;  max-height:150px;" src="{{ $result->imageProfile }}" alt="Card image cap">
                            <div class="card-block">
                                <h4 class="card-title text-center"><a href="/timeline/{{ $result->id }}"> {{ $result->name }} </a></h4>
                                @if($result->followed == 'false')
                                    <a href="#" onclick="{
                                        document.getElementsByClassName('spn').innerHTML = 'Followed';
                                        this.className.replace('btn-success','btn-info');
                                        document.getElementsByClassName('spn').className+=' glyphicon';
                                        document.getElementsByClassName('spn').className+=' glyphicon-ok';
                                    }" class="btn btn-success center-block" style="width:60%"><span class="spn"></span>Follow</a>
                                @else
                                    <a href="#" onclick="{
                                        this.className.replace('btn-info','btn-success');
                                        document.getElementsByClassName('spn').innerHTML = 'Follow';
                                        document.getElementsByClassName('spn').className='spn';
                                    }" class="btn btn-info center-block" style="width:60%;"><span class="glyphicon glyphicon-ok"></span> Followed</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>


@endsection