# NSY
NSY is a simple PHP Framework that works well on MVC or HMVC mode, its made with HTML5 Boilerplate and Foundation CSS Framework. NSY also provides Font-Awesome and several optimizations for Datatables plugin.

Site example :
<a href="https://nsy.kazuyamarino.com/" target="_blank">https://nsy.kazuyamarino.com/</a>


## NSY folder structure

```bash
.
├── composer.json
├── Docs
│   ├── apache config
│   │   ├── hide_public
│   │   ├── Readme.txt
│   │   └── sites_public
│   └── nginx config
│       ├── nginx.conf
│       └── sites-available
│           └── default
├── LICENSE.txt
├── Public
│   ├── 403.html
│   ├── 404.html
│   ├── index.php
│   ├── robots.txt
│   └── Template
│       ├── css
│       │   ├── main.css
│       │   └── vendor
│       │       ├── dataTables.foundation.min.css
│       │       ├── foundation.min.css
│       │       └── responsive-tables.min.css
│       ├── footer.php
│       ├── header.php
│       ├── img
│       │   ├── favicon.png
│       │   └── logo.png
│       └── js
│           ├── datatables
│           │   └── init.js
│           ├── main.js
│           └── vendor
│               ├── dataTables.foundation.min.js
│               ├── foundation.min.js
│               ├── jquery.dataTables.min.js
│               ├── jquery.min.js
│               ├── modernizr.min.js
│               ├── responsive-tables.min.js
│               └── what-input.min.js
├── README.md
└── System
    ├── Controllers
    │   └── Welcome.php
    ├── Core
    │   ├── NSY_Config.php
    │   ├── NSY_Controller.php
    │   ├── NSY_DB.php
    │   ├── NSY_Router.php
    │   └── Routing.php
    ├── Libraries
    │   └── AssetManager
    │       ├── Assets.php
    │       └── NSY_AssetManager.php
    ├── Models
    │   └── Model_Welcome.php
    ├── Modules
    │   └── Homepage
    │       ├── Controllers
    │       │   └── Hello.php
    │       ├── Models
    │       │   └── Model_Hello.php
    │       └── Views
    │           └── index.php
    ├── Vendor
    │   ├── autoload.php
    │   └── composer
    │       ├── autoload_classmap.php
    │       ├── autoload_files.php
    │       ├── autoload_namespaces.php
    │       ├── autoload_psr4.php
    │       ├── autoload_real.php
    │       ├── autoload_static.php
    │       ├── ClassLoader.php
    │       └── LICENSE
    └── Views
        └── index.php
```


## How to dating with NSY?
- Simply rename the folder that has been downloaded to `nsy` & copy it to your `html` or `htdocs` folder or git clone it.
- For apache, please go to the `docs/apache config` folder and read the Readme.txt.
- For nginx, please go to the `docs/nginx config` folder.
- And save the date..


## NSY contain package :
- [Datatables jQuery Javascript Library](https://www.datatables.net/) with Responsive Plugin
- [Foundation Zurb Framework](https://foundation.zurb.com/)
- [JQuery](https://jquery.com/)
- [Modernizr](https://modernizr.com/)
- [WhatInputJs](https://github.com/ten1seven/what-input)
- [Font Awesome CDN](https://fontawesome.com/)


## NSY Feature :
- MVC or HMVC
- NSY Assets Manager
- PSR-4 Autoloading

### MVC & HMVC
- The Model View Controller (MVC) design pattern specifies that an application consist of a data model, presentation information, and control information. The pattern requires that each of these be separated into different objects.
- The Hierarchical Model View Controller (HMVC) is an evolution of the MVC pattern used for most web applications today. It came about as an answer to the scalability problems apparent within applications which used MVC.

### Introducting to NSY Assets Manager
The easiest & best assets manager in history
Made with love by Vikry Yuansah

How to use it? Simply follow this.
- First, you need to go to `System/Libraries/AssetManager/`, there are 2 files, that is `Assets.php` & `NSY_AssetManager.php`.
- `NSY_AssetManager.php` is the core, & `Assets.php` is the controller which regulates assets, if you want to manage the assets, please go to `Assets.php`.

Create `<meta>` tag :
```
$this->meta('name', 'content');
```

Create `<link>` tag :
```
$this->link('filename/url_filename', 'attribute_rel', 'attribute_type');
```

Create `<script>` tag :
```
$this->script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');
```

You can write any html tags with custom method :
```
$this->custom('anythings');
```

### PSR-4 Autoloading
- NSY applies the concept of PSR-4 Autoloading. NSY has the `composer.json` file that can be dumped with [composer](https://getcomposer.org/download/) command `composer dump-autoload` when creating a folder structure that contains new class files.
- Complete information about PSR-4 can be read on the official [PHP-FIG](https://www.php-fig.org/psr/psr-4/) website.


## Browser support test

---
title: Compatibility
description: Foundation is tested across many browsers and devices, and works back as far as IE9 and Android 2.
tags:
  - support
  - browser
---

## Overview

<table class="docs-compat-table">
  <tr>
    <td>Chrome</td>
    <td class="works" rowspan="7">Last Two Versions</td>
  </tr>
  <tr><td>Firefox</td></tr>
  <tr><td>Safari</td></tr>
  <tr><td>Opera</td></tr>
  <tr><td>Mobile Safari<sup>1</sup></td></tr>
  <tr><td>IE Mobile</td></tr>
  <tr><td>Edge</td></tr>
  <tr>
    <td>Internet Explorer</td>
    <td class="works">Versions 9+</td>
  </tr>
  <tr>
    <td>Android Browser</td>
    <td class="works">Versions 4.4+</td>
  </tr>
</table>

<sup>1</sup>iOS 7+ is actively supported but with some known bugs.

## What Won't Work?

- **The Grid:** Foundation's grid uses `box-sizing: border-box` to apply gutters to columns, but this property isn't supported in IE8.
- **Desktop Styles:** Because the framework is written mobile-first, browsers that don't support media queries will display the mobile styles of the site.
- **JavaScript:** Our plugins use a number of handy ECMAScript 5 features that aren't supported in IE8.

*This doesn't mean that NSY cannot be used in older browsers,
just that we'll ensure compatibility with the ones mentioned above.*
*NSY Browser support information based on [Foundation Zurb Compatibility](https://foundation.zurb.com/sites/docs/compatibility.html).


## User Guide.
- On Progress.


## License

The code is available under the [MIT license](LICENSE.txt)..
