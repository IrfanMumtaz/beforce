function $(element) {
  return document.getElementById(element);
}

var googleMap = {};
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

googleMap.pics = null;
googleMap.map = null;
googleMap.markerClusterer = null;
googleMap.markers = [];
googleMap.infoWindow = null;

googleMap.init = function() {
  var latlng = new google.maps.LatLng(30.3753, 69.3451);
  var options = {
    'zoom': 4,
    'center': latlng,
    'mapTypeId': google.maps.MapTypeId.ROADMAP
  };

  googleMap.map = new google.maps.Map($('map'), options);
  googleMap.pics = data.photos;
  googleMap.infoWindow = new google.maps.InfoWindow();

  googleMap.showMarkers();
};

googleMap.showMarkers = function() {
  googleMap.markers = [];

  if (googleMap.markerClusterer) {
    googleMap.markerClusterer.clearMarkers();
  }

  for (var i = 0; i < data.count; i++) {
    var titleText = googleMap.pics[i].employee_name;
    if (titleText === '') {
      titleText = 'No title';
    }
    var latLng = new google.maps.LatLng(googleMap.pics[i].latitude,
        googleMap.pics[i].longitude);

    var imageUrl ='http://chart.apis.google.com/chart?cht=mm&chs=24x32&' +
          'chco=FFFFFF,008CFF,000000&ext=.png';
    var markerImage = new google.maps.MarkerImage(imageUrl,
        new google.maps.Size(24, 32));
    if(googleMap.pics[i].distance>50) { 
    var marker = new google.maps.Marker({
      'position': latLng
    });

    var fn = googleMap.markerClickFunction(googleMap.pics[i], latLng);
    google.maps.event.addListener(marker, 'click', fn);
    googleMap.markers.push(marker); 
  }
  else
  {
    var marker = new google.maps.Marker({
      'position': latLng,
      'icon': markerImage
    });

    var fn = googleMap.markerClickFunction(googleMap.pics[i], latLng);
    google.maps.event.addListener(marker, 'click', fn);
    googleMap.markers.push(marker); 
  }
  }

  window.setTimeout(googleMap.time, 0);
};

googleMap.markerClickFunction = function(pic, latlng) {
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


    googleMap.infoWindow.setContent(infoHtml);
    googleMap.infoWindow.setPosition(latlng);
    googleMap.infoWindow.open(googleMap.map);
  };
};

googleMap.change = function() {
  googleMap.clear();
  googleMap.showMarkers();
};

googleMap.time = function() {
    googleMap.markerClusterer = new MarkerClusterer(googleMap.map, googleMap.markers, {imagePath: baseUrl+'/public/images/m'});
};
