<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <META NAME="robots" CONTENT="noindex,nofollow">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/css/styles2.css">
  <link rel="stylesheet" href="/css/styles.css">
  <script src="https://kit.fontawesome.com/6da2e1b6c1.js" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark nav_color">
  <div class="container-fluid">
    <a class="navbar-brand" href="#" style="color: #7FF9C1;">E Vector Quizzes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      @include('inc.navigation')
    </div>
  </div>
</nav>
<div class="container" id="main_container" style="">
    @include('.inc.messages')
    @yield('content')
</div>
<!-- <footer class="container-fluid text-center">
  <p>&copy Evector.biz</p>
</footer> -->
<script>
  
  const allLinks = document.querySelectorAll('a');

        allLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                // Get the href attribute of the clicked link
                const href = link.getAttribute('href');

                // Check if the href matches the pattern "/delete_quiz/{id}"
                if (href && href.match(/^\/delete_quiz\/\d+$/)) {
                    // Prevent the default behavior of the link (i.e., navigating to the URL)
                    event.preventDefault();

                    // Extract the {id} from the href attribute
                    const id = href.split('/').pop();

                    // Get the quiz name from the link's text
                    const quizName = link.textContent.trim();

                    // Show a confirmation dialog with the quiz name
                    const isConfirmed = confirm(`Are you sure you want to delete this quiz?`);

                    // If the user confirms, proceed to the link
                    if (isConfirmed) {
                        window.location.href = href;
                    }
                }
            });
          });
</script>
</body>
</html>