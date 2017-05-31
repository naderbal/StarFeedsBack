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
                    {{Form::submit('LogIn',array('class' => 'btn btn-primary center-block'))}}
                </div>

                {!! Form::close() !!}
                <hr>
                <a href="/redirect" class="btn btn-block btn-social btn-facebook">
                    <span class="fa fa-facebook"></span> Sign in with Facebook
                </a>
                <hr>
                <a href="/google" class="btn btn-block btn-social btn-google">
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

                <div class="form-group">
                    {{Form::label('country', 'Country:' )}}
                    {{Form::select('country',array( "--Select Country--",
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
                                                    "ZW" => "Zimbabwe"),null,array("class"=>"form-control"))}}
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
                    {{Form::submit('Register',array('class' => 'btn btn-primary center-block'))}}
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
