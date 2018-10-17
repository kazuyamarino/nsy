# NSY
NSY is a simple PHP Framework that works well on MVC or HMVC mode, its contain the HTML5 Boilerplate and Foundation CSS Framework in one package at a time. As well as include some support for Font-Awesome. NSY also provides several optimizations for Datatables plugin.

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
│       │       ├── foundation.css
│       │       ├── foundation.min.css
│       │       └── responsive-tables.min.css
│       ├── footer.php
│       ├── header.php
│       ├── img
│       │   ├── favicon.png
│       │   ├── logo.png
│       │   └── magic.gif
│       └── js
│           ├── datatables
│           │   └── init.js
│           ├── main.js
│           └── vendor
│               ├── dataTables.foundation.min.js
│               ├── foundation.js
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
- Datatables jQuery Javascript Library with Responsive Plugin
- Foundation Zurb Framework
- JQuery
- Modernizr
- WhatInputJs

## NSY Feature :
- MVC or HMVC
- NSY Assets Manager
- PSR4 Autoloading

### Introducting to NSY Assets Manager
The easiest & best assets manager in history
Made with love by Vikry Yuansah

How to use it? Simply follow this.
- First, you need to go to `System/Libraries/AssetManager/`, there are 2 files, that is `Assets.php` & `NSY_AssetManager.php`.
- `NSY_AssetManager.php` is the core, & `Assets.php` is the controller which regulates assets, if you want to manage the assets, please go to `Assets.php`.

Create <meta> tag :
```
$this->meta('name', 'content');
```

Create <link> tag :
```
$this->link('filename/url_filename', 'attribute_rel', 'attribute_type');
```

Create <script> tag :
```
$this->script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');
```

You can write any html tags with custom method :
```
$this->custom('anythings');
```

## User Guide.
- On Progress..
