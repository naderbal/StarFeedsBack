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
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModalHorizontal">
                        Change Password
                    </button>
                </div>
            </div>

            <!-- edit form column -->
            <div class="col-md-9 personal-info">
                    @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissable">
                            <a class="panel-close close" data-dismiss="alert">×</a>
                            <i class="fa fa-warning"></i>
                            <strong>{{ Session::get('error') }}</strong>
                        </div>
                    @endif

                    @if(Session::has('success'))
                        <div class="alert alert-info alert-dismissable">
                            <a class="panel-close close" data-dismiss="alert">×</a>
                            <i class="fa fa-check"></i>
                            <strong>{{ Session::get('success') }}</strong>
                        </div>
                    @endif
                <h3>Personal info</h3>
                <hr>

                {!! Form::open(array('url' => '/update-user','data-parsley-validate'=>"")) !!}

                <div class="form-group">
                    {{Form::label('fullname', 'Full Name:' )}}
                    {{Form::text('name', Session::get('user')->name,array('class' => 'form-control','placeholder' => 'Full Name','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('email', 'Email:' )}}
                    {{Form::text('email', Session::get('user')->email,array('class' => 'form-control','placeholder' => 'Email Address','required' => 'required' ))}}
                </div>

                <div class="form-group">
                    {{Form::label('country', 'Country:' )}}
                    {{Form::select('country',array( " " => "--Select Country--",
                                                    "AF" => "Afghanistan",
                                                    "AL" => "Albania",
                                                    "DZ" => "Algeria",
                                                    "AS" => "American Samoa",
                                                    "AD" => "Andorra",
                                                    "AO" => "Angola",
                                                    "AI" => "Anguilla",
                                                    "AQ" => "Antarctica",
                                                    "AG" => "Antigua and Barbuda",
                                                    "AR" => "Argentina",
                                                    "AM" => "Armenia",
                                                    "AW" => "Aruba",
                                                    "AU" => "Australia",
                                                    "AT" => "Austria",
                                                    "AZ" => "Azerbaijan",
                                                    "BS" => "Bahamas",
                                                    "BH" => "Bahrain",
                                                    "BD" => "Bangladesh",
                                                    "BB" => "Barbados",
                                                    "BU" => "Barbuda",
                                                    "BY" => "Belarus",
                                                    "BE" => "Belgium",
                                                    "BZ" => "Belize",
                                                    "BJ" => "Benin",
                                                    "BM" => "Bermuda",
                                                    "BT" => "Bhutan",
                                                    "BO" => "Bolivia",
                                                    "BA" => "Bosnia and Herzegovina",
                                                    "BW" => "Botswana",
                                                    "BV" => "Bouvet Island",
                                                    "BR" => "Brazil",
                                                    "IO" => "British Indian Ocean Territory",
                                                    "BN" => "Brunei",
                                                    "BG" => "Bulgaria",
                                                    "BF" => "Burkina Faso",
                                                    "BI" => "Burundi",
                                                    "KH" => "Cambodia",
                                                    "CM" => "Cameroon",
                                                    "CA" => "Canada",
                                                    "CV" => "Cape Verde",
                                                    "KY" => "Cayman Islands",
                                                    "CF" => "Central African Republic",
                                                    "TD" => "Chad",
                                                    "CL" => "Chile",
                                                    "CN" => "China",
                                                    "CX" => "Christmas Island",
                                                    "CC" => "Cocos (Keeling) Islands",
                                                    "CO" => "Colombia",
                                                    "KM" => "Comoros",
                                                    "CG" => "Congo",
                                                    "CK" => "Cook Islands",
                                                    "CR" => "Costa Rica",
                                                    "CI" => "Cote d´Ivoire (Ivory Coast)",
                                                    "HR" => "Croatia (Hrvatska)",
                                                    "CU" => "Cuba",
                                                    "CY" => "Cyprus",
                                                    "CZ" => "Czech Republic",
                                                    "CD" => "Dem Rep of Congo (Zaire)",
                                                    "DK" => "Denmark",
                                                    "DJ" => "Djibouti",
                                                    "DM" => "Dominica",
                                                    "DO" => "Dominican Republic",
                                                    "TP" => "East Timor",
                                                    "EC" => "Ecuador",
                                                    "EG" => "Egypt",
                                                    "SV" => "El Salvador",
                                                    "EM" => "Emirates",
                                                    "GQ" => "Equatorial Guinea",
                                                    "ER" => "Eritrea",
                                                    "EE" => "Estonia",
                                                    "ET" => "Ethiopia",
                                                    "FK" => "Falkland Islands (Malvinas)",
                                                    "FO" => "Faroe Islands",
                                                    "FJ" => "Fiji",
                                                    "FI" => "Finland",
                                                    "FR" => "France",
                                                    "GF" => "French Guiana",
                                                    "PF" => "French Polynesia",
                                                    "TF" => "French Southern Territorie",
                                                    "GA" => "Gabon",
                                                    "GM" => "Gambia",
                                                    "GE" => "Georgia",
                                                    "DE" => "Germany",
                                                    "GH" => "Ghana",
                                                    "GR" => "Greece",
                                                    "GD" => "Grenada",
                                                    "GRS" => "Grenadines",
                                                    "GP" => "Guadeloupe",
                                                    "GU" => "Guam",
                                                    "GT" => "Guatemala",
                                                    "GN" => "Guinea",
                                                    "GW" => "Guinea-Bissau",
                                                    "GY" => "Guyana",
                                                    "HT" => "Haiti",
                                                    "HM" => "Heard and McDonald Islands",
                                                    "HN" => "Honduras",
                                                    "HK" => "Hong Kong (China)",
                                                    "HU" => "Hungary",
                                                    "IS" => "Iceland",
                                                    "IN" => "India",
                                                    "ID" => "Indonesia",
                                                    "IR" => "Iran",
                                                    "IQ" => "Iraq",
                                                    "IE" => "Ireland",
                                                    "IT" => "Italy",
                                                    "JM" => "Jamaica",
                                                    "JP" => "Japan",
                                                    "JO" => "Jordan",
                                                    "KZ" => "Kazakhstan",
                                                    "KE" => "Kenya",
                                                    "KI" => "Kiribati",
                                                    "KP" => "Korea North (D.P.R. Korea)",
                                                    "KR" => "Korea South",
                                                    "KW" => "Kuwait",
                                                    "KG" => "Kyrgyzstan",
                                                    "LA" => "Laos",
                                                    "LV" => "Latvia",
                                                    "LB" => "Lebanon",
                                                    "LS" => "Lesotho",
                                                    "LR" => "Liberia",
                                                    "LY" => "Libya",
                                                    "LI" => "Liechtenstein",
                                                    "LT" => "Lithuania",
                                                    "LU" => "Luxembourg",
                                                    "MO" => "Macao",
                                                    "MK" => "Macedonia",
                                                    "MG" => "Madagascar",
                                                    "MW" => "Malawi",
                                                    "MY" => "Malaysia",
                                                    "MV" => "Maldives",
                                                    "ML" => "Mali",
                                                    "MT" => "Malta",
                                                    "MH" => "Marshall Islands",
                                                    "MQ" => "Martinique",
                                                    "MR" => "Mauritania",
                                                    "MU" => "Mauritius",
                                                    "YT" => "Mayotte",
                                                    "MX" => "Mexico",
                                                    "FM" => "Micronesia",
                                                    "MD" => "Moldova",
                                                    "MC" => "Monaco",
                                                    "MN" => "Mongolia",
                                                    "MOR" => "Montenegro",
                                                    "MS" => "Montserrat",
                                                    "MA" => "Morocco",
                                                    "MZ" => "Mozambique",
                                                    "MM" => "Myanmar",
                                                    "NA" => "Namibia",
                                                    "NR" => "Nauru",
                                                    "NP" => "Nepal",
                                                    "NL" => "Netherlands",
                                                    "AN" => "Netherlands Antilles",
                                                    "NC" => "New Caledonia",
                                                    "NZ" => "New Zealand",
                                                    "NI" => "Nicaragua",
                                                    "NE" => "Niger",
                                                    "NG" => "Nigeria",
                                                    "NU" => "Niue",
                                                    "NF" => "Norfolk Island",
                                                    "MP" => "Northern Mariana Islands",
                                                    "NO" => "Norway",
                                                    "OM" => "Oman",
                                                    "PK" => "Pakistan",
                                                    "PW" => "Palau",
                                                    "PS" => "Palestine",
                                                    "PA" => "Panama",
                                                    "PG" => "Papua New Guinea",
                                                    "PY" => "Paraguay",
                                                    "PE" => "Peru",
                                                    "PH" => "Philippines",
                                                    "PN" => "Pitcairn",
                                                    "PL" => "Poland",
                                                    "PT" => "Portugal",
                                                    "PI" => "Principe",
                                                    "PR" => "Puerto Rico (US)",
                                                    "QA" => "Qatar",
                                                    "RE" => "Reunion",
                                                    "RO" => "Romania",
                                                    "RU" => "Russia",
                                                    "RU" => "Russian Federation",
                                                    "RW" => "Rwanda",
                                                    "KN" => "Saint Kitts And Nevis",
                                                    "LC" => "Saint Lucia",
                                                    "VC" => "Saint Vincent & Grenadines",
                                                    "WS" => "Samoa",
                                                    "SM" => "San Marino",
                                                    "ST" => "Sao Tome and Principe",
                                                    "SA" => "Saudi Arabia",
                                                    "SN" => "Senegal",
                                                    "SC" => "Seychelles",
                                                    "SL" => "Sierra Leone",
                                                    "SG" => "Singapore",
                                                    "SK" => "Slovakia",
                                                    "SI" => "Slovenia",
                                                    "SB" => "Solomon Islands",
                                                    "SO" => "Somalia",
                                                    "ZA" => "South Africa",
                                                    "GS" => "S. Georgia & S. Sandwich Is.",
                                                    "ES" => "Spain",
                                                    "LK" => "Sri Lanka",
                                                    "SH" => "St Helena",
                                                    "PM" => "St Pierre and Miquelon",
                                                    "SD" => "Sudan",
                                                    "SR" => "Suriname",
                                                    "SJ" => "Svalbard & Jan Mayen Is.",
                                                    "SZ" => "Swaziland",
                                                    "SE" => "Sweden",
                                                    "CH" => "Switzerland",
                                                    "SY" => "Syria",
                                                    "TW" => "Taiwan",
                                                    "TJ" => "Tajikistan",
                                                    "TZ" => "Tanzania",
                                                    "TH" => "Thailand",
                                                    "TG" => "Togo",
                                                    "TK" => "Tokelau",
                                                    "TO" => "Tonga",
                                                    "TT" => "Trinidad and Tobago",
                                                    "TN" => "Tunisia",
                                                    "TR" => "Turkey",
                                                    "TM" => "Turkmenistan",
                                                    "TC" => "Turks and Caicos Islands",
                                                    "TV" => "Tuvalu",
                                                    "UG" => "Uganda",
                                                    "UA" => "Ukraine",
                                                    "AE" => "United Arab Emirates",
                                                    "UK" => "United Kingdom",
                                                    "US" => "United States",
                                                    "UM" => "US Minor Outlying Islands",
                                                    "UY" => "Uruguay",
                                                    "UZ" => "Uzbekistan",
                                                    "VU" => "Vanuatu",
                                                    "VA" => "Vatican City State (Holy See)",
                                                    "VE" => "Venezuela",
                                                    "VN" => "Viet Nam",
                                                    "VG" => "Virgin Islands (British)",
                                                    "VI" => "Virgin Islands (US)",
                                                    "WF" => "Wallis and Futuna Islands",
                                                    "EH" => "Western Sahara",
                                                    "YE" => "Yemen",
                                                    "YU" => "Yugoslavia",
                                                    "ZM" => "Zambia",
                                                    "ZW" => "Zimbabwe"),Session::get('user')->country,array("class"=>"form-control"))}}
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
                    {{Form::number('age',Session::get('user')->age,array('class' => 'form-control','required','style' => 'width:70px'))}}
                </div>

                <div class="form-group">
                    {{Form::submit('Update',array('class' => 'btn btn-success'))}}
                </div>

                {!! Form::close() !!}


                <!-- Modal -->
                <div class="modal fade" id="myModalHorizontal" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">
                                    Change Password
                                </h4>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">

                                {!! Form::open(array('url' => '/update-user-password','data-parsley-validate'=>"")) !!}

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
                                        {{Form::submit('Save Changes',array('class' => 'btn btn-success'))}}
                                    </div>

                                {!! Form::close() !!}

                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

