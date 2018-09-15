# FanPress CM News System version 4

![FanPress CM 4 Logo](/core/theme/logo.svg)

The FanPress CM News System version 4 is a lightweight but powerful content management system for small and mid-size websites. Based on the stable FanPress CM 3.x, this version includes major improvements in functionality, code quality, speed and more.

## Base Requirements

* PHP 7.0 or better
* MySQL/ Maria DB 7 or Postgres 9.4
* complete requirements check will be performed during setup.

The inclusion into a website depends an how you use the system. An assistant for integration is provided. If you do it manually, here are further information:

## php include

When using php include, fist include the api file and create a new API object.

```php
<?php include_once 'fanpress/fpcmapi.php'; ?>
<?php $api = new fpcmAPI(); ?>
```

The following functions are available:

* `$api->showArticles(array $params)`: Display active articles, a single article or article archive in front end
    * `$params` is an array to further customize the out of the function
        * _count_: number of articles per page
        * _category_: select articles of a a single category, default is 0
        * _isUtf8_: out will be displayed utf-8 encoded or iso-8895-1, default is true
        * _template_: change used template to display articles in frontend
* `$api->showLatestNews(array $params)`: Display recent recent news list
    * `$params` is an array to further customize the out of the function
        * _count_: number of articles per page
        * _category_: select articles of a a single category, default is 0
        * _isUtf8_: out will be displayed utf-8 encoded or iso-8895-1, default is true
* `$api->showPageNumber($divider, $isUtf8)`: displays current page number.
    * _divider_: parameter for page descriptions like "Page XYZ"
    * _isUtf8_: out will be displayed utf-8 encoded or iso-8895-1, default is true
* `$api->showTitle($divider, $isUtf8)`: displays article title in HTML <title>
    * _divider_: parameter for a separator of your text in <title>
    * _isUtf8_: out will be displayed utf-8 encoded or iso-8895-1, default is true
* `$api->legacyRedirect($articlesPerPage)`: perform redirect of article urls  in FanPress CM 1/2 style.

## iframes

In case you are using iframes you have to call the controllers manually.

* **your-domain.xyz/fanpress/index.php?module=fpcm/list**
    * show all active articles
* **your-domain.xyz/fanpress/index.php?module=fpcm/archive**
    * show article archive
* **your-domain.xyz/fanpress/index.php?module=fpcm/article&&id=A_DIGIT**
    * show a single article with given id including comments
* **your-domain.xyz/fanpress/index.php?module=fpcm/latest**
    * show latest news

## RSS Feed

If you want to provide the RSS feed for your visitors, just create a link to **your-domain.xyz/fanpress/index.php?module=fpcm/feed**. The link does not depend on the way you're using FanPress CM.

## Licence

FanPress CM 4 is provided under the GPL v3 and is free to use. Support is provided via GitHub.

## Contribution

Any kind of contribution to general development (code, feature requests/ ideas), translation into various languages, testing and so on is greatly appreciated. Feel free to leave a message.
