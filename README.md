# NSY
NSY is a simple PHP Framework that works well on MVC or HMVC mode.

[![Build Status](https://travis-ci.org/kazuyamarino/nsy.svg?branch=master)](https://travis-ci.org/kazuyamarino/nsy)

Site example :
[https://nsy.kazuyamarino.com/](https://nsy.kazuyamarino.com/)


## NSY folder structure

```bash
.
├── composer.json
├── composer.lock
├── docs
│   ├── apache
│   │   ├── for_public
│   │   │   └── .htaccess
│   │   ├── for_root
│   │   │   └── .htaccess
│   │   └── Readme.txt
│   ├── env
│   │   └── .env.example
│   ├── nginx
│   │   ├── nginx.conf
│   │   └── sites-available
│   │       └── default
│   ├── SYSGUIDE.md
│   └── USERGUIDE.md
├── dump
│   └── DUMP.md
├── .editorconfig
├── .env
├── .gitattributes
├── .gitignore
├── INSTALL.CLI.sh
├── LICENSE.txt
├── phpunit.xml
├── public
│   ├── 403.html
│   ├── 404.html
│   ├── browserconfig.xml
│   ├── css
│   │   └── main.css
│   ├── humans.txt
│   ├── img
│   │   ├── favicon.png
│   │   └── logo.png
│   ├── index.php
│   ├── js
│   │   ├── config
│   │   │   └── system.js
│   │   └── main.js
│   └── robots.txt
├── README.md
├── system
│   ├── config
│   │   ├── app.php
│   │   ├── database.php
│   │   └── site.php
│   ├── controllers
│   │   └── Welcome.php
│   ├── core
│   │   ├── .htaccess
│   │   ├── NSY_AssetManager.php
│   │   ├── NSY_Controller.php
│   │   ├── NSY_CSRF.php
│   │   ├── NSY_DB.php
│   │   ├── NSY_Model.php
│   │   ├── NSY_Router.php
│   │   ├── NSY_System.php
│   │   └── NSY_XSS_Filter.php
│   ├── helpers
│   │   └── NSY_Helper.php
│   ├── libraries
│   │   ├── Assets.php
│   │   └── .htaccess
│   ├── models
│   │   └── Model_Welcome.php
│   ├── modules
│   │   └── homepage
│   │       ├── controllers
│   │       │   └── Hello.php
│   │       ├── models
│   │       │   └── Model_Hello.php
│   │       └── views
│   │           └── index.php
│   ├── routes
│   │   ├── Api.php
│   │   └── Web.php
│   ├── templates
│   │   ├── footer.php
│   │   └── header.php
│   └── views
│       └── index.php
└── .travis.yml
```


## How to dating with NSY?
* Simply rename the folder that has been downloaded to `nsy` & copy it to your `html` or `htdocs` folder or anythings folder.
* For apache, please go to the `docs/apache` folder and read the Readme.txt.
* For nginx, please go to the `docs/nginx` folder.
* Go to the `docs/env` folder and copy the `.env.example` to root folder, and rename it to `.env`.
* And save the date..


## CRUD Example?
Here it is [Vylma CRUD Example](https://github.com/kazuyamarino/vylma-crud)
And [Shyffon CRUD Example](https://github.com/kazuyamarino/shyffon-crud)


## NSY Feature :
* MVC or HMVC
* NSY Assets Manager
* PSR-4 Autoloading
* Composer
* .env (Environment Variables) Config
* Anti XSS & CSRF Token
* NSY CLI (Command Line)
* FTP Client, See [php-ftp-client](https://github.com/Nicolab/php-ftp-client)
* Carbon DateTime, [Carbon](https://github.com/briannesbitt/Carbon)


## User Guide.
See [USERGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md).

## System Guide.
See [SYSGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE.md).


## License
The code is available under the [MIT license](LICENSE.txt)
