<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $gallery_name }}</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/navbar-fixed-top.css" rel="stylesheet">

    @if (Auth::check())    
    
    <link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">

    @endif
    
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.min.css">    
    <style> 
        .thumb img { 
          filter: none; /* IE6-9 */ 
          -webkit-filter: grayscale(0); 
          border-radius:5px; 
          background-color: #fff; 
          border: 1px solid #ddd; 
          padding:5px; 
        } 
        .thumb img:hover { 
          filter: gray; /* IE6-9 */ 
          -webkit-filter: grayscale(1); 
        } 
        .thumb { 
          padding:5px; 
        } 
        
        @yield('css')
        
              
    </style>     
  </head>

  <body>

@include('template.menu')

    <div class="container">


        @yield('content')
        

      
      
    </div> <!-- /container -->

    
    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>

    <!-- JS -->
    <script src="/js/jquery.fancybox.min.js"></script>
    
    
    @if (Auth::check())
    
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/dataTables.bootstrap.min.js"></script>
    <script src="/js/bootstrap-confirmation.js"></script>
    
    @endif
    
    <script type="text/javascript"> 
        $(document).ready(function() { 
          $("a.fancyimage").fancybox(); 
          
          @yield('js')
          
          @if (Auth::check())
              
          $('#album-table').DataTable();
          
          $('#group-table').DataTable();
          
          $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            btnOkLabel: 'Да',
            btnOkIcon: 'Нет',
          });          
          
          @endif
          
        }); 
    </script>     
  </body>
</html>
