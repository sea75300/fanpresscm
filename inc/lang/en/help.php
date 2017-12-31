<?xml version="1.0" encoding="UTF-8"?>
<!--
Help language file
@author Stefan Seehafer <sea75300@yahoo.de>
@copyright (c) 2011-2017, Stefan Seehafer
@license http://www.gnu.org/licenses/gpl.txt GPLv3
*/
-->
<chapters>
    <chapter>
        <headline>
            HL_DASHBOARD
        </headline>
        <text>
        <![CDATA[
            <p>The <b>dashboard</b> offers you a first overview about you systems status, updates, recently created articles and more.
            To create an own container, create a file into in "fanpress/inc/dashboard" or using the module event.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            ARTICLES_EDITOR
        </headline>
        <text>
        <![CDATA[
            <p>The article editor allows you to create and format articles, as well as sort it into categories, postpone or pin it.
            It's possible to extended the editor using various module events.</p>
            <p>The editor has two different views, which can be set in system options</p>
            <ul>
                <li><b>WYSIWYG:</b><br>
                    This view uses TinyMCE 4 to show you an easy editor you can use without any knowledge of HTML.
                </li>
                <li><b>HTML view:</b><br>
                    This is a simple HTML editor with syntax highlighting and a couple of defined HTML adding buttons. Use this view
                    to edit the HTML code of your article directly.
                </li>
            </ul>

            <p>The <span class="fpcm-ui-button">Short link</span>-button at the top of the editor allows you to create a shortened
            link using <a href=http://is.gd>is.gd</a> service. The service can be changed by modules.</p>

            <p>If possible, you can connect FanPress CM to twitter and let the system create automatically create tweets during
            when publishing or updating an article. See the help chapter for further information..</p>

            <p><span class="fpcm-ui-button">Extended</span> menu:</p>
            <ul>
                <li><em>Article image:</em> You can set an article image to extend articles with an optical introduction, decoration
                    or so on.</li>
                <li><em>List of sources:</em> The content of this field will show up in the "{{sources}}" template tag. You may enter links for credits for informations, images, videos and so on
                or links for further information on a certain topic. Links will be converted to HTML link tags if possible</li>
                <li><em>Tweet generation enabled:</em> Use this option to enable/disable the generation of a tweet and override the
                    config set in system options.</li>                
                <li><em>Tweet content:</em> This field allows you to override the content of posts on Twitter based on the default
                    Tweet template. The content of this input field won't be saved anywhere.</li>
                <li><em>Postpone article:</em> Postponed articles are not published immediately after saving it. You can set a date and
                    time when an article is published automatically. The time must be within a two month range.</li>
                <li><em>Save article as draft:</em> Articles saved as draft are available for users, which signed in before only.
                    Users can edit drafts and publish them later.</li>
                <li><em>Pin article:</em> Pinned articles will show up at the beginning of the article list in frontend, even if you
                    have published new articles.</li>
                <li><em>Comments enabled:</em> With this option you can enable or disable the comment system for a single article.
                    If the option is disabled, a visitor is unable to create a new comment.</li>
                <li><em>Archive article:</em> Existing articles can be moved to the article archive if you enable this option..</li>
                <li><em>Change author:</em> Users with permissions can change the autor of a new or existing article.</li>
            </ul>

            <p>To hide content like spoilers and so an, you can use the FanPress CM tag <strong>&lt;readmore&gt;</strong>. Just insert
            the text between <strong>&lt;readmore&gt;YOUR TEXT&lt;/readmore&gt;</strong>.</p>

            <p>At the top of the editor you can see up to three tabs. The <i>Article editor</i> tab is always visible and includes
            the editor as is. Additional tabs can be <i>Comments</i> and <i>Revisions</i></p>

            <p>The <i>Comments</i> tab allows you to manage comments created on the currently opened article. You can edit, delete,
            approve comments or set them as private. If you don't see this tab, you might have no permissions.</p>

            <p>FanPress CM has a simple revision system, so you wont loose changes you made to an article. Revisions can be enabled
            in system options and managed in the <i>Revisions</i> tab.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_ARTICLE_EDIT
        </headline>
        <text>
        <![CDATA[
            <p>This area allows you to manager you existing articles. You can edit or delete them as well as set different article
            options.</p>
            <ul>
                <li><em>Edit selected:</em> Start mass editing for selected articles. The available options are similar to the
                    ones in the article editor.</li>                
                <li><em>Create new tweets:</em> Create new posts at Twitter for selected articles in case you enabled the Twitter
                    connection in system options.</li>
                <li><em>Delete:</em> Throw selected articles into trash if enabled. If trash is not enabled, articles will be deleted
                    immediately.</li>
                <li><em>Restore articles:</em> Restore selected articles from trash if enabled.</li>
                <li><em>Clear trash:</em> Remove all articles in trash.</li>
            </ul>
            <p>The <span class="fpcm-ui-button">Search & filter</span> button allows you to search or filter you articles by various conditions. The main menu
            let you made an additional pre selection for f. g. for active articles.</p>
            <p></p>
            <p>Die Listen des Bereichs umfassen verschiedene Datensätze:</p>
            <ul>
                <li><em>All Articles:</em> The first view lists all articles created within the system, except for deleted articles.</li>
                <li><em>Active Articles:</em> The "active articles" view contains all articles to be displayed on your website as well
                as articles saved as draft.</li>
                <li><em>Archived Articles:</em> This list contains all archived articles.</li>
                <li><em>Trash:</em> If enabled in system settings, you'll finde this list in the menu. Here you can manage deleted
                articles or restore them.</li>
            </ul>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_COMMENTS_MNG
        </headline>
        <text>
        <![CDATA[
            <p>The <i>Comments</i> area is an extension of the editor <i>Comments</i>-tab. This area lists you all existing articles
            regardless of their related article. Your can manage all articles as you can do in the editor tab, which means edit, delete,
            approve them or set the the spam/ private status.</p>            
            <ul>
                <li><em>Edit selected:</em> Start mass editing for selected comments. The available options are similar to the
                    ones in the comment editor.
                    <ul>
                        <li><em>Comment is private:</em> Private comments will not be displayed to your visitors.</li>
                        <li><em>Comment is approved:</em> Approved comments will be displayed in public. Visitors can read and reply
                            to them. Unapproved comments won't show up in public like private comments. Comment approval can be
                            disabled in system options.</li>
                        <li><em>Comment is spam:</em> Comments marks as spam won't be displayed to your visitors. They will
                            be used to improve spam detection if you don't delete them.</li>
                            disabled in system options.</li>
                        <li><em>Move comment to article with ID:</em> Move selected comments to a certain article id. The input field
                            offers search for articles and auto completion.</li>
                    </ul>
                </li>    
                <li><em>Delete:</em> Delete selected comments. Important! Comments do not offer a trash.</li>
            </ul>

            <p>The <span class="fpcm-ui-button">Search & filter</span> button allows you to search or filter you articles by various conditions.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_FILES_MNG
        </headline>
        <text>
        <![CDATA[
        <p>The <i>filemanager</i> allows you to manage all uploaded images you are using in your articles. A simplified view is
        available when opend in the article editor. The filemanager shows you a list of all images with a thumbnail and allows
        you to perform a few actions like deletion, creating new thumbnails and rename images.</p>
        <p>To upload an image, select the upload files tab. This tab has two different modes, which can be changed in system settings.</p>
        <p>The first one is multiple file uploader based on jQuery, which is less resticted then a second mode. The second mode
        uses a classic HTML form and PHP upload combination, which can be used if your're using an older browser or have other problems
        with the jQuery uploader.</p>
        <p><b>How to insert an image into an article?</b></p>
        <p>To insert the source path of an image, use the button <span class="fpcm-ui-button">Insert image URL</span> or
        <span class="fpcm-ui-button">Insert thumbnail URL</span>, depending on what you want to insert into your article.</p>
        <p>You also can click right on both buttons and copy the source link and insert it into the source field in the Insert image
        dialogue. The HTML editor view uses an auto-completion in the source field. Just start typing the file name. TinyMCE includes an
        option called "Image list" where you can select an image.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_PROFILE
        </headline>
        <text>
        <![CDATA[
            <p>The <b>profile</b> contains your personal settings such as:</p>
            <ul>
                <li><em>Password</em> for you login</li>
                <li><em>Display name</em> which will be displayed in every article you created</li>
                <li><em>E-Mail address</em> for password reset, comment notifications, etc.</li>
                <li><em>Language</em> for FanPress CM acp</li>
                <li><em>Timezone</em> for time and date of articles, etc.</li>
                <li><em>Date-Time-Mask</em> of your current location</li>
                <li><em>ACP article list limit</em> defined the number of articles display per page in ACP article list</li>
                <li><em>Default editor font size:</em> Default font size used in the article editor</li>
                <li><em>Use jQuery uploader:</em> Enables the jQuery file uploader.</li>
                <li><em>Biography / Other:</em> Short optional text with information about the current user which can be displayed
                    in the news.</li>
                <li><em>Avatar:</em> User avatar, filename pattern is <em>username.jpg/png/gif/bmp</em></li>
            </ul>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_OPTIONS
        </headline>
        <text>
        <![CDATA[
            <h3>System settings</h3>
            <p>Users with permissions to change settings can chose those options:</p>
            <ul>
                <li><b>General:</b><br>
                    The upper part contains general settings of the FanPress CM system.
                    <ul>
                        <li><em>Email address:</em> General Email address for comment notifications and so on.</li>
                        <li><em>Article URL base:</em> Base URL for articles, important for use with phpinclude. In most cases
                            this will be something like <em>your-domain.com/index.php</em> or a file which includes
                            <em>fpcmapi.php</em>.</li>
                        <li><em>Language:</em> Global system language, can be override by user settings.</li>
                        <li><em>Timezone:</em> Global system timezone, can be override by user settings.</li>
                        <li><em>Date-Time-Mask:</em> Settings how to display date and time informations, can be override by user settings.</li>
                        <li><em>Interval for cache timeout:</em> Interval until cache content expires automatically.</li>
                        <li><em>Usemode:</em> Mode how to use FanPress CM (phpinclude or iframe).</li>
                        <li><em>CSS style file path:</em> path to your css file if you use iframes.</li>
                        <li><em>Include jQuery library:</em> Include jQuery library shipped with FanPress CM. Important if you use
                        phpclude and get a warning the jQuery is not loaded at your page.</li>
                    </ul>
                </li>
                <li><b>Editor and file manager:</b><br>
                    Settings for editor and file manager.
                    <ul>
                        <li><em>Select editor:</em> Enables TinyMCE editor view or classic HTML editor view..</li>
                        <li><em>Default editor font size:</em> Default font size used in the article editor</li>
                        <li><em>Revisions enabled:</em> Enabled revision function to save changes on articles and make them
                        restorable.</li>
                        <li><em>Delete old revisions, if older then:</em> Remove all article revisions which are older then a amount of weeks</li>
                        <li><em>Enabled article trash:</em> Enable article trash to and be able to restore deleted articles.</li>
                        <li><em>Use jQuery uploader:</em> Enables the jQuery file uploader.</li>
                        <li><em>Number of images per page:</em> Number of images displayed per page in file manager.</li>
                        <li><em>Save image changes in TinyMCE in file system:</em> Save changes on images made using TinyMCE image tools
                        as file in upload folder.</li>
                        <li><em>Maximum thumbnail size:</em> Maximum size of created thumbnails.</li>
                        <li><em>CSS classes in editor:</em> CSS classes with can be used in article editor.</li>
                    </ul>
                </li>            
                <li><b>Articles:</b><br>
                    Settings for article output.
                    <ul>
                        <li><em>Articles per public page:</em> Number of articles to display per page in front end.</li>
                        <li><em>Article list template:</em> Template for front end article list.</li>
                        <li><em>Single article template:</em> Template to display a single article in front end.</li>
                        <li><em>Sort articles by:</em> Sorting of articles in article list.</li>
                        <li><em>Show share buttons:</em> Enabled social media share buttons.</li>
                        <li><em>RSS-Feed is enabled:</em> Enable RSS feed.</li>
                        <li><em>Enable URL rwriting for article links:</em> Article links will be extended with a version of the article title</li>
                        <li><em>Show archive link:</em> Enable archive link in front end pagination.</li>
                        <li><em>Show articles in archiv from:</em> Show articles in front end archive which were published after the give date, older won't be visible to your visitors</li>
                    </ul>
                </li>
                <li><b>Comments:</b><br>
                    Settings for comments and comment output.
                    <ul>
                        <li><em>Comments are enabled:</em> Comments are enabled globally.</li>
                        <li><em>Comment template:</em> Template to display a single comment.</li>
                        <li><em>Spam captcha question:</em> Question for default spam captcha plugin.</li>
                        <li><em>Spam captcha answer:</em> Answer for default spam captcha plugin.</li>
                        <li><em>Flood protection between two comments:</em> Time between to comment from same IP address.</li>
                        <li><em>Email address is required:</em> Email address is required to add a comment to an article.</li>
                        <li><em>Approval of comments is required:</em> Comment must be approved before being displayed in
                        article.</li>
                        <li><em>Send Notification of new comments to:</em> Email address which should be used for notifications
                        about new comments. (author only, global only, both)</li>
                        <li><em>Automatic "Mark as Spam":</em> This options allows you to set a limit number of comments, which are already
                            marked as spam, before a new comment of this author will be marked as spam automatically.</li>
                    </ul>
                </li>
                <li><b>Twitter connection:</b><br>
                    If you see this tab, got to the Twitter connection chapter below.
                </li>
                <li><b>Sicherheit & Wartung:</b><br>
                    This tab includes maintenance and security settings.
                    <ul>
                        <li><em>Maintenance mode enabled:</em> The "Maintenance mode" limits system access to already logged-in users only.</li>
                        <li><em>ACP session length:</em> Interval until auto logout from ACP.</li>
                        <li><em>Login auto-lock:</em> This settings defines the number of failed login-attempts before authentication is locked temporarily.</li>
                    </ul>
                </li>
                <li><b>Extended:</b><br>
                    All options on the "Extended" register should be used carefully.
                    <ul>
                        <li><em>Email notification when updates are available:</em> Enable or disable the e-mail notification if a new version
                            of FanPress CM was detected by the update cronjob.</li>
                        <li><em>Include development releases in update check:</em> This option allows you to include test- and
                            development versions when FanPress CM executes and update check. <b>Important: test- and development
                            may include failures, unfinished functions which can cause problems!</b></li>
                        <li><em>Update check interval if unable to connect to external servers:</em> If your FanPress CM
                            installation is unable to connect to the update server, a dialog will pop up from time to time
                            which includes the project page at <a href="https://Nobody-Knows.org">Nobody-Knows.org</a>.
                            This setting allows you to set the interval the dialog is displayed.</li>
                        
                        <li><em>Submit e-mail via SMTP:</em> If this setting is enabled, e-mail will be send using the e-mail-account
                        defined within the following data.</li>
                        <li><em>Email address:</em> Email address, used as adress to send messages</li>
                        <li><em>SMTP server address:</em> URL to the e-mail-server to use</li>
                        <li><em>SMTP server port:</em> Mail server port</li>
                        <li><em>SMTP username:</em> Username for e-mail account</li>
                        <li><em>SMTP password:</em> Password for account</li>
                        <li><em>SMTP-encryption:</em> Enable encryption for mail server connection. This must be supported
                        by your mail server.</li>
                    </ul>
                </li>
                <li><b>System check:</b><br>
                    This tab give you a detailed overview about your systems status and may indicate problems with your webspace.
                    Any <i>non optional</i> should contain a blue check symbol like <span class="fa fa-check-square fpcm-ui-booltext-yes"></span>.
                    In case it is not your probably should contact your host.
                </li>            
            </ul>

            <h3>User and rolls</h3>
            <ul>
                <li>Here you can manage users and user rolls in your system, including deletion, editing and so on.</li>
                <li>An user can be a member of only one group.</li>
                <li>You can disable users to keep their data (written articles & co.) and prevent them from login to the admin panel.
                    This can be usefull if the user was excluded or left your team, or the account was abused by someone else.</li>
                <li><strong>Permissions:</strong> Permissions allows you to define which user can use a function or not. This area
                should be visible for administrators only! You can't prohibit the Administrator roll to access permission settings.
                As of FPCM 3.6, permission are directly modified in the user roll management view.</li>
            </ul>

            <h3>IP addresses</h3>
            <p>Here you can lock IP addresses to prohibit access to FanPress CM because of spam comments, many login failures, etc.</p>
            <ul>
                <li><em>No comment access:</em> The user of this IP address was prohibited reading or writing comments..</li>
                <li><em>No login access:</em> The user of this IP address can't log into your FanPress CM system and can't
                    access the login mask.</li>
                <li><em>No frontend access:</em> The user of this IP address can't read you published articles, comments and so on.
                    Access to other parts of your site may depend of futher factors.</li>
            </ul>
                
            <h3>Censored texts</h3>
            <p>Censored texts prevent certain words, texts, text groups and strings from beeing used in comments, articles, categories, users and user rolls.</p>
            <ul>
                <li><em>Replace text:</em> Censored texts will be replaced by the "Replacement" text, if this option is enabled.</li>
                <li><em>Approval of article required:</em> Article is set to require an user approval, if the phrase is included.</li>
                <li><em>Approval of comment required:</em> Comment is set to require an user approval, if the phrase is included.</li>
            </ul>
                
            <h3>Categories</h3>
            <ul>
                <li>The area to manage article categories, depending on given permissions.</li>
                <li>The access to a category can be restricted to certain groups.</li>
                <li>The "Category icon" can be a remote image file or somewhere in you local file system. It is recommend to use the
                full URL of the file in both cases.</li>
            </ul>
                
            <h3>Templates</h3>
            <p>Edit templates to display articles, comments, the latest news, etc. in front end. The template editor includes
            syntax highlighting and a list of allowed replacements.</p>
            <ul>
                <li><em>Article list:</em> Template used for a single article in article lists.</li>
                <li><em>Single article view:</em> Template used for a single article opened in frontend including
                    comments and so on. Tab is invisible if <em>Article list</em> and <em>Single article view</em>
                    are the same value.</li>
                <li><em>Comment:</em> Template used for a single comment</li>
                <li><em>Comment form:</em> Template used for formular to create a comment</li>
                <li><em>Latest News:</em> Template used for a single line of the "Latest News" widget</li>
                <li><em>Tweet:</em> Template used for generation of a Tweet on Twitter! No HTML code allowed.</li>
                <li><em>Article templates:</em> HTML files which can be used are template in TinyMCE or HTML editor view.</li>
            </ul>
            
            <h3>Smileys</h3>
            <p>Manage smileys available in articles and comments.</p>
            
            <h3>Cronjobs</h3>
            <ul>
                <li>Cronjobs are tasks, executed automatically and regularly by FanPress CM in background.</li>
                <li>The cronjob list shows you when a cronjob was executed last, when it's next execution is planned.</li>
                <li>You can modfify the execution interval by changed the interval time.</li>
                <li>Please be careful when you change the intverval, because a cronjob can cause high system load.</li>
            </ul>
            
            <h3>System Logs</h3>
            <p>This area shows you the system logs of FanPress CM for sessions, system messages, errors and database errors.
               You can clear the logs in case they are to large.</p>
            <ul>
                <li>The <em>System Log</em> includes messages dropped by the FanPress CM system itself.</li>
                <li>The <em>PHP error log</em> lists error messages and notices triggered during the execution of FanPress CM.</li>
                <li>The <em>Database log</em> includes error messages, notices and so on triggered during the access of the
                    database.</li>
                <li>The <em>Session log</em> lists all logins of users available in FanPress CM.</li>
                <li>The <em>Package manager log</em> is an overview of installed packages from updates and installed modules.</li>
            </ul>
            
            <h3>Backup manager</h3>
            <ul>
                <li>The "Backup manager" allows you to download the database backups created by the FanPress CM cronjob.</li>
                <li>The created backups are zipped SQL-Files. The structure depends on the database system you're using.</li>
                <li>To restore a backup file, you can use tools such as
                    <a href="https://www.phpmyadmin.net/" target="_blank">phpMyAdmin</a>, <a href="https://www.adminer.org/" target="_blank">Adminer</a> or
                    <a href="http://phppgadmin.sourceforge.net/doku.php" target="_blank">phpPgAdmin</a>.
                </li>
            </ul>

        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_MODULES
        </headline>
        <text>
        <![CDATA[
            <p>The <b>Modules</b> area allows you to manage installed and available module including updates and so on.
            Depending on the amount of used events, classes, etc. modules and extend functionality of FanPress CM.</p>
            <p>When your host allows external connections to other servers (see dashboard container of system check),
            managing modules is pretty easy. In case you have to install/ update modules, use the "Install module manually" tab.
            Simply select the module ZIP archive file and start the upload. The package will be automatically extracted into the correct
            subfolder in "fanpress/inc/modules". If an uploaded module is not installed, it's install action will be called otherwise
            the update instructions will be executed.</p>
            <p>You can manage every module using the buttons in front of the module name. The visible buttons depend on the module
            manager roll permissions. Non-admin users should NOT have access to settings to manage modules. If you want to perform
            an action to various modules at the same time, check the boxes at the end on a line and select the action your want to
            execute at the right bottom.</p>
            <p>Module authors can define module <b>dependencies</b>, which means a selected module requires other modules to be enabled
            and usable. The button <span class="fpcm-ui-button"><span class="ui-icon ui-icon-alert"></span></span> at the beginning of a
            module line informs you about dependency errors. See module description for further information.</p>
            <p>In case you want to create an own module, check the <a href="https://nobody-knows.org/download/fanpress-cm/tutorial-zum-schreiben-eines-moduls/">Tutorial</a>
            and our <a href="http://updates.nobody-knows.org/fanpress/docs_fpcm3/">class documentation</a>.
            </p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_HELP_CACHE
        </headline>
        <text>
        <![CDATA[
            <p> In case it's not, click <span class="fpcm-ui-button">Clear cache</span> at the page top
            to cleanup the cache manually.</p>
            
            
            <ul>
                <li>FanPress CM includes a cache system to reduce load to database, file system and improve speed. If it's content is expired
            it will be rebuild automatically.</li>
                <li>In case it's not, click <span class="fpcm-ui-button" title="Cache leeren"><span class="fa fa-recycle fa-lg fa-fw"></span></span>
                at the page top to cleanup the cache manually.</li>
            </ul>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_HELP_INTEGRATION
        </headline>
        <text>
        <![CDATA[
            <p>Including FanPress CM depend an how you use the system on your site.</p>
            <p>An assistant for integration is provided by the "FanPress CM Integration" module which can be found in module manager.
            If you do it manually, here are further information:</p>
            <p><b>php include:</b></p>
            <p>When using php include, fist include the api file and create a new API object.</p>
            <pre>
                &lt;?php include_once 'fanpress/fpcmapi.php'; ?&gt;
                &lt;?php $api = new fpcmAPI(); ?&gt;
            </pre>                
            <p>Now you can use the API functions:</p>
            <ul>
                <li><strong>$api->showArticles()</strong> to display active articles, a single article or the article archive in
                front end. (fulfils task of shownews.php from FanPress CM 1.x and 2.x)</li>
                <li><strong>$api->showLatestNews()</strong> to show recent news list.</li>
                <li><strong>$api->showPageNumber()</strong> displays current page number, accepts a parameter for page descriptions
                like "Page XYZ".</li>
                <li><strong>$api->showTitle()</strong> displayse the article title in HTML &lt;title&gt; , 
                accepts a parameter for a separator of your text in &lt;title&gt;.</li>
                <li><strong>$api->legacyRedirect()</strong> redirect visitors which enters your site using an FanPress CM 1/2 article/ page URL style.</li>
            </ul>
            <p>You can use a couple of constants for further configuration of the output:</p>
            <ul>
                <li><strong>FPCM_PUB_CATEGORY_LATEST</strong> articles from category in $api->showLatestNews()</li>
                <li><strong>FPCM_PUB_CATEGORY_LISTALL</strong> articles from category in $api->showArticles()</li>
                <li><strong>FPCM_PUB_LIMIT_LISTALL</strong> amount of active articles in $api->showArticles()</li>
                <li><strong>FPCM_PUB_LIMIT_ARCHIVE</strong> amount of archived articles in $api->showArticles()</li>
                <li><strong>FPCM_PUB_LIMIT_LATEST</strong> amount of articles in $api->showLatestNews()</li>
                <li><strong>FPCM_PUB_OUTPUT_UTF8</strong> enable or disable usage of UTF-8 charset in output of $api->showLatestNews(),
                $api->showArticles() and $api->showTitle(). Should only be used in case special signs as german umlauts are displayed
                incorrectly.</li>
            </ul>              
            <p><b>iframes:</b></p>
            <p>In case your're using <i>iframes</i> you have to call the controllers manually.</p>
            <ul>
                <li><strong>your-domain.xyz/fanpress/index.php?module=fpcm/list</strong> show all active articles
                 (fulfils task of shownews.php from FanPress CM 1.x and 2.x)</li>
                <li><strong>your-domain.xyz/fanpress/index.php?module=fpcm/archive</strong> show article archive
                 (fulfils task of shownews.php from FanPress CM 1.x and 2.x)</li>
                <li><strong>your-domain.xyz/fanpress/index.php?module=fpcm/article&&amp;id=A_DIGIT</strong> show a single article with
                given id including comments</li>
                <li><strong>your-domain.xyz/fanpress/index.php?module=fpcm/latest</strong> show latest news</li>
            </ul>
            
            <p><b>RSS Feed:</b></p>
            <p>if you want to provide the RSS feed for your visitors, just create a link to <strong>your-domain.xyz/fanpress/index.php?module=fpcm/feed</strong>.                
            The link does not depend on the way you're using FanPress CM.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            SYSTEM_OPTIONS_TWITTER_CONNECTION
        </headline>
        <text>
        <![CDATA[
            <p>FanPress CM includes a twitter connection interface, so you can directly connect to Twitter and create tweets when
            publishing and/or updating an article.</p>
            <p>Execute the following steps to initialize the connection.</p>
            <ol class="list-large">
                <li>Open Twitter.com and sign in with your credentials. <a href="https://twitter.com/login" class="fpcm-ui-button">Open login</a></li>
                <li>Head to <strong>Options &rarr; System settings &rarr; Twitter connection</strong>.</li>
                <li><strong>API key:</strong> Click the  <span class="fpcm-ui-button">Request API key and/or token</span>, you well be redirected to
                    Twitters app mangement site.</li>
                <li>Select the <span class="fpcm-ui-button">Create new app</span> button.</li>
                <li>Insert the requested data into the form and click <span class="fpcm-ui-button">Create your Twitter application</span>.</li>
                <li>Now open tab <strong>Keys and Access Tokens</strong> and copy <strong>Consumer Key (API Key)</strong> and
                    <strong>Consumer Secret (API Secret)</strong> into the same-named fields in system settings.</li>
                <li>To create tweets select the <strong>Permissions</strong> tab and change <strong>Access Level</strong> to
                    <strong>Read and Write</strong>.</li>
                <li><strong>Access Token:</strong> After that you need to create an Access Token. On Twitter scroll down to
                    <strong>Your Access Token</strong> and select the <span class="fpcm-ui-button">Create my access token</span>
                    button. Again copy <strong>Access Token</strong> and <strong>Access Token Secret</strong> into the same-named
                    fields in system settings.</li>                
                <li>Now you can click <span class="fpcm-ui-button">Save</span> in system settings.</li>
                <li>In case all steps where successful, you'll get a message that the connection is active.</li>
            </ol>
            <p>To disconnect you system from Twitter, select <span class="fpcm-ui-button">Delete connection</span>.</p>
        ]]>
        </text> 
    </chapter>
    <chapter>
        <headline>
            HL_HELP_SUPPORT
        </headline>
        <text>
        <![CDATA[
            <p>In case you need help with technical issues or further questions, email me at <em>fanpress@nobody-knows.org</em>
            or <em>sea75300@yahoo.de</em>. You also can leave a comment on <a href="https://nobody-knows.org/download/fanpress-cm/">nobody-knows.org</a>.</p>
            <p>A fast and simple way to provide access in case you need support, install <em>FanPress CM Support Module</em> using the
            module manager. However, an e-mail with the created support user and additional information will be send during installation!</p>
        ]]>
        </text> 
    </chapter>
</chapters>