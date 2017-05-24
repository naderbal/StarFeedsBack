@extends('main')

@section('title',' | Admin | Edit Celebrity')
@section('content')
    <div class="container">
        <h1>Edit Celebrity</h1>
        <hr>
        <div class="row">
            <!-- edit form column -->
            <div class="col-md-9 personal-info">


                {!! Form::open(array('url' => '/adminGetCeleb', 'class' => 'form-horizontal')) !!}

                <div class="form-group">
                    {{Form::label('searchCeleb', 'Search Celebrity: ', array('class'=>'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('search', null,array('class' => 'form-control','placeholder' => "Search"))}}
                        @if(Session::has('error'))
                            <small style="color:red;">*{{ Session::get('error') }}</small>
                            <script>
                                document.getElementById('email').style.borderColor='red';
                            </script>
                        @endif
                    </div>

                </div>

                {!! Form::close() !!}
                <hr>

                @if(Session::has('celebrity'))
                <h3>Celebrity info</h3>



                    {!! Form::open(array('url' => '/update-celeb','class'=>'form-horizontal')) !!}

                        <div class="form-group">
                            {{Form::label('celebname', 'Name:',array('class' => 'col-lg-3'))}}
                            <div class="col-lg-8">
                                {{Form::text('celebname', Session::get('celebrity')->name,array('class' => 'form-control','placeholder' => "Name"))}}
                            </div>
                        </div>

                        <div class="form-group">
                            {{Form::label('twitterlink', 'Twitter Link:',array('class' => 'col-lg-3'))}}
                            <div class="col-lg-8">
                                {{Form::text('twitterlink', Session::get('celebrity')->twt_id,array('class' => 'form-control','placeholder' => 'Twitter link'))}}
                            </div>
                        </div>

                        <div class="form-group">
                            {{Form::label('facebooklink', 'Facebook Link: ',array('class' => 'col-lg-3'))}}
                            <div class="col-lg-8">
                                {{Form::text('facebooklink',Session::get('celebrity')->fb_id ,array('class' => 'form-control','placeholder' => 'Facebook link'))}}
                            </div>
                        </div>

                        <div class="form-group">
                            {{Form::label('instagramlink', 'Instagram Link:',array('class' => 'col-lg-3'))}}
                            <div class="col-lg-8">
                                {{Form::text('instagramlink',Session::get('celebrity')->instagram_id ,array('class' => 'form-control','placeholder' => 'Instagram link' ))}}
                            </div>
                        </div>

                        <div class="form-group">
                            {{Form::label('category', 'Category:',array('class' => 'col-lg-3'))}}
                            <div class="col-lg-8">
                                {{Form::text('category', Session::get('celebrity')->category[0]->category,array('class' => 'form-control','placeholder' => 'Category' ))}}
                            </div>
                        </div>

                        <div class="form-group">
                            {{Form::label('','',array('class' => 'col-lg-3'))}}
                            <div class="col-md-8">
                                {{Form::submit('Save Changes',array('class' => 'btn btn-primary'))}}
                            </div>
                        </div>

                    {!! Form::close() !!}
                    @endif

            </div>
        </div>
    </div>
@endsection