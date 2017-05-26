<style>
    .navbar-login
    {
        width: 305px;
        padding: 10px;
        padding-bottom: 0px;
    }

    .navbar-login-session
    {
        padding: 10px;
        padding-bottom: 0px;
        padding-top: 0px;
    }

    .icon-size
    {
        font-size: 87px;
    }

    .search-form .form-group {
        float: left !important;
        transition: all 0.35s, border-radius 0s;
        width: 32px;
        height: 32px;
        background-color: #fff;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        border-radius: 25px;
        border: 1px solid #ccc;
    }
    .search-form .form-group input.form-control {
        padding-right: 20px;
        border: 0 none;
        background: transparent;
        box-shadow: none;
        display:block;
    }
    .search-form .form-group input.form-control::-webkit-input-placeholder {
        display: none;
    }
    .search-form .form-group input.form-control:-moz-placeholder {
        /* Firefox 18- */
        display: none;
    }
    .search-form .form-group input.form-control::-moz-placeholder {
        /* Firefox 19+ */
        display: none;
    }
    .search-form .form-group input.form-control:-ms-input-placeholder {
        display: none;
    }
    .search-form .form-group:hover,
    .search-form .form-group:hover {
        width: 100%;
        max-width:200px;
        border-radius: 4px 25px 25px 4px;
    }
    .search-form .form-group span.form-control-feedback {
        position: absolute;
        top: -1px;
        right: -2px;
        z-index: 2;
        display: block;
        width: 34px;
        height: 34px;
        line-height: 34px;
        text-align: center;
        color: #3596e0;
        left: initial;
        font-size: 14px;
    }



</style>


<nav class="navbar navbar-default secondary " style="height:50px; border-bottom:1px solid #ccc">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header" >
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="#"><img class="img-responsive" style="max-height: 51px;float:left;" src="{{ URL::asset("images/logo.png") }}"></a>

        </div>

    @if(Session::has('user'))
        <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="visibility: @yield('visible');">
                <ul class="nav navbar-nav">
                    <li class="@yield('homeActive')" ><a href="/home"><strong>Home</strong></a></li>
                    <li class="@yield('exploreActive')"><a href="/explore"><strong>Explore</strong></a></li>


                    <li class="@yield('celebritiesActive') dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><strong>Celebrities</strong> <span class="caret"></span></a>
                        <ul class="dropdown-menu" style="background-color: #eee; border: none;">
                            <li><a href="/celebrities/all" ><strong>All</strong></a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/celebrities/categories"><strong>Categories</strong></a></li>
                            {{--<li role="separator" class="divider"></li>--}}
                            {{--<li><a href="/celebrities/regions"><strong>Regions</strong></a></li>--}}
                        </ul>
                    </li>
                    {{--<li class="@yield('aboutActive')"><a href="/{{ $user->id }}/about"><strong>About</strong></a></li>--}}
                    <li class="@yield('contactActive')"><a href="/contact"><strong>Contact</strong></a></li>
                    @if(Session::get('user')->is_admin)

                        @include('partials._adminNav')

                    @endif
                    <li>

                        {!! Form::open(array('url' => "/search",'class' => 'search-form narvbar-form navbar-left','style' => 'margin-bottom:-8px;margin-top: 7px;margin-left: 7px;')) !!}

                        <div class="form-group has-feedback" style="height:inherit; background-color: transparent;">

                            {{Form::label('search', 'Search:',array('class' => 'sr-only') )}}
                            {{Form::text('search', null,array('class' => 'form-control','placeholder' => 'Search' ))}}
                            <span class="glyphicon glyphicon-search form-control-feedback "></span>

                        </div>

                        {!! Form::close() !!}
                    </li>

                </ul>



                <ul class="nav navbar-nav navbar-right" >
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-user">
                            </span> 
                            <strong>Account</strong>
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                        <ul class="dropdown-menu primary" style=" border: none;">
                            <li>
                                <div class="navbar-login">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <p class="text-left"><strong>{{ Session::get('user')->name }}</strong></p>
                                            <p class="text-left small">{{ Session::get('user')->email }}</p>
                                            <p class="text-left">
                                                <a href="/edit-account" class="btn btn-primary btn-block btn-sm">Edit Account</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="navbar-login navbar-login-session">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p>
                                                <a href="/logout" class="btn btn-danger btn-block">Logout</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->

        @endif
    </div><!-- /.container-fluid -->
</nav>