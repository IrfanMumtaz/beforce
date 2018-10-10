<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>MarkerClusterer v3 Advanced Example</title>

    <style>
      body {
        margin: 0;
        padding: 10px 20px 20px;
        font-family: Arial;
        font-size: 16px;
      }
      #map-container {
        padding: 6px;
        border-width: 1px;
        border-style: solid;
        border-color: #ccc #ccc #999 #ccc;
        -webkit-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
        -moz-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
        box-shadow: rgba(64, 64, 64, 0.1) 0 2px 5px;
        width: 100%;
      }
      #map {
        width: 100%;
        height: 600px;
      }
      #actions {
        list-style: none;
        padding: 0;
      }
      #inline-actions {
        padding-top: 10px;
      }
      .item {
        margin-left: 20px;
      }
    </style>

    <!-- <script src="https://maps.googleapis.com/maps/api/js"></script> -->
<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDV4k8Ka_EuBhfcQ1EpZpNzuoFxAWb9lmQ&v=3"  type="text/javascript"></script>
    <script src="{{ asset('js/new_data.json') }}"></script>
    <script type="text/javascript" src="{{ asset('js/markerclusterer.js') }}"></script>
    <script>      
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

var pics = null;
var map = null;
var markerClusterer = null;
var markers = [];
var infoWindow = null;

function initialize() {
  var latlng = new google.maps.LatLng(30.3753, 69.3451);
  var options = {
    'zoom': 4,
    'center': latlng,
    'mapTypeId': google.maps.MapTypeId.ROADMAP
  };

  map = new google.maps.Map(document.getElementById('map'), options);
  pics = data.photos;
  infoWindow = new google.maps.InfoWindow();

  showMarkers();
};

function showMarkers() {
  markers = [];

  if (markerClusterer) {
    markerClusterer.clearMarkers();
  }

  for (var i = 0; i < data.count; i++) {
    var titleText = pics[i].employee_name;
    if (titleText === '') {
      titleText = 'No title';
    }
    var latLng = new google.maps.LatLng(pics[i].latitude,
        pics[i].longitude);

    var imageUrl ='http://chart.apis.google.com/chart?cht=mm&chs=24x32&' +
          'chco=FFFFFF,008CFF,000000&ext=.png';
    var markerImage = new google.maps.MarkerImage(imageUrl,
        new google.maps.Size(24, 32));
    if(pics[i].distance>50) { 
    var marker = new google.maps.Marker({
      'position': latLng
    });

    var fn = markerClickFunction(pics[i], latLng);
    google.maps.event.addListener(marker, 'click', fn);
    markers.push(marker); 
  }
  else
  {
    var marker = new google.maps.Marker({
      'position': latLng,
      'icon': markerImage
    });

    var fn = markerClickFunction(pics[i], latLng);
    google.maps.event.addListener(marker, 'click', fn);
    markers.push(marker); 
  }
  }

  window.setTimeout(time, 0);
};

function markerClickFunction(pic, latlng) {
  return function(e) {
    e.cancelBubble = true;
    e.returnValue = false;
    if (e.stopPropagation) {
      e.stopPropagation();
      e.preventDefault();
    }
    var title = pic.employee_name;
    var url = pic.UserAvatar;

    var infoHtml = '<div style="display: inline-block; overflow: auto; max-height: 654px; max-width: 654px;"><div style="overflow: auto;"><div class="info-window two fields"><div class="field"><img src='+pic.UserAvatar+' style="max-height: 150px;width: 110px;" alt="No Image"></div><div class="field"><div class="info-row"><strong>Name</strong>: '+pic.employee_name+'</div><div class="info-row"><strong>Brand</strong>: '+pic.brand+'</div><div class="info-row"><strong>Designation</strong>: '+pic.role+'</div><div class="info-row"><strong>Date of Deployment</strong>: '+pic.DateOfDeployment+'</div><div class="info-row"><strong>Store</strong>: '+pic.location+'</div><div class="info-row"><strong>City</strong>: '+pic.city+'</div></div></div></div></div>';


    infoWindow.setContent(infoHtml);
    infoWindow.setPosition(latlng);
    infoWindow.open(map);
  };
};

function change() {
  clear();
  showMarkers();
};

 function time() {
    markerClusterer = new MarkerClusterer(map, markers, {imagePath: baseUrl+'/public/images/m'});
};

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body>
    <h3>An example of MarkerClusterer v3</h3>
    <div id="map-container">
      <div id="map"></div>
    </div>   
  </body>
</html>
