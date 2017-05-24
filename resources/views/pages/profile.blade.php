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
                    <h3><a class="" href="/following" style="color:inherit">Following </a>: {{sizeof(Session::get('user')->celebrity)}}</h3>
                </div>
            </div>

            <!-- edit form column -->
            <div class="col-md-9 personal-info">

                <h3>Personal info</h3>
                <hr>

                {!! Form::open(array('url' => '/update-user','data-parsley-validate'=>"")) !!}

                <div class="form-group" style="display: none;">
                    {{Form::label('id', 'Id:')}}
                    {{Form::number('id',Session::get('user')->id,array('class' => 'form-control','placeholder'=>'Id',"readonly",'required' => 'required','style'=>'width:70px;position:relative;'))}}
                </div>

                <div class="form-group">
                    {{Form::label('fullname', 'Full Name:' )}}
                    {{Form::text('name', Session::get('user')->name,array('class' => 'form-control','placeholder' => 'Full Name','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('email', 'Email:' )}}
                    {{Form::text('email', Session::get('user')->email,array('class' => 'form-control','placeholder' => 'Email Address','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('oldPassword', 'Old Password:' )}}
                    {{Form::password('password',array('class' => 'form-control',"id"=>"oldPass",'placeholder' => 'password','required' => 'required' ))}}
                    @if(Session::has('error'))
                        <small style="color:red;">*{{ Session::get('error') }}</small>
                        <script>
                            document.getElementById('oldPass').style.borderColor='red';
                        </script>
                    @endif
                </div>

                <div class="form-group">
                    {{Form::label('newPassword', 'New Password:' )}}
                    {{Form::password('new_password',array('class' => 'form-control','data-parsley-minlength'=>"8",'id'=>'pass1','placeholder' => 'password','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('confirmPassword', 'Confirm Password:' )}}
                    {{Form::password('confirmPassword',array('class' => 'form-control','data-parsley-equalto'=>"#pass1",'placeholder' => 'Password','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('gender', 'Gender:' )}}

                    @if(str_contains(Session::get('user')->gender, 'male'))

                        {{Form::radio('gender',"male",'checked',array('class' => '','checked' => 'checked',"style" => "display:initial;"))}}Male
                        {{Form::radio('gender',"female",null,array('class' => '',"style" => "display:initial;"))}}Female



                    @else

                        {{Form::radio('gender',"male",null,array('class' => '',"style" => "display:initial;"))}}Male
                        {{Form::radio('gender',"female",'checked',array('class' => '',"style" => "display:initial;"))}}Female



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
