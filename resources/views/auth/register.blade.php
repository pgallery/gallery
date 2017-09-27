<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>Регистрация</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
 
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
        <div class="row centered-form">
            <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif        
        
      <form class="form-horizontal" action="{{ route('register') }}" method="POST">
          
        {!! csrf_field() !!}
        
        <div class="panel-heading">
            <h2 class="panel-title">Регистрация</h2>
        </div>
        <div class="row">
            <label for="inputName" class="sr-only">Ваше имя: </label>
            <input name="name" type="name" id="inputName" class="form-control" placeholder="Ваше имя" required autofocus>
            <label for="inputEmail" class="sr-only">Ваш E-Mail: </label>
            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Ваш E-Mail" required>
            <label for="inputPassword" class="sr-only">Пароль: </label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Пароль" required>
            <label for="inputPasswordConfirm" class="sr-only">Повторите пароль: </label>
            <input type="password" name="password_confirmation" id="inputPasswordConfirm" class="form-control" placeholder="Повторите пароль" required>

            <button class="btn btn-lg btn-primary btn-block" type="submit">Зарегистрироваться</button>
        </div>
      </form>

            </div>
        </div>
    </div> <!-- /container -->



    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
