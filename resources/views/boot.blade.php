<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="/css/styles.css">
  <link rel="stylesheet" href="/css/styles2.css">
  <script src="https://kit.fontawesome.com/6da2e1b6c1.js" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#" id="logo">E Vector Quizzes</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        @include('.inc.navigation')
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="/admin/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
  
<div class="container-fluid text-center">    
  <div class="row content">
    <div class="col-sm-1 sidenav">

    </div>
    <div class="col-sm-9 text-left"> 
      <div class="c_content">

            @include('.inc.messages')
            @yield('content')
            
      </div>
    </div>
    <div class="col-sm-2 sidenav">
      <div class="well">
        <p><img src="https://global-uploads.webflow.com/5e693ac3893940c39c851249/5ee24ec64d4f28309cb2f998_DigitalOcean.png" width="200"></p>
      </div>
      <div class="well">
        <img src="https://img1.wsimg.com/cdn/Image/All/All/1/All/cfc868ac-498f-44aa-ba4a-c1fa6b82659e/og-hosting.jpg" width="200" alt="">
      </div>
    </div>
  </div>
</div>

<footer class="container-fluid text-center">
  <p>&copy Evector.biz</p>
</footer>

</body>
</html>