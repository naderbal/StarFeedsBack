@extends('main')
@section('title','| '.$user->name)
@section('content')
    <div class="container">
        <h1>Edit Profile</h1>
        <hr>
        <div class="row">
            <!-- left column -->
            <div class="col-md-3">
                <div class="text-center">
                    <img src="{{ $user->imageProfile }}" class="avatar img-circle" alt="avatar">
                    <h6>Upload a different photo...</h6>

                    <input type="file" class="form-control">
                    <h3><a class="" href="/following/{{$user->id}}">Following </a>: {{sizeof($celebrities)}}</h3>
                </div>
            </div>

            <!-- edit form column -->
            <div class="col-md-9 personal-info">
                <div class="alert alert-info alert-dismissable">
                    <a class="panel-close close" data-dismiss="alert">×</a>
                    <i class="fa fa-coffee"></i>
                    This is an <strong>.alert</strong>. Use this to show important messages to the user.
                </div>
                <h3>Personal info</h3>
                <hr>

                {!! Form::open(array('url' => 'foo/bar')) !!}

                <div class="form-group">
                    {{Form::label('fullname', 'Full Name:' )}}
                    {{Form::text('fullname', $user->name,array('class' => 'form-control','placeholder' => 'Full Name','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('username', 'User Name:' )}}
                    {{Form::text('username', $user->username,array('class' => 'form-control','placeholder' => 'User Name','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('email', 'Email:' )}}
                    {{Form::text('email', $user->email,array('class' => 'form-control','placeholder' => 'Email Address','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('password', 'Password:' )}}
                    {{Form::text('password',$user->password,array('class' => 'form-control','placeholder' => 'password','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('confirmPassword', 'Confirm Password:' )}}
                    {{Form::text('confirmPassword',$user->password,array('class' => 'form-control','placeholder' => 'password','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('gender', 'Gender:' )}}

                    @if(str_contains($user->gender, 'male'))

                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'male','checked' => 'checked'))}}Male
                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'female'))}}Female



                    @else

                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'male'))}}Male
                        {{Form::radio('gender',null,array('class' => 'form-control','value' => 'female','checked' => 'checked'))}}Female



                    @endif

                </div>

                <div class="form-group">
                    {{Form::label('age', 'Age:')}}
                    {{Form::text('age', $user->age,array('class' => 'form-control','style' => 'width:10%', 'value' => $user->age))}}
                </div>

                <div class="form-group">
                    {{Form::submit('Update',array('class' => 'btn btn-success'))}}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <hr>
@endsection