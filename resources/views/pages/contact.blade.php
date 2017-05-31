@extends('main')

@section('title', '| Contact Us')
@section('contactActive', 'active')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Contact Us</h1>
            <hr>
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Thank you!</strong> Your message has been sent!
                </div>
            @endif
            {!! Form::open(array('url' => '/send-message','data-parsley-validate'=>"")) !!}

            <div class="form-group">
                {{Form::label('subject', 'Subject :')}}
                {{Form::text('subject',null,array('class' => 'form-control','placeholder'=>'Subject','required'))}}
            </div>

            <div class="form-group">
                {{Form::label('message', 'Message :' )}}
                {{Form::textarea('message', null,array('class' => 'form-control','placeholder' => 'Type your message here...','required' ))}}
            </div>

            <div class="form-group">
                {{Form::label('','')}}
                {{Form::submit('Send Message',array('class' => 'btn btn-primary'))}}
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection