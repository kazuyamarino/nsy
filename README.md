[![Build Status](https://travis-ci.org/kazuyamarino/nsy.svg?branch=master)](https://travis-ci.org/kazuyamarino/nsy)

# NSY
NSY is a simple PHP Framework that works well on MVC or HMVC mode.

Site example :
[https://nsy.kazuyamarino.com/](https://nsy.kazuyamarino.com/)


## NSY folder structure

```bash
├── composer.json
├── composer.lock
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
├── dump
│   └── DUMP.md
├── INSTALL.CLI.sh
├── LICENSE.txt
├── phpunit.xml
├── public
│   ├── 403.html
│   ├── 404.html
│   ├── css
│   │   └── main.css
│   ├── footer.php
│   ├── header.php
│   ├── img
│   │   ├── favicon.png
│   │   └── logo.png
│   ├── index.php
│   ├── js
│   │   ├── main.js
│   │   └── system.js
│   └── robots.txt
├── README.md
└── system
    ├── config
    │   ├── app.php
    │   ├── database.php
    │   └── site.php
    ├── controllers
    │   └── Welcome.php
    ├── core
    │   ├── NSY_AssetManager.php
    │   ├── NSY_Controller.php
    │   ├── NSY_CSRF.php
    │   ├── NSY_DB.php
    │   ├── NSY_Model.php
    │   ├── NSY_Router.php
    │   └── NSY_System.php
    ├── helpers
    │   └── NSY_Helper.php
    ├── libraries
    │   └── Assets.php
    ├── models
    │   └── Model_Welcome.php
    ├── modules
    │   └── homepage
    │       ├── controllers
    │       │   └── Hello.php
    │       ├── models
    │       │   └── Model_Hello.php
    │       └── views
    │           └── index.php
    ├── routes
    │   ├── Api.php
    │   └── Web.php
    └── views
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
* Composer
* .env (Environment Variables) Config


## User Guide.
See [USERGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md).


## License
The code is available under the [MIT license](LICENSE.txt)..
