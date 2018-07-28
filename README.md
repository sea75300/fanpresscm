# FanPress CM News System version 3

__Please note, this version is still in beta developement, so it is not ready for use in live envoirments!__

The FanPress CM News System version 4 is a lightwight but powerfull content management system for small and mide size websites. Based on the stable version 3, this version includes major improvements in functionality, code quality, speed and more.

The inclusion into a website depends an how you use the system. An assistent for integration is provided. If you do it manually, here are further information:

## php include

When using php include, fist include the api file and create a new API object.

```php
<?php include_once 'fanpress/fpcmapi.php'; ?>
<?php $api = new fpcmAPI(); ?>
```

Now you can use the API functions:

```php
$api->showArticles() to display active articles, a single article or the article archive in front end. (fulfils task of shownews.php from FanPress CM 1.x and 2.x)
$api->showLatestNews() to show recent news list.
$api->showPageNumber() displays current page number, accepts a parameter for page descriptions like "Page XYZ".
$api->showTitle() displayse the article title in HTML <title> , accepts a parameter for a seperator of your text in <title>.
$api->legacyRedirect() redirect visitors which enters your site using an FanPress CM 1/2 article/ page url style.
```

## iframes

In case your're using iframes you have to call the controllers manually.

* your-domain.xyz/fanpress/index.php?module=fpcm/list
    * show all active articles (fulfils task of shownews.php from FanPress CM 1.x and 2.x)
* your-domain.xyz/fanpress/index.php?module=fpcm/archive
    * show article archive (fulfils task of shownews.php from FanPress CM 1.x and 2.x)
* your-domain.xyz/fanpress/index.php?module=fpcm/article&&id=A_DIGIT
    * show a single article with given id including comments
* your-domain.xyz/fanpress/index.php?module=fpcm/latest
    * show latest news

## RSS Feed

If you want to provide the RSS feed for your visitors, just create a link to your-domain.xyz/fanpress/index.php?module=fpcm/feed. The link does not depend on the way you're using FanPress CM.
