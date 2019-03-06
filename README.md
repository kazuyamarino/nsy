# NSY
NSY is a simple PHP Framework that works well on MVC or HMVC mode, its a core of NSY Framework, with no more Stylesheet and Javascript.

Site example :
[https://nsy.kazuyamarino.com/](https://nsy.kazuyamarino.com/)


## NSY folder structure

```bash
.
├── composer.json
├── docs
│   ├── apache
│   │   ├── for_public
│   │   ├── for_root
│   │   └── Readme.txt
│   ├── nginx
│   │   ├── nginx.conf
│   │   └── sites-available
│   │       └── default
│   └── USERGUIDE.md
├── LICENSE.txt
├── Public
│   ├── 403.html
│   ├── 404.html
│   ├── index.php
│   ├── robots.txt
│   └── Template
│       ├── css
│       │   └── main.css
│       ├── footer.php
│       ├── header.php
│       ├── img
│       │   ├── favicon.png
│       │   └── logo.png
│       └── js
│           └── main.js
├── README.md
└── System
    ├── Controllers
    │   └── Welcome.php
    ├── Core
    │   ├── NSY_AliasClass.php
    │   ├── NSY_AssetManager.php
    │   ├── NSY_Config.php
    │   ├── NSY_Controller.php
    │   ├── NSY_DB.php
    │   ├── NSY_Model.php
    │   └── NSY_Router.php
    ├── Libraries
    │   └── Assets.php
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
    ├── Routes
    │   ├── Api.php
    │   └── Web.php
    ├── Vendor
    │   ├── autoload.php
    │   ├── bin
    │   └── composer
    │       ├── autoload_classmap.php
    │       ├── autoload_files.php
    │       ├── autoload_namespaces.php
    │       ├── autoload_psr4.php
    │       ├── autoload_real.php
    │       ├── autoload_static.php
    │       ├── ClassLoader.php
    │       ├── installed.json
    │       └── LICENSE
    └── Views
        └── index.php
```


## How to dating with NSY?
* Simply rename the folder that has been downloaded to `nsy` & copy it to your `html` or `htdocs` folder or anythings folder.
* For apache, please go to the `docs/apache` folder and read the Readme.txt.
* For nginx, please go to the `docs/nginx` folder.
* And save the date..


## CRUD Example?
Here it is [NSY CRUD Example](https://github.com/kazuyamarino/crud)


## NSY Feature :
* MVC or HMVC
* NSY Assets Manager
* PSR-4 Autoloading

For more information, see [USERGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/Docs/USERGUIDE.md).


## User Guide.
See [USERGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/Docs/USERGUIDE.md).


## License
The code is available under the [MIT license](LICENSE.txt)..
