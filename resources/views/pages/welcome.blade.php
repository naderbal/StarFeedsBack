@extends('main')

@section('content')

    <div class="row">
        <div class="col-md-4">
            <div class="jumbotron">
                <h3>Sign In</h3>
                <hr>
                {!! Form::open(array('url' => '/login')) !!}

                <div class="form-group">
                    {{Form::label('email', 'Email:' )}}
                    {{Form::email('email', null,array('class' => 'form-control','placeholder' => 'Email Address','required' => 'required'))}}
                </div>
                <div class="pass form-group">
                    {{Form::label('password', 'Password:' )}}
                    {{Form::password('password',null,array('class' => 'form-control','placeholder' => 'password','required' => 'required'))}}
                    @if(!$false)

                        <small class="form-text text-muted">*Wrong Email or Password</small>

                    @endif
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
                {!! Form::open(array('url' => '/register','id'=>'form')) !!}

                <div class="form-group">
                    {{Form::label('fullname', 'Full Name:' )}}
                    {{Form::text('name', null,array('class' => 'form-control','placeholder' => 'Full Name','required' => 'required'))}}
                </div>

                <div class="form-group">
                    {{Form::label('email', 'Email:' )}}
                    {{Form::email('email', null,array('class' => 'form-control','placeholder' => 'Email Address','required' => 'required'))}}
                </div>

                <div class="pass form-group">
                    {{Form::label('password', 'Password:' )}}
                    {{Form::password('password',null,array('class' => 'form-control','id'=>'pass1','name' => 'password','placeholder' => 'password','required' => 'required'))}}
                </div>

                <div class="pass form-group">
                    {{Form::label('confirmPassword', 'Confirm Password:' )}}
                    {{Form::password('confirmPassword',null,array('class' => 'form-control','id'=>'pass1','name' => 'confirmPassword','placeholder' => 'password','required' => 'required'))}}
                </div>

                <div class="gender form-group">
                    {{Form::label('gender', 'Gender:' )}}
                    {{Form::radio('gender',null,array('class' => 'form-control','value' => 'male'))}}Male
                    {{Form::radio('gender',null,array('class' => 'form-control','value' => 'female'))}}Female
                </div>

                <div class="form-group">
                    {{Form::label('age', 'Age:')}}
                    {{Form::number('age',null,array('class' => 'form-control','placeholder'=>'Age','required' => 'required','style'=>'width:70px'))}}
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

        $('.pass input').addClass("form-control");
        $('.pass input').attr("required","required");

            $('#form').bootstrapValidator({
                password: {
                    validators: {
                        stringLength: {
                            min: 8,
                        },
                        notEmpty: {
                            message: 'Please enter your Password'
                        }
                    }
                },
                confirmPassword: {
                    validators: {
                        stringLength: {
                            min: 8,
                        },
                        notEmpty: {
                            message: 'Please confirm your Password'
                        }
                    }
                }
            });
        });

</script>
