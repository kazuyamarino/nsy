<?php
defined('ROOT') OR exit('No direct script access allowed');
?>
<!doctype html>
<html class="no-js" lang="@( get_lang_code() )" prefix="@( get_og_prefix() )">
<head>
    <!-- call header assets method -->
    @( Pull::header_assets() )
</head>
<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <header style="text-align:center" class="header">
        <a href="@( base_url() )">
            @raw( img( base_url('public/img/logo.png'), 'width="100"' ) )
        </a>
        <h2>@( $my_name )</h2>
        <p>NSY is a simple PHP Framework that works well on MVC or HMVC mode.</p>
        <a target="_blank" href="https://github.com/kazuyamarino/nsy">View On Github</a>
        <hr>
    </header>
