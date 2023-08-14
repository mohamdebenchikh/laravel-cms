<!DOCTYPE html>
<html lang="{{ App::currentLocale() }}" data-bs-theme="light">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    @if(App::isLocale('ar'))
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.rtl.min.css" integrity="sha384-PRrgQVJ8NNHGieOA1grGdCTIt4h21CzJs6SnWH4YMQ6G5F5+IEzOHz67L4SQaF0o" crossorigin="anonymous">
      <script>
      </script>
    @else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    @endif

    @vite('resources/js/app.js')
    @routes
    @inertiaHead
  </head>
  <body dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">
    @inertia

    <script>
      window.locale = '{{ App::currentLocale() }}';
    </script>
  </body>
</html>
