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
│   ├── DB
│   │   └── saleshub_development.sql
│   ├── nginx config
│   │   ├── nginx.conf
│   │   └── sites-available
│   │       └── default
│   └── USERGUIDE.md
├── dump-saleshub_development-201901221346.sql
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
│       │       ├── img
│       │       │   ├── sort_asc_disabled.png
│       │       │   ├── sort_asc.png
│       │       │   ├── sort_both.png
│       │       │   ├── sort_desc_disabled.png
│       │       │   └── sort_desc.png
│       │       ├── responsive-tables.min.css
│       │       └── select2.css
│       ├── img
│       │   ├── avione.png
│       │   ├── favicon.png
│       │   ├── item
│       │   │   ├── 139990.jpg
│       │   │   ├── 179563.jpg
│       │   │   ├── 189092.jpg
│       │   │   ├── 402063.jpg
│       │   │   ├── 479828.jpg
│       │   │   ├── 541490.jpg
│       │   │   ├── 550392.jpg
│       │   │   ├── 762645.jpg
│       │   │   ├── 828306.jpg
│       │   │   ├── 850449.jpg
│       │   │   ├── 894016.jpg
│       │   │   ├── thumb-139990.jpg
│       │   │   ├── thumb-179563.jpg
│       │   │   ├── thumb-189092.jpg
│       │   │   ├── thumb-402063.jpg
│       │   │   ├── thumb-479828.jpg
│       │   │   ├── thumb-541490.jpg
│       │   │   ├── thumb-550392.jpg
│       │   │   ├── thumb-762645.jpg
│       │   │   ├── thumb-828306.jpg
│       │   │   ├── thumb-850449.jpg
│       │   │   └── thumb-894016.jpg
│       │   ├── logo.png
│       │   ├── principal
│       │   │   ├── EGLIN.png
│       │   │   ├── GSK.png
│       │   │   ├── KAO.png
│       │   │   ├── LOLANE.png
│       │   │   ├── RDL.png
│       │   │   └── UNZA.png
│       │   ├── produk2.jpg
│       │   ├── promo1.jpeg
│       │   ├── promo2.jpeg
│       │   ├── promo3.jpeg
│       │   ├── promo4.jpeg
│       │   └── targetprima.jpg
│       ├── js
│       │   ├── datatables
│       │   │   ├── init-admin.js
│       │   │   ├── init-chart-item.js
│       │   │   ├── init-customer.js
│       │   │   ├── init-discount-qty.js
│       │   │   ├── init-discount-value.js
│       │   │   ├── init-item-group.js
│       │   │   ├── init-item.js
│       │   │   ├── init-kategori-principal.js
│       │   │   ├── init-principal.js
│       │   │   ├── init-rayon.js
│       │   │   └── init-review-chart.js
│       │   ├── main.js
│       │   └── vendor
│       │       ├── dataTables.foundation.min.js
│       │       ├── foundation.min.js
│       │       ├── foundation.offcanvas.js
│       │       ├── jquery.dataTables.min.js
│       │       ├── jquery.formatNumber-0.1.1.min.js
│       │       ├── jquery.min.js
│       │       ├── modernizr.min.js
│       │       ├── promise-polyfill.js
│       │       ├── responsive-tables.min.js
│       │       ├── select2.min.js
│       │       ├── sweetalert2.all.min.js
│       │       └── what-input.min.js
│       └── trumbowyg
│           ├── trumbowyg.min.css
│           ├── trumbowyg.min.js
│           └── ui
│               └── icons.svg
├── README.md
├── src
│   ├── foundation.css
│   ├── foundation.js
│   ├── foundation.min.css
│   └── foundation.min.js
└── System
    ├── Controllers
    │   └── LICENSE.txt
    ├── Core
    │   ├── NSY_Config.php
    │   ├── NSY_Controller.php
    │   ├── NSY_DB.php
    │   ├── NSY_Router.php
    │   └── Routing.php
    ├── Libraries
    │   ├── AlertManager
    │   │   └── Alerts.php
    │   ├── AssetManager
    │   │   ├── Assets.php
    │   │   └── NSY_AssetManager.php
    │   ├── Gumlet
    │   │   ├── ImageResizeException.php
    │   │   └── ImageResize.php
    │   └── PHPMailer
    │       ├── Exception.php
    │       ├── OAuth.php
    │       ├── PHPMailer.php
    │       ├── POP3.php
    │       └── SMTP.php
    ├── Models
    │   └── LICENSE.txt
    ├── Modules
    │   ├── Admin
    │   │   ├── Controllers
    │   │   │   ├── c_admin.php
    │   │   │   └── c_customer.php
    │   │   ├── Models
    │   │   │   ├── m_admin.php
    │   │   │   └── m_customer.php
    │   │   └── Views
    │   │       ├── add_master_discount_qty.php
    │   │       ├── add_master_discount_value.php
    │   │       ├── add_master_item_group.php
    │   │       ├── add_master_item.php
    │   │       ├── add_master_principal.php
    │   │       ├── add_master_rayon.php
    │   │       ├── add_user_admin.php
    │   │       ├── admin_footer.php
    │   │       ├── admin_header.php
    │   │       ├── admin_page.php
    │   │       ├── customer_footer.php
    │   │       ├── customer_header.php
    │   │       ├── customer_page.php
    │   │       ├── edit_master_discount_qty.php
    │   │       ├── edit_master_discount_value.php
    │   │       ├── edit_master_item_group.php
    │   │       ├── edit_master_item.php
    │   │       ├── edit_master_principal.php
    │   │       ├── edit_master_rayon.php
    │   │       ├── edit_user_admin.php
    │   │       ├── edit_user_customer.php
    │   │       ├── edit_user_principal.php
    │   │       ├── master_discount_footer.php
    │   │       ├── master_discount.php
    │   │       ├── master_diskon.php
    │   │       ├── master_item_footer.php
    │   │       ├── master_item.php
    │   │       ├── master_principal_footer.php
    │   │       ├── master_principal.php
    │   │       ├── master_rayon_footer.php
    │   │       ├── master_rayon.php
    │   │       ├── user_admin_footer.php
    │   │       ├── user_admin.php
    │   │       ├── user_customer_footer.php
    │   │       ├── user_customer.php
    │   │       ├── user_principal_footer.php
    │   │       └── user_principal.php
    │   ├── Homepage
    │   │   ├── Controllers
    │   │   │   ├── c_homepage.php
    │   │   │   └── c_product.php
    │   │   ├── Models
    │   │   │   ├── m_home_page.php
    │   │   │   ├── m_homepage.php
    │   │   │   └── m_product.php
    │   │   └── Views
    │   │       ├── homepage_footer.php
    │   │       ├── homepage_header.php
    │   │       ├── homepage_index.php
    │   │       ├── list-order-item.php
    │   │       ├── product_detail.php
    │   │       ├── product_eglin_homepage.php
    │   │       ├── product_homepage.php
    │   │       ├── product_kao_homepage.php
    │   │       ├── product_lolane_homepage.php
    │   │       └── product_unza_homepage.php
    │   ├── Login
    │   │   ├── Controllers
    │   │   │   └── c_login_auth.php
    │   │   ├── Models
    │   │   │   └── m_login_auth.php
    │   │   └── Views
    │   │       ├── forgotpass_page.php
    │   │       ├── login_footer.php
    │   │       ├── login_header.php
    │   │       ├── login_page.php
    │   │       └── resetpass_page.php
    │   └── Register
    │       ├── Controllers
    │       │   └── c_register.php
    │       ├── Models
    │       │   └── m_register.php
    │       └── Views
    │           ├── register_customer.php
    │           ├── register_footer.php
    │           ├── register_header.php
    │           └── register_principal.php
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
        └── LICENSE.txt
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
