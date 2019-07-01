
The Parcel_pool package consists of the web serivce, admin panel and database.


WEB SERVICE:
The web service uses the php as the sever side language and mysql as the database.
To set up the server, you need to run The Apache HTTP Server.

Database server
    Server type: MySQL
    Server version: 5.7.25
    Server charset: UTF-8 Unicode (utf8)
Web server
    Apache/2.2.34 (Unix) mod_wsgi/3.5 Python/2.7.13 PHP/7.3.1 mod_ssl/2.2.34 OpenSSL/1.0.2o DAV/2 mod_fastcgi/mod_fastcgi-SNAP-0910052141 mod_perl/2.0.9 Perl/v5.24.0
    Database client version: libmysql - mysqlnd 5.0.12-dev - 20150407 - $Id: 401a40ebd5e281cf22215acdc170723a1519aaa9 $
    PHP version: 7.3.1


UIkit is used as the frontend framework for the application which uses the CDN repositories to deliver the style and functionality.
UIkit 3.1.5 | http://www.getuikit.com | (c) 2014 - 2018 YOOtheme | MIT License



ADMIN PANEL:
Symfony is used as the backend framework for the admin panel.
To set up the symfony Upload the code to the production server;
Install the vendor dependencies (via Composer);
Run the database migration.

A list of all symfony dependencies version is on the composer.json file.
PHP version: 7.1.3


Bootstrap is used as the frontend framework which uses the CDN repositories to deliver the style and functionality.
  * Bootstrap v4.0.0 (https://getbootstrap.com)
  * Copyright 2011-2018 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
  * Licensed under MIT

  Username: adminadmin
  Password: Admin_Admin