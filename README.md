# FanPress CM News System version 5

The FanPress CM News System version 5 is a lightweight but powerful content management system for small and mid-size websites, especially blogs and such looking for a post management system in combination with static HTML.

## Demo system

A small live demo system is available at https://area51.nobody-knows.org/fanpress4/.

* Username: __preview__
* Password: __Preview2018___ 

## Base Requirements

* __FanPress CM 5.0:__ PHP 7.2 or better
* MySQL/ Maria DB 7 or Postgres 9.4
* complete requirements check will be performed during setup.

The inclusion into a website depends on how you use the system. An assistant for integration is provided. If you do it manually, here are further information:

## PHP include

When using PHP include, first include the API file and create a new API object.

```php
<?php include_once 'fanpress/fpcmapi.php'; ?>
<?php $api = new fpcmAPI(); ?>
```

The following functions are available:

* `$api->showArticles(array $params)`: Display active articles, a single article or article archive in front end
    * `$params` is an array to further customize the out of the function
        * _count_: number of articles per page
        * _category_: select articles of a single category, default is 0
        * _isUtf8_: out will be displayed utf-8 encoded or iso-8895-1, default is true ( :x: deprecated as of version 5.0, to be removed )
        * _template_: change used template to display articles in frontend
        * _search_: can be used to create search function for articles, only in content
* `$api->showLatestNews(array $params)`: Display recent news list
    * `$params` is an array to further customize the out of the function
        * _count_: number of articles per page
        * _category_: select articles of a single category, default is 0
        * _isUtf8_: out will be displayed utf-8 encoded or iso-8895-1, default is true ( :x: deprecated as of version 5.0, to be removed )
* `$api->showPageNumber($divider, $isUtf8)`: displays current page number.
    * _divider_: parameter for page descriptions like "Page XYZ"
    * _isUtf8_: out will be displayed utf-8 encoded or iso-8895-1, default is true ( :x: deprecated as of version 5.0, to be removed )
* `$api->showTitle($divider, $isUtf8)`: displays article title in HTML <title>
    * _divider_: parameter for a separator of your text in <title>
    * _isUtf8_: out will be displayed utf-8 encoded or iso-8895-1, default is true ( :x: deprecated as of version 5.0, to be removed )
   
A full list of additional functions can be found in our [class documentation](https://sea75300.github.io/fanpresscm/classes/fpcmAPI.html).

## iframes

In case you are using iframes you have to call the controllers manually.

* **your-domain.xyz/fanpress/index.php?module=fpcm/list**
    * show all active articles
* **your-domain.xyz/fanpress/index.php?module=fpcm/archive**
    * show article archive
* **your-domain.xyz/fanpress/index.php?module=fpcm/article&&id=A_DIGIT**
    * show a single article with given id including comments
* **your-domain.xyz/fanpress/index.php?module=fpcm/latest**
    * show the latest news

## RSS Feed

If you want to provide the RSS feed for your visitors, just create a link to **your-domain.xyz/fanpress/index.php?module=fpcm/feed**. The link does not depend on the way you're using FanPress CM.

## Licence

FanPress CM 5 is provided under the GPL v3 and is free to use. Support is provided via GitHub.

## Contribution

Any kind of contribution to general development (code, feature requests/ ideas), translation into various languages, testing and so on is greatly appreciated. Feel free to leave a message.
