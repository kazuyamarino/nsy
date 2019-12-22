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
│   │   ├── .htaccess
│   │   ├── mimes.php
│   │   └── site.php
│   ├── controllers
│   │   └── Welcome.php
│   ├── core
│   │   ├── .htaccess
│   │   ├── NSY_AssetManager.php
│   │   ├── NSY_Controller.php
│   │   ├── NSY_CSRF.php
│   │   ├── NSY_DB.php
│   │   ├── NSY_Desk.php
│   │   ├── NSY_Model.php
│   │   ├── NSY_Router.php
│   │   ├── NSY_System.php
│   │   ├── NSY_XSS_Filter.php
│   │   └── Razr
│   │       ├── Directive
│   │       │   ├── BlockDirective.php
│   │       │   ├── ControlDirective.php
│   │       │   ├── DirectiveInterface.php
│   │       │   ├── Directive.php
│   │       │   ├── ExtendDirective.php
│   │       │   ├── FunctionDirective.php
│   │       │   ├── .htaccess
│   │       │   ├── IncludeDirective.php
│   │       │   ├── RawDirective.php
│   │       │   └── SetDirective.php
│   │       ├── Engine.php
│   │       ├── Exception
│   │       │   ├── ExceptionInterface.php
│   │       │   ├── .htaccess
│   │       │   ├── InvalidArgumentException.php
│   │       │   ├── RuntimeException.php
│   │       │   └── SyntaxErrorException.php
│   │       ├── Extension
│   │       │   ├── CoreExtension.php
│   │       │   ├── ExtensionInterface.php
│   │       │   └── .htaccess
│   │       ├── .htaccess
│   │       ├── Lexer.php
│   │       ├── Loader
│   │       │   ├── ChainLoader.php
│   │       │   ├── FilesystemLoader.php
│   │       │   ├── .htaccess
│   │       │   ├── LoaderInterface.php
│   │       │   └── StringLoader.php
│   │       ├── Parser.php
│   │       ├── Storage
│   │       │   ├── FileStorage.php
│   │       │   ├── .htaccess
│   │       │   ├── Storage.php
│   │       │   └── StringStorage.php
│   │       ├── Token.php
│   │       └── TokenStream.php
│   ├── helpers
│   │   ├── CI_Helpers.php
│   │   ├── .htaccess
│   │   └── NSY_Helpers.php
│   ├── libraries
│   │   ├── Assets.php
│   │   ├── Cookie.php
│   │   └── .htaccess
│   ├── migrations
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
│   │   ├── .htaccess
│   │   ├── Migration.php
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
* Primary and Secondary Database Connection, [See Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE.md#primary--secondary-database-connections)
* Aurora File Export, [Aurora Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md#routes)
* NSY Routing System, [Routes Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md#routes)
* [MVC or HMVC](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md#mvc--hmvc)
* NSY Assets Manager, [See Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md#introducting-to-nsy-assets-manager)
* [PSR-4 Autoloading](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md#psr-4-autoloading)
* Composer, [Composer on NSY](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md#composer-on-nsy-framework)
* .env (Environment Variables) Config, [Framework Config](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md#framework-configuration)
* Anti XSS & CSRF Token, [Security helper](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE.md#security-helper)
* NSY CLI (Command Line), [See Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md#nsy-cli-command-line)
* Carbon DateTime, [Carbon Documentation](https://carbon.nesbot.com/docs/)
* FTP Client, See [php-ftp-client Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE_2.md#nsy-ftp-client-library) **NEW!**
* Cookie Library, [Cookie Library Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE_2.md#cookie-library) **NEW!**
* Add several Codeigniter Helpers **NEW!**
* Razr Template Engine, [Razr Engine Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE_2.md#razr---the-powerful-php-template-engine) **NEW!**
* Migrations, [See Documentation](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE_2.md#nsy-migrations) **NEW!**


## User Guide.
See [USERGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/docs/USERGUIDE.md).


## System Guide.
Part 1 of [SYSGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE.md).<br/>
Part 2 of [SYSGUIDE.md](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE_2.md).


## License
The code is available under the [MIT license](LICENSE.txt)
