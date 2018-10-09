@inject('meta', 'App\Facades\ViewerFacade')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="{{ $meta->getDescription() }}">
    <meta name="keywords" content="{{ $meta->getKeywords() }}">

    <title>{{ $meta->getTitle() }}</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/navbar-fixed-top.css" rel="stylesheet">

    @if (Auth::check())    
        
        <link href="/css/bootstrap-tagsinput.css" rel="stylesheet">
        <link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="/css/dropzone.css" rel="stylesheet">
    
    @endif
    
    @if(Setting::get('use_ulogin') == 'yes')

        <script src="//ulogin.ru/js/ulogin.js"></script>    

    @endif

    @if(Setting::get('comment_engine') == 'VK')
    
        <script type="text/javascript" src="//vk.com/js/api/openapi.js?150"></script>
        <script type="text/javascript">
            VK.init({apiId: {{ Setting::get('comment_vk') }}, onlyWidgets: true});
        </script>

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
    
    @if(Setting::get('use_yandexmetrika') == 'yes')

        <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter{{ Setting::get('yandexmetrika_id') }} = new Ya.Metrika2({
                            id:{{ Setting::get('yandexmetrika_id') }},
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true
                        });
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/tag.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks2");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/{{ Setting::get('yandexmetrika_id') }}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->   

    @endif    
    
  </head>

  <body>

@include('default.layouts.menu')

    <div class="container">

        @if(Roles::is('admin'))        
            <div id="showStatus">
                
            </div>
        @endif
        
        @yield('content')
        

      
      
    </div> <!-- /container -->

    
    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>

    <!-- JS -->
    <script src="/js/jquery.fancybox.min.js"></script>
    
    
    @if (Auth::check())
    
    <script src="/js/typeahead.bundle.js"></script>
    <script src="/js/bootstrap-tagsinput.min.js"></script>
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/dataTables.bootstrap.min.js"></script>
    <script src="/js/bootstrap-confirmation.js"></script>
    <script src="/js/dropzone.js"></script>

    <script type="text/javascript" src="/js/bootstrap-filestyle.min.js"> </script>
    
    @endif
    
    <script type="text/javascript"> 
        
        @yield('js-top')
              
        
        @if (Roles::is('admin'))
            
        function showStatus()  
            {      
                $.ajax({
                    type: "GET", 
                    url: "/admin/status", 
                    cache: false,
                    dataType: "json", 
                    error: function(xhr) {
                        document.getElementById("showStatus").innerHTML='';
                    },
                    success: function(a) {
                        var status = '';
                        if(a['run'] > 0) {
                            status += 'Фоновых процессов: ' + a['run'] + '. ';
                        }
                        if(a['rebuild'] > 0) {
                            status += 'Изображений в очереди: ' + a['rebuild'] + '. ';
                        }
                        document.getElementById("showStatus").innerHTML="<div class='alert alert-success' role='alert'>" + status + "</div>";
                    }  
                });
            }
            
        @endif
        
        $(document).ready(function() { 
          $("a.fancyimage").fancybox(); 
          
          @yield('js')
          
          @if (Roles::is('admin'))
          
            showStatus();  
            setInterval('showStatus()',2000);
            
          @endif
          
          @if (Auth::check())
          

          var albumtable = $('#album-table').DataTable({
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"] ],
            "language": {
              "search": "Фильтр:",
              "sLengthMenu": "Отображать _MENU_ записей",
              "paginate": {
                "previous": "Назад",
                "next": "Дальше"
              }
            }
          });

          albumtable
            .order( [ 0, 'desc' ] )
            .draw();

          var tagtable = $('#tag-table').DataTable({
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"] ],
            "language": {
              "search": "Фильтр:",
              "sLengthMenu": "Отображать _MENU_ записей",
              "paginate": {
                "previous": "Назад",
                "next": "Дальше"
              }              
            }
          });

          tagtable
            .order( [ 1, 'asc' ] )
            .draw();

          $('#group-table').DataTable({
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"] ],
            "language": {
              "search": "Фильтр:",
              "sLengthMenu": "Отображать _MENU_ записей",
              "paginate": {
                "previous": "Назад",
                "next": "Дальше"
              }              
            }
          });
          
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
