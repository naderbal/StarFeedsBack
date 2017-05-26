@extends('main')

@section('content')

    <div class="row">
        <div class="col-md-5">
            <div class="secondary jumbotron" style="border:1px solid #ccc;">
                <h3>Sign In</h3>
                <hr>
                {!! Form::open(array('url' => '/login','data-parsley-validate'=>"")) !!}

                <div class="form-group">
                    {{Form::label('email', 'Email:' )}}
                    {{Form::email('email', null,array('class' => 'form-control','id' => 'logIn','placeholder' => 'Email Address','required' => 'required'))}}
                </div>
                <div class="pass form-group">
                    {{Form::label('password', 'Password:' )}}
                    {{Form::password('password',array('class' => 'form-control','id' => 'logIn','placeholder' => 'Password','required' => 'required'))}}
                    @if(Session::has('fail'))
                        <small class="form-text text-muted">*{{Session::get("fail")}}</small>
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
        <div class="col-md-5 col-md-offset-2">
            <div class="jumbotron secondary" style="border:1px solid #ccc;">
                <h3>Register</h3>
                <hr>
                {!! Form::open(array('url' => '/register','data-parsley-validate'=>"")) !!}

                <div class="form-group">
                    {{Form::label('fullname', 'Full Name:' )}}
                    {{Form::text('name', null,array('class' => 'form-control','placeholder' => 'Full Name','required' => 'required'))}}
                </div>

                <div class="form-group">
                    {{Form::label('email', 'Email:' )}}
                    {{Form::email('r-email', null,array('class' => 'form-control','placeholder' => 'Email Address','required' => 'required','id' => 'email'))}}
                    @if(Session::has('error'))
                        <small style="color:red;">*{{ Session::get('error') }}</small>
                        <script>
                            document.getElementById('email').style.borderColor='red';
                        </script>
                    @endif
                </div>

                <div class="pass form-group">
                    {{Form::label('password', 'Password:' )}}
                    {{Form::password('password',array('class' => 'form-control','id'=>'pass1','data-parsley-minlength'=>"8",'name' => 'password','placeholder' => 'Password','required' => 'required'))}}
                </div>

                <div class="form-group">
                    {{Form::label('confirmPassword', 'Confirm Password:' )}}
                    {{Form::password('confirmPassword',array('class' => 'form-control','data-parsley-equalto'=>"#pass1",'name' => 'confirmPassword','placeholder' => 'Confirm Password','required' => 'required'))}}
                </div>

                <div class="gender form-group">
                    {{Form::label('gender', 'Gender:' )}}
                    {{Form::radio('gender','male',"checked",array('class' => 'radio',"required" => "required","style" => "display:initial;"))}}Male
                    {{Form::radio('gender','female',null,array('class' => '',"required" => "required","style" => "display:initial;"))}}Female
                </div>

                <div class="form-group">
                    {{Form::label('age', 'Age:')}}
                    {{Form::number('age',null,array('class' => 'form-control','placeholder'=>'Age','required' => 'required','style'=>'width:70px;position:relative;'))}}
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
