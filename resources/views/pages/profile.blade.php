@extends('main')
@section('title','| '.Session::get('user')->name)
@section('content')
    <div class="container">
        <h1>Edit Profile</h1>
        <hr>
        <div class="row">
            <!-- left column -->
            <div class="col-md-3">
                <div class="text-center">
                    <h3><a class="" href="/following" style="color:inherit">Following </a>: {{sizeof($celebrities)}}</h3>
                </div>
            </div>

            <!-- edit form column -->
            <div class="col-md-9 personal-info">

                <h3>Personal info</h3>
                <hr>

                {!! Form::open(array('url' => 'foo/bar')) !!}

                <div class="form-group">
                    {{Form::label('fullname', 'Full Name:' )}}
                    {{Form::text('fullname', Session::get('user')->name,array('class' => 'form-control','placeholder' => 'Full Name','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('email', 'Email:' )}}
                    {{Form::text('email', Session::get('user')->email,array('class' => 'form-control','placeholder' => 'Email Address','required' => 'required' ))}}
                </div>

                <div class="pass form-group">
                    {{Form::label('password', 'Password:' )}}
                    {{Form::password('password',null,array('class' => 'form-control','placeholder' => 'password','required' => 'required' ))}}
                </div>

                <div class="pass form-group">
                    {{Form::label('confirmPassword', 'Confirm Password:' )}}
                    {{Form::password('confirmPassword',null,array('class' => 'form-control','placeholder' => 'password','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('gender', 'Gender:' )}}

                    @if(str_contains(Session::get('user')->gender, 'male'))

                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'male','checked' => 'checked'))}}Male
                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'female'))}}Female



                    @else

                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'male'))}}Male
                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'female','checked' => 'checked'))}}Female



                    @endif

                </div>

                <div class="form-group">
                    {{Form::label('age', 'Age:')}}
                    {{Form::number('age', Session::get('user')->age,array('class' => 'form-control','style' => 'width:70px'))}}
                </div>

                <div class="form-group">
                    {{Form::submit('Update',array('class' => 'btn btn-success'))}}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

<script>


    $(document).ready(function() {
        $('.pass input').addClass("form-control");
        $('.pass input').attr("required", "required");
    });

</script>