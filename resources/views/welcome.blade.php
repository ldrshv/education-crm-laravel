<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>#app</title>
        
        <!-- Scripts -->
        @vite(['_frontend/app.js'])
    </head>
    
    <body class="">
        <div id="app"></div>
    </body>
</html>
