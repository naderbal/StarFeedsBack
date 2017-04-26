@extends('main')
@section('title', ' | Admin | View Celebrity')
@section('content')

    <h1>Celebrities</h1>
    <hr>
    <div class="row">
        <form class="navbar-form navbar-left">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
    <div class="row">

        <div class="col-md-8">

            @foreach($celebrities as $celeb)

                <div class="col-md-4" >
                    <div class="card" style="width: 20rem; background-color: #eee; overflow: hidden;">
                        <div style="margin:5px;">
                            <img class="card-img-top img-circle center-block img-responsive" style="width:150px;  max-height:150px;" src="{{ $celeb['imageUrl'] }}" alt="Card image cap">
                            <div class="card-block">
                                <h4 class="card-title text-center">{{ $celeb['celebName'] }}</h4>
                                <a href="/7admin/edit/{{ $celeb['celebName'] }}" class="btn btn-primary center-block" style="width:50%">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>

@endsection