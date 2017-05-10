<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- Javascript -->
        

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            
            .hide{
                display:none;
            }
            li {
                text-align:left;
                font-size:14px;
            }
            #map {
            height: 100%;
            }
            .bold{
                font-weight:bold;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                </div>
                <div style="display:inline-block" id="pharmacies"></div>
                <div style="" id="map"></div>

            </div>  
        </div>
        
        <div class="hide" id="lat"></div>
        <div class="hide" id="long"></div>
        
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>

<script>
(function ($) {

var lat, long;
function showPosition(position) {

$('#lat').html(position.coords.latitude); 
$('#long').html(position.coords.longitude); 
latlon=position.coords.latitude+','+position.coords.longitude

    $.ajax({
        type: "GET",
        url: "/api/pharmacy/"+$('#lat').html()+'/'+$('#long').html()+'/5',
        // The key needs to match your method's input parameter (case-sensitive).
        data: '',
        //headers: {"Authorization": "Basic " + btoa( "admin:admin" )},
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data){
            content = '<ul>';
            $(data).each(function(idx,pharmacy){

                first = (idx==0) ? 'bold' : '';
                content += '<li data="'+pharmacy.lat+','+pharmacy.long+'" class="'+first+'">'+pharmacy.name+ ' '+ pharmacy.address+ ' '+ pharmacy.city+ ' '+ pharmacy.state+ ' '+ pharmacy.zip+  ' (Distance '+pharmacy.distance+') '+
                '</li>';
            });
            content+='</ul>';
            $('#pharmacies').html(content);
            ll = $('.bold').attr('data');
            var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+ll+"&zoom=14&size=500x400&sensor=false&key=AIzaSyBzjsipXyspoNGmLGuzja-9_Qzao_wcp4k";

            document.getElementById("map").innerHTML = "<img src='"+img_url+"'>";
            
        },
        failure: function(errMsg) {
            alert(errMsg);
        }
    });



}
if (navigator.geolocation) {
navigator.geolocation.getCurrentPosition(showPosition);
} else { 
alert("Geolocation is not supported by this browser.");
}



})(jQuery);
      


</script>
      
    </body>
</html>
