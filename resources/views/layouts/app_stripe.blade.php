<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title')</title>
  
  <meta name="description" content="@yield('description')">
  <meta name="keywords" content="@yield('keywords')">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   -->
  <link rel="stylesheet" href="/css/styles2.css">
  <script src="https://kit.fontawesome.com/6da2e1b6c1.js" crossorigin="anonymous"></script>

  <link rel="/css/stylesheet" href="/css/checkout_stripe.css" />
  <script src="https://js.stripe.com/v3/"></script>
  <script src="/js/checkout_stripe.js" defer></script>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

  <div class="container-fluid">
    <a class="navbar-brand" href="/" style="color: #7FF9C1;"><b>Quizzes.Ltd</b></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">


    </div>
  </div>
</nav>

<div class="container" id="main_container" style="">
<script src="/js/cart_main.js"></script>
    @include('.inc.messages')
    @yield('content')

</div>
<footer class="container-fluid text-center">
  &copy Evector.biz 2023 - <a href="/pageStatic/privacy_policy">Privacy</a> - <a href="/pageStatic/terms_and_conditions">Terms</a>
  -   
</footer>
<script src="/js/password.js"></script>
<script src="/js/shop.js"></script>
</body>
</html>