<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Systém pro mateřské školy, který zjednodušší každodenní procesy ve škole, usnadní komunikaci s rodiči a přenese školu do moderní doby.">

    <!-- Apple meta -->
    <link rel="apple-touch-icon-precomposed" sizes="156x156" href="assets/img/apple-touch-156.png">
    <link rel="apple-touch-icon-precomposed" href="assets/img/apple-touch-120.png">
    <meta name="apple-mobile-web-app-title" content="Karellen.cz">

    <!-- FB Sharing -->
    <meta property="og:image" content="http://www.karellen.cz/assets/img/fb-share.png">
    <meta property="og:title" content="Karellen - Úžasné řešení každodenní agendy v mateřské škole">
    <meta property="og:description" content="Systém pro mateřské školy, který zjednodušší každodenní procesy ve škole, usnadní komunikaci s rodiči a přenese školu do moderní doby.">
    <meta property="og:url" content="http://www.karellen.cz">
    <meta property="og:site_name" content="Karellen - Úžasné řešení každodenní agendy v mateřské škole">
    <meta property="og:type" content="website">
    <title>
        <?php print($title); ?>
    </title>

    <link rel="icon" type="image/png" href="assets/img/favicon.png">

    <!-- CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/fancybox.css" rel="stylesheet">
    <link href="assets/css/datetimepicker.css" rel="stylesheet">
    <link href="assets/css/font.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <header>
        <div class="container">
            <div class="row">
                <div class="logo">
                    <a href="index.php"><img src="assets/img/logo.jpg" alt="Hafík hlídání dětí - logo"></a>
                </div>
                <h1 class="logo-h1">Rodiče máte volno!<span>hlídací centrum pro děti od 2 - 8 let</span></h1>
            </div>
        </div>
        <section class="menu">
            <div class="container">
                <div class="row">
                    <button class="mobile" data-toggle="mobile"><i class="fa fa-bars"></i> MENU</button>
                    <ul class="nav">
                        <li <?php $menu==0 ? print( 'class="active"') : '' ?>><a href="index.php">Úvod</a></li>
                        <li <?php $menu==1 ? print( 'class="active"') : '' ?>><a href="o-nas.php">O Nás</a></li>
<!--                        <li --><?php //$menu==2 ? print( 'class="active"') : '' ?><!--<a href="rezervace.php">Rezervace</a></li>-->
                        <li <?php $menu==3 ? print( 'class="active"') : '' ?>><a href="sluzby.php">Služby</a></li>
                        <li <?php $menu==4 ? print( 'class="active"') : '' ?>><a href="cenik.php">Ceník</a></li>
                        <li <?php $menu==5 ? print( 'class="active"') : '' ?>><a href="provozni-rad.php">Provozní řád</a></li>
                        <li <?php $menu==6 ? print( 'class="active"') : '' ?>><a href="co-s-sebou-do-batuzku.php">Co s sebou do batůžku</a></li>
                        <li <?php $menu==7 ? print( 'class="active"') : '' ?>><a href="kudy-k-nam.php">Kudy k nám</a></li>
                    </ul>
                </div>
            </div>
        </section>
    </header>
