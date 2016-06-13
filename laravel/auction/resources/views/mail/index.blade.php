<!DOCTYPE html>
<html>
<head>
   <title>@yield('title')</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>




<?php
if(isset($link)){
?>
    <p>{{$link}}</p>
    <?php
}
?>
</body>
</html>

