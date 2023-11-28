<!DOCTYPE html>
<html lang="en">
<head>
  <!-- google analytics 2 -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-JRX2KRWV8Y"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-JRX2KRWV8Y');
  </script>
  <!-- google analytics (@ends) -->
  <title>@yield('title')</title>
  
  <meta name="description" content="@yield('description')">
  <meta name="keywords" content="@yield('keywords')">
  @if(env('APP_ENV')  == 'local' || (!empty($quiz->public) && $quiz->public == 1))
  <META NAME="robots" CONTENT="noindex,nofollow">
  @endif
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/css/styles2.css">
  <script src="https://kit.fontawesome.com/6da2e1b6c1.js" crossorigin="anonymous"></script>
  
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<!-- style="margin: 0px 0px -5px 0px;" -->
  <div class="container-fluid">
    <a class="navbar-brand" href="/" style="color: #7FF9C1;"><b>Quizzes.Ltd</b></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            <li class="nav-item">
                <a class="nav-link" href="/pageStatic/about_us" title="About Us">About Us</a>
            </li>  
            <li class="nav-item">
                <a class="nav-link" href="/quizes" title="Quizes">Quizzes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/categoriesTree" title="Quizes">Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/contactUs" title="Contact"> Contact</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="/checkout_stripe" title="Stripe"> Stripe</a>
            </li> -->
            <!-- checkout_stripe -->
            <li class="nav-item">
                <div class="pt-2 ms-3">
                  <form action="/search" method="">
                    <input type="search" name="keyword" placeholder="Search" aria-label="Search" style="padding: ">
                  </form>
                </div>
            </li>
        </ul>

        <div class="d-flex">
          <!-- <a class="nav-link" href="#">
              <form action="/search" method="">
                <input type="search" name="keyword" placeholder="Search" aria-label="Search" style="margin-top: -2px; padding: 0px; 5px;">
              </form>
          </a> -->
          <a class="nav-link" href="/cart">
            <i class="fa-sharp fa-solid fa-cart-shopping fa-lg"></i> 
            <b style="color: magento;"> <span id="cart-count">0</span> </b>
          </a>

          @if(!empty($_SESSION['user']) && $_SESSION['user_id'] != 777)

            <a class="nav-link" href="/myPage"><i class="fa-sharp fa-solid fa-user fa-sm"></i> <b>Hi {{ $_SESSION['username'] }}</b>,</a>
            <a class="nav-link" href="/myPage" title=""><i class="fa-sharp fa-solid fa-gear fa-sm"></i> My Account</a>
            <a class="nav-link" href="/logout" title=""><i class="fa-sharp fa-solid fa-arrow-up-left-from-circle fa-sm"></i> Logout</a>
            
          @else 
            <a class="nav-link" href="/signUp" title=""><i class="fa-sharp fa-solid fa-user-plus fa-sm"></i> Sign up</a>
            <a class="nav-link" href="/logIn" title=""><i class="fa-sharp fa-solid fa-right-to-bracket fa-sm"></i> Login</a> 
          
          @endif
        </div>
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
  - <a href="/checkout_stripe">Stripe</a>  
</footer>
<script src="/js/password.js"></script>
<script src="/js/shop.js"></script>
</body>
</html>