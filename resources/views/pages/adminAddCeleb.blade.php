@extends('main')
@section('title', '| Admin | Add Celebrity')

@section('content')
    <div class="container">
        <h1>Add Celebrity</h1>
        <hr>
        <div class="row">
            <!-- left column -->


            <!-- edit form column -->
            <div class="col-md-9 personal-info">
                <div class="alert alert-info alert-dismissable">
                    <a class="panel-close close" data-dismiss="alert">×</a>
                    <i class="fa fa-coffee"></i>
                    This is an <strong>.alert</strong>. Use this to show important messages to the user.
                </div>
                <h3>Personal info</h3>

                {!! Form::open(array('url' => 'foo/bar','class'=>'form-horizontal')) !!}

                <div class="form-group">
                    {{Form::label('celebname', 'Name:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('celebname', null,array('class' => 'form-control','placeholder' => "Name"))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('twitterlink', 'Twitter Link:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('twitterlink', null,array('class' => 'form-control','placeholder' => 'Twitter link'))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('facebooklink', 'Facebook Link: ',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('facebooklink', null,array('class' => 'form-control','placeholder' => 'Facebook link'))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('instagramlink', 'Instagram Link:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('instagramlink', null,array('class' => 'form-control','placeholder' => 'Instagram link'))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('category', 'Category:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('category', null,array('class' => 'form-control','placeholder' => 'Category'))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('','',array('class' => 'col-lg-3'))}}
                    <div class="col-md-8">
                        {{Form::submit('Add Celebrity',array('class' => 'btn btn-primary'))}}
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
    <hr>
@endsection