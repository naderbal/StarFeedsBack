@extends('main')
@section('title', '| Admin | Add Celebrity')

@section('content')
    <div class="container">
        <h1>Add Celebrity</h1>
        <hr>
        <div class="row">
            <!-- left column -->


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
                        <i class="fa fa-ok"></i>
                        <strong>{{ Session::get('success') }}</strong>
                    </div>
                @endif

                <h3>Personal info</h3>

                {!! Form::open(array('url' => '/add-celeb','class'=>'form-horizontal','data-parsley-validate'=>"")) !!}

                <div class="form-group">
                    {{Form::label('celebname', 'Name:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('name', null,array('class' => 'form-control','placeholder' => "Name"))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('twitterlink', 'Twitter Link:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('twt_id', null,array('class' => 'form-control','placeholder' => 'Twitter link'))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('facebooklink', 'Facebook Link: ',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('fb_id', null,array('class' => 'form-control','placeholder' => 'Facebook link'))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('instagramlink', 'Instagram Link:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('instagram_id', null,array('class' => 'form-control','placeholder' => 'Instagram link'))}}
                    </div>
                </div>

                <div class="form-group">
                    {{Form::label('category', 'Category:',array('class' => 'col-lg-3'))}}
                    <div class="col-lg-8">
                        {{Form::text('category', null,array('class' => 'form-control','placeholder' => 'Category'))}}
                    </div>
                </div>

                    <div class="form-group">
                        {{Form::label('country', 'Country:' ,array('class' => 'col-lg-3'))}}
                        <div class="col-lg-8">
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
                    </div>

                <div class="form-group">
                    {{Form::label('','',array('class' => 'col-lg-3'))}}
                    <div class="col-md-8">
                        {{Form::submit('Add Celebrity',array('class' => 'btn btn-primary'))}}
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection