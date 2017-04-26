@extends('main')

@section('stylesheet')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.css" crossorigin="anonymous">

@endsection

@section('homeLink', "/")
@section('visible','hidden')


@section('content')

    <div class="row">
        <div class="col-md-4">
            <div class="jumbotron">
                <h3>Sign In</h3>
                <hr>
                {!! Form::open(array('url' => 'foo/bar')) !!}

                    <div id="Form" class="form-group">
                        {{Form::label('email', 'Email:' )}}
                        {{Form::text('email', null,array('class' => 'form-control','placeholder' => 'Email Address or Username','required' => 'required'))}}
                    </div>
                    <div class="form-group">
                        {{Form::label('password', 'Password:' )}}
                        {{Form::password('password',null,array('class' => 'form-control','placeholder' => 'password','required' => 'required'))}}
                    </div>
                    <div class="form-group">
                        {{Form::submit('LogIn',array('class' => 'btn btn-success center-block'))}}
                    </div>

                {!! Form::close() !!}
                <hr>
                <a class="btn btn-block btn-social btn-facebook">
                    <span class="fa fa-facebook"></span> Sign in with Facebook
                </a>
                <hr>
                <a class="btn btn-block btn-social btn-google">
                    <span class="fa fa-google"></span> Sign in with Google
                </a>
            </div>
        </div>
        <div class="col-md-4 col-md-offset-2">
            <div class="jumbotron">
                <h3>Register</h3>
                <hr>
                {!! Form::open(array('url' => 'foo/bar','id' => 'form')) !!}

                    <div class="form-group">
                        {{Form::label('fullname', 'Full Name:' )}}
                        {{Form::text('fullname', null,array('class' => 'form-control','placeholder' => 'Full Name','required' => 'required'))}}
                    </div>

                    <div class="form-group">
                        {{Form::label('username', 'User Name:' )}}
                        {{Form::text('username', null,array('class' => 'form-control','placeholder' => 'User Name','required' => 'required'))}}
                    </div>

                    <div class="form-group">
                        {{Form::label('email', 'Email:' )}}
                        {{Form::text('email', null,array('class' => 'form-control','placeholder' => 'Email Address','required' => 'required'))}}
                    </div>

                    <div class="form-group">
                        {{Form::label('password', 'Password:' )}}
                        {{Form::password('password',null,array('class' => 'form-control','name' => 'password','placeholder' => 'password','required' => 'required'))}}
                    </div>

                    <div class="form-group">
                        {{Form::label('confirmPassword', 'Confirm Password:' )}}
                        {{Form::password('confirmPassword',null,array('class' => 'form-control','name' => 'confirmPassword','placeholder' => 'password','required' => 'required'))}}
                    </div>

                    <div class="form-group">
                        {{Form::label('gender', 'Gender:' )}}
                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'male'))}}Male
                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'female'))}}Female
                    </div>

                    <div class="form-group">
                        {{Form::label('dateOfBirth', 'Date of Birth:')}}
                        {{Form::date('dateOfBirth',null,array('class' => 'form-control','required' => 'required'))}}
                    </div>

                    <div class="form-group">
                        {{Form::submit('Register',array('class' => 'btn btn-success center-block'))}}
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>

@endsection


<script>
    $(document).ready(function() {
        $('#form').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                confirmPassword: {
                    validators: {
                        identical: {
                            field: 'password',
                            message: 'The password and its confirm are not the same'
                        }
                    }
                }
            }
        });
    });
</script>
