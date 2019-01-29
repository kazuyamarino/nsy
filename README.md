# NSY-Foundation
NSY is a simple PHP Framework that works well on MVC or HMVC mode, its made with HTML5 Boilerplate and Foundation CSS Framework. NSY also provides Font-Awesome and several optimizations for Datatables plugin.

Site example :
[https://nsy.kazuyamarino.com/](https://nsy.kazuyamarino.com/)


## NSY folder structure

```bash
.
├── composer.json
├── Docs
│   ├── apache config
│   │   ├── hide_public
│   │   ├── Readme.txt
│   │   └── sites_public
│   ├── nginx config
│   │   ├── nginx.conf
│   │   └── sites-available
│   │       └── default
│   └── USERGUIDE.md
├── LICENSE.txt
├── Public
│   ├── 403.html
│   ├── 404.html
│   ├── Data
│   │   └── data.json
│   ├── index.php
│   ├── robots.txt
│   └── Template
│       ├── css
│       │   ├── main.css
│       │   └── vendor
│       │       ├── dataTables.foundation.min.css
│       │       ├── foundation.min.css
│       │       ├── img
│       │       │   ├── sort_asc_disabled.png
│       │       │   ├── sort_asc.png
│       │       │   ├── sort_both.png
│       │       │   ├── sort_desc_disabled.png
│       │       │   └── sort_desc.png
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
    │   ├── NSY_Model.php
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
* Simply rename the folder that has been downloaded to `nsy-foundation` & copy it to your `html` or `htdocs` folder or anythings folder.
* For apache, please go to the `docs/apache config` folder and read the Readme.txt.
* For nginx, please go to the `docs/nginx config` folder.
* And save the date..

## CRUD Example?
Here it is [NSY CRUD Example](https://github.com/kazuyamarino/crud)

## NSY contain package :
* [Datatables jQuery Javascript Library](https://www.datatables.net/) with Responsive Plugin
* [Foundation Zurb Framework](https://foundation.zurb.com/)
* [JQuery](https://jquery.com/)
* [Modernizr](https://modernizr.com/)
* [WhatInputJs](https://github.com/ten1seven/what-input)
* [Font Awesome CDN](https://fontawesome.com/)


## NSY Feature :
* MVC or HMVC
* NSY Assets Manager
* PSR-4 Autoloading

For more information, see [USERGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/Docs/USERGUIDE.md).

## Browser support test

NSY is made with Foundation CSS Framework. This information is based on [Foundation Compatibility](https://foundation.zurb.com/sites/docs/compatibility.html).

>Foundation is tested across many browsers and devices, and works back as far as IE9 and Android 2.

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

* **The Grid:** Foundation's grid uses `box-sizing: border-box` to apply gutters to columns, but this property isn't supported in IE8.
* **Desktop Styles:** Because the framework is written mobile-first, browsers that don't support media queries will display the mobile styles of the site.
* **JavaScript:** Our plugins use a number of handy ECMAScript 5 features that aren't supported in IE8.

*This doesn't mean that NSY cannot be used in older browsers,
just that we'll ensure compatibility with the ones mentioned above.*
*NSY Browser support information based on [Foundation Zurb Compatibility](https://foundation.zurb.com/sites/docs/compatibility.html).*


## User Guide.
See [USERGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/Docs/USERGUIDE.md).


## License

The code is available under the [MIT license](LICENSE.txt)..
