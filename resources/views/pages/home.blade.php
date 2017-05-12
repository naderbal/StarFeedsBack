@extends('main')

<style>

    .fa-facebook{
        color:#3b5998;
    }
    .fa-twitter{
        color:#0084b4;
    }
    .fa-instagram{
        color:#f56040;
    }

    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {
        opacity: 0.7;
    }

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        max-width: 100%; /* Full width */
        max-height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(255,255,255); /* Fallback color */
        background-color: rgba(238,238,238,0.9); /* Black w/ opacity */
    }

    /* Modal Content (Image) */
    .modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
    }

    /* Caption of Modal Image (Image Text) - Same Width as the Image */
    #caption {
        margin: auto;
        display: block;
        width: 50%;
        max-width: 700px;
        text-align: center;
        color: #444;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation - Zoom in the Modal */
    .modal-content, #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {-webkit-transform:scale(0)}
        to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
    }

    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */


    .close{
        position: inherit;
    }

</style>

@section('title','| Homepage')
@section('homeActive','active')

@section('stylesheet')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.css" crossorigin="anonymous">
    <link rel="stylesheet" href="" crossorigin="anonymous">
    <script src="{{ URL::asset("js/bootstrap-lightbox.min.js") }}"></script>

@endsection



@section('content')


    <div class="row">
        <div class="col-md-8 col-sm-8">
            @foreach ($data as $feed )



                @if (str_contains($feed->feedType,"photo"))



                     @include('partials._imgPost',
                              ['postimg'=> $feed->imageUrl,
                               'usrname'=> $feed->celebName,
                               'usrimg'=>  $feed->imageProfile,
                               'postapp'=> $feed->platform,
                               'date'=>    $feed->date,
                               'cap'=>     $feed->text,
                               'postid' => $feed->id
                               ])


                @elseif(str_contains($feed->feedType,"video") )

                    @include('partials._vidPost',
                              ['usrname'=> $feed->celebName,
                               'usrimg'=>  $feed->imageProfile,
                               'postvid'=> $feed->link,
                               'postapp'=> $feed->platform,
                               'date'=>    $feed->date,
                               'cap'=>     $feed->text
                              ])


                @elseif(str_contains($feed->feedType,"text") )

                    @include('partials._txtPost',
                            ['post'=>    $feed->text,
                             'usrname'=> $feed->celebName,
                             'usrimg'=>  $feed->imageProfile,
                             'postapp'=> $feed->platform,
                             'date'=>    $feed->date
                            ])
                @endif

            @endforeach

        </div>

        <div class="col-md-3 col-md-offset-1 col-sm-3 sol-sm-offset-1 hidden-xs secondary" style="border: 1px solid #ddd; border-radius: 10px">
            <h3><a href="/suggestions" style="color:inherit">Suggestions</a></h3>
            <hr>
            @foreach($suggestions as $suggestion)

                @include('partials._suggestion',
                [
                'celebimage'=> $suggestion->imageProfile,
                'celebName' => $suggestion->name,
                'celebid' => $suggestion->id
                ]
                )

            @endforeach
        </div>
    </div>


    @include('partials._modal')

@endsection
