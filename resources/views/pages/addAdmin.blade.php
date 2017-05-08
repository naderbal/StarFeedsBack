@extends('main')

@section('title',' | Admin | Add Admin')
@section('content')
    <div class="container">
        <h1>Add Admin</h1>
        <div class="row">

            <!-- edit form column -->
            <div class="col-md-9 personal-info">

                @if($error)
                    <div class="alert alert-info alert-dismissable">
                        <a class="panel-close close" data-dismiss="alert">×</a>
                        <i class="fa fa-coffee"></i>
                        <strong>Admin Already Exist!</strong>
                    </div>
                @endif

                <hr>
                {!! Form::open(array('url' => '/add-admin','class'=>'form-horizontal','id'=>'form')) !!}

                <div class="form-group">
                    {{Form::label('name', 'Name:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('name', null,array('class' => 'form-control','placeholder' => "Name","required"=>'required'))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('email', 'Email:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::email('email', null,array('class' => 'form-control','placeholder' => 'Email',"required"=>'required'))}}
                    </div>
                </div>

                <div class="form-group pass">
                    {{Form::label('password', 'Password: ',array('class' => 'col-lg-3'))}}
                    <div class="pass col-lg-8">
                        {{Form::password('password', null,array('class' => 'form-control','placeholder' => 'password',"required"=>'required' ))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('cpassword', 'Confirm Password:',array('class' => 'col-lg-3'))}}
                    <div class="pass col-lg-8">
                        {{Form::password('cpassword', null,array('class' => 'form-control','placeholder' => 'password',"required"=>'required' ))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('gender', 'Gender:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'male'))}}Male
                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'female'))}}Female
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('age', 'Age:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::number('age',null,array('class' => 'form-control','placeholder'=>'Age','required' => 'required','style'=>'width:70px'))}}
                    </div>
                </div>


                <div class="form-group">
                    {{Form::label('','',array('class' => 'col-lg-3'))}}
                    <div class="col-md-8">
                        {{Form::submit('Add',array('class' => 'btn btn-primary'))}}
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.pass input').addClass("form-control");
            $('.pass input').attr("required", "required");
        });
    </script>
@endsection