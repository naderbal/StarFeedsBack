@extends('main')

@section('title',' | Admin | Edit Celebrity')
@section('content')
    <div class="container">
        <h1>Edit Celebrity</h1>
        <hr>
        <div class="row">
            <!-- left column -->
            <div class="col-md-3">
                <div class="text-center">
                    <img src="{{ $celeb['usrimage'] }}" class="avatar img-circle" alt="avatar">
                </div>
            </div>

            <!-- edit form column -->
            <div class="col-md-9 personal-info">
                <div class="alert alert-info alert-dismissable">
                    <a class="panel-close close" data-dismiss="alert">×</a>
                    <i class="fa fa-coffee"></i>
                    This is an <strong>.alert</strong>. Use this to show important messages to the user.
                </div>
                {!! Form::open(array('url' => 'foo/bar', 'class' => 'form-horizontal')) !!}

                <div class="form-group">
                    {{Form::label('searchCeleb', 'Search Celebrity: ', array('class'=>'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('searchCeleb', null,array('class' => 'form-control','placeholder' => "Search"))}}
                    </div>
                </div>

                {!! Form::close() !!}
                <hr>
                <h3>Celebrity info</h3>



                {!! Form::open(array('url' => 'foo/bar','class'=>'form-horizontal')) !!}

                    <div class="form-group">
                        {{Form::label('celebname', 'Name:',array('class' => 'col-lg-3'))}}
                        <div class="col-lg-8">
                            {{Form::text('celebname', 'jane',array('class' => 'form-control','placeholder' => "Name"))}}
                        </div>
                    </div>

                    <div class="form-group">
                        {{Form::label('twitterlink', 'Twitter Link:',array('class' => 'col-lg-3'))}}
                        <div class="col-lg-8">
                            {{Form::text('twitterlink', 'Twitter Link',array('class' => 'form-control','placeholder' => 'Twitter link','value' => $celeb['twitterlink'] ))}}
                        </div>
                    </div>

                    <div class="form-group">
                        {{Form::label('facebooklink', 'Facebook Link: ',array('class' => 'col-lg-3'))}}
                        <div class="col-lg-8">
                            {{Form::text('facebooklink', 'Facebook Link',array('class' => 'form-control','placeholder' => 'Facebook link','value' => $celeb['facebooklink'] ))}}
                        </div>
                    </div>

                    <div class="form-group">
                        {{Form::label('instagramlink', 'Instagram Link:',array('class' => 'col-lg-3'))}}
                        <div class="col-lg-8">
                            {{Form::text('instagramlink', 'Instagram Link',array('class' => 'form-control','placeholder' => 'Instagram link','value' => $celeb['instagramlink'] ))}}
                        </div>
                    </div>

                    <div class="form-group">
                        {{Form::label('category', 'Category:',array('class' => 'col-lg-3'))}}
                        <div class="col-lg-8">
                            {{Form::text('category', 'Category',array('class' => 'form-control','placeholder' => 'Category','value' => $celeb['category'] ))}}
                        </div>
                    </div>

                    <div class="form-group">
                        {{Form::label('','',array('class' => 'col-lg-3'))}}
                        <div class="col-md-8">
                            {{Form::submit('Save Changes',array('class' => 'btn btn-primary'))}}
                            <span></span>
                            {{Form::submit('Reset Changes',array('class' => 'btn btn-default'))}}
                        </div>
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
    <hr>
@endsection