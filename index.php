<?php
  
  $url = $_SERVER['HTTP_HOST'];

  $parsed_url = parse_url($url);
  $host = explode('.', $parsed_url['host']);

  switch( strtolower( $host[0] ) ) {

    case 'bob':
              $title = 'Bob Tracker';
              break;

    case 'julius':
              $title = 'Julius Tracker';
              break;

    default:  $title = 'Tracker';
              break;
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $title; ?></title>

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//cdn.leafletjs.com/leaflet-0.7.3/leaflet.css">
    <link rel="stylesheet" href="//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css">
    <link rel="stylesheet" href="//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css">
    <link rel="stylesheet" href="//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.css">
    <link rel="stylesheet" href="assets/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.css">
    <link rel="stylesheet" href="assets/css/app.css">

    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicon-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicon-152.png">
    <link rel="icon" sizes="196x196" href="assets/img/favicon-196.png">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <div class="navbar-icon-container">
            <a href="#" class="navbar-icon pull-right visible-xs" id="nav-btn"><i class="fa fa-bars fa-lg white"></i></a>
            <a href="#" class="navbar-icon pull-right visible-xs" id="sidebar-toggle-btn"><i class="fa fa-search fa-lg white"></i></a>
          </div>
          <a class="navbar-brand" href="#"><?php echo $title; ?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="about-btn"><i class="fa fa-question-circle white"></i>&nbsp;&nbsp;About</a></li>
            <li class="dropdown">
              <a id="toolsDrop" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe white"></i>&nbsp;&nbsp;Tools <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="full-extent-btn"><i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;Zoom To Full Extent</a></li>
                <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="legend-btn"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Show Legend</a></li>
                <li class="divider hidden-xs"></li>
                <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="login-btn"><i class="fa fa-user"></i>&nbsp;&nbsp;Login</a></li>
              </ul>
            </li>
            <li class="hidden-xs"><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="list-btn"><i class="fa fa-list white"></i>&nbsp;&nbsp;History</a></li>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <div id="container">
      <div id="sidebar">
        <div class="sidebar-wrapper">
          <div class="panel panel-default" id="features">
            <div class="panel-heading">
              <h3 class="panel-title">History
              <button type="button" class="btn btn-xs btn-default pull-right" id="sidebar-hide-btn"><i class="fa fa-chevron-left"></i></button></h3>
            </div>
            <div class="panel-body">
              <p>
                <div class="row">
                  <div class="col-xs-8 col-md-8">
                    <input type="text" class="form-control search" placeholder="Filter" />
                  </div>
                  <div class="col-xs-4 col-md-4">
                    <button type="button" class="btn btn-primary pull-right sort" data-sort="feature-name" id="sort-btn"><i class="fa fa-sort"></i>&nbsp;&nbsp;Sort</button>
                  </div>
                </div>
              </p>
            </div>
            <div class="sidebar-table">
              <table class="table table-hover" id="feature-list">
                <thead class="hidden">
                  <tr>
                    <th>Icon</th>
                  <tr>
                  <tr>
                    <th>Name</th>
                  <tr>
                  <tr>
                    <th>Chevron</th>
                  <tr>
                </thead>
                <tbody class="list"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div id="map"></div>
    </div>
    <div id="loading">
      <div class="loading-indicator">
        <div class="progress progress-striped active">
          <div class="progress-bar progress-bar-info progress-bar-full"></div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="aboutModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Welcome to the Dark Water tracker!</h4>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs" id="aboutTabs">
              <li class="active"><a href="#about" data-toggle="tab"><i class="fa fa-question-circle"></i>&nbsp;About the project</a></li>
              <li><a href="#contact" data-toggle="tab"><i class="fa fa-envelope"></i>&nbsp;Contact us</a></li>
              <li><a href="#disclaimer" data-toggle="tab"><i class="fa fa-exclamation-circle"></i>&nbsp;Disclaimer</a></li>
            </ul>
            <div class="tab-content" id="aboutTabsContent">
              <div class="tab-pane fade active in" id="about">
                <p>A simple, responsive template for building web mapping applications with <a href="http://getbootstrap.com/">Bootstrap 3</a>, <a href="http://leafletjs.com/" target="_blank">Leaflet</a>, and <a href="http://twitter.github.io/typeahead.js/" target="_blank">typeahead.js</a>. Open source, MIT licensed, and available on <a href="https://github.com/bmcbride/bootleaf" target="_blank">GitHub</a>.</p>
              <div id="disclaimer" class="tab-pane fade text-danger">
                <p>The data provided on this site is for informational and planning purposes only.</p>
                <p>Absolutely no accuracy or completeness guarantee is implied or intended. All information on this map is subject to such variations and corrections as might result from a complete title search and/or accurate field survey.</p>
              </div>
              <div class="tab-pane fade" id="contact">
                <form id="contact-form">
                  <div class="well well-sm">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="first-name">First Name:</label>
                          <input type="text" class="form-control" id="first-name">
                        </div>
                        <div class="form-group">
                          <label for="last-name">Last Name:</label>
                          <input type="text" class="form-control" id="last-email">
                        </div>
                        <div class="form-group">
                          <label for="email">Email:</label>
                          <input type="text" class="form-control" id="email">
                        </div>
                      </div>
                      <div class="col-md-8">
                        <label for="message">Message:</label>
                        <textarea class="form-control" rows="8" id="message"></textarea>
                      </div>
                      <div class="col-md-12">
                        <p>
                          <button type="submit" class="btn btn-primary pull-right" data-dismiss="modal">Submit</button>
                        </p>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="legendModal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Map Legend</h4>
          </div>
          <div class="modal-body">
            <p>Map Legend goes here...</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Login</h4>
          </div>
          <div class="modal-body">
            <form id="contact-form">
              <fieldset>
                <div class="form-group">
                  <label for="name">Username:</label>
                  <input type="text" class="form-control" id="username">
                </div>
                <div class="form-group">
                  <label for="email">Password:</label>
                  <input type="password" class="form-control" id="password">
                </div>
              </fieldset>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" data-dismiss="modal">Login</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="featureModal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title text-primary" id="feature-title"></h4>
          </div>
          <div class="modal-body" id="feature-info"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="attributionModal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
              Powered by <a href='http://darkwaterfoundation.org'>Dark Water</a>
            </h4>
          </div>
          <div class="modal-body">
            <div id="attribution"></div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.5/typeahead.bundle.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.3.0/handlebars.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
    <script src="//cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
    <script src="//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js"></script>
    <script src="//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.js"></script>
    <script src="assets/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.js"></script>
    <script src="assets/js/app.js"></script>
  </body>
</html>