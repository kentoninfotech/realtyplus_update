<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Realty Plus | {{$page_title}}</title>

  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">


</head>
<body onload="window.print()">
                <div style="text-align: center !important"><img src="{{asset('dist/img/banner.png')}}" alt="kenton" height="200" width="auto"></div>


    

    <section class="content">
        @yield('content')
    </section>

</body>
</html>
