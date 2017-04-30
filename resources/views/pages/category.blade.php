@extends('main')

@section('celebritiesActive','active')
@section('title',' | Caelebrities')

@section('content')
    @include('partials._celebNav',[
                                   'categoriesActive'=>'active',
                                   'allActive' => '',
                                   'regionActive' => ''
                                   ])

    <div class="row">
        <div class="col-md-10">

            <h4><a href="/{{$user->id}}/celebrities/categories">Categories </a> > {{ $category->category }}</h4>
            <hr>

            @foreach($celebrities as $result)

                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-lg-pull-1 col-md-pull-1 col-md-offset-1 col-sm-offset-1 col-sm-pull-2 col-xs-pull-2 col-xs-offset-2" style="margin-bottom: 5px;">
                    <div class="card" style="width: 150%; background-color: #eee; overflow: hidden;">
                        <div style="margin:5px;">
                            <img class="card-img-top img-circle center-block img-responsive" style="width:150px;  max-height:150px;" src="{{ $result->imageProfile }}" alt="Card image cap">
                            <div class="card-block">
                                <h4 class="card-title text-center"><a href="/{{$user->id}}/timeline/{{ $result->id }}"> {{ $result->name }} </a></h4>
                                @if($result->isFollowed = 'false')
                                    <a href="/{{$user->id}}/follow/{{$result->id}}" class="btn btn-success center-block" style="width:60%"><span class="spn"></span>Follow</a>
                                @else
                                    <a href="/{{$user->id}}/unfollow/{{$result->id}}" class="btn btn-info center-block" style="width:60%;"><span class="glyphicon glyphicon-ok"></span> Followed</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>



    <script>
        function Region() {
            if(document.getElementById("regionTab").className.toString()!="active"){

                document.getElementById("regionTab").className="active";
                document.getElementById("allTab").className="none";
                document.getElementById("categoriesTab").className="none";
                document.getElementById("all").style.display="none";
                document.getElementById("region").style.display="block";
                document.getElementById("categories").style.display="none";

            }
        }

        function All() {
            if(document.getElementById("allTab").className.toString()!="active"){

                document.getElementById("allTab").className="active";
                document.getElementById("categoriesTab").className="none";
                document.getElementById("regionTab").className="none";
                document.getElementById("all").style.display="block";
                document.getElementById("region").style.display="none";
                document.getElementById("categories").style.display="none";


            }
        }


        function Categories() {

            if(document.getElementById("categoriesTab").className.toString()!="active"){

                document.getElementById("categoriesTab").className = "active";
                document.getElementById("allTab").className="none";
                document.getElementById("regionTab").className="none";
                document.getElementById("all").style.display="none";
                document.getElementById("region").style.display="none";
                document.getElementById("categories").style.display="block";


            }
        }
    </script>

@endsection