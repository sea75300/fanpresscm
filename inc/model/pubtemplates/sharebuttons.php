<?php
    /**
     * Share button object
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\pubtemplates;

    /**
     * Share Button Template Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class sharebuttons extends \fpcm\model\abstracts\staticModel {

        /**
         * zu teilender Link
         * @var string
         */
        protected $link;
        
        /**
         * Beschreibung für Share-EIntrag
         * @var string
         */
        protected $description;
        
        /**
         * Konstruktor
         * @param string $link Artikel-Link
         * @param string $description Artikel-Beschreibung
         */
        public function __construct($link, $description) {
            parent::__construct();
            
            $this->link = rawurlencode($link);
            $this->description = $description;
        }
        
        /**
         * Share-Buttons parsen
         * @return string
         */
        public function parse() {
            
            if (!$this->config->system_show_share) return '';

            $shareButtonPath = \fpcm\classes\baseconfig::$shareRootPath;
            
            $sharecode  = [];
            $sharecode[] = "<ul class=\"fpcm-pub-sharebuttons\">";
            $sharecode[] = "<li><a href=\"https://www.facebook.com/sharer/sharer.php?u={$this->link}&amp;t={$this->description}\" target=\"_blank\"><img src=\"{$shareButtonPath}default/facebook.png\" alt=\"Facebook\"></a></li>";
            $sharecode[] = "<li><a href=\"https://twitter.com/intent/tweet?source={$this->link}&amp;text={$this->description}\" target=\"_blank\" title=\"Tweet\"><img src=\"{$shareButtonPath}default/twitter.png\" alt=\"Twitter\"></a></li>";
            $sharecode[] = "<li><a href=\"https://plus.google.com/share?url={$this->link}\" target=\"_blank\" title=\"Share on Google+\"><img src=\"{$shareButtonPath}default/googleplus.png\" alt=\"Google+\"></a></li>";
            $sharecode[] = "<li><a href=\"http://www.tumblr.com/share?v=3&amp;u={$this->link}&amp;t={$this->description}&amp;s=\" target=\"_blank\" title=\"Post to Tumblr\"><img src=\"{$shareButtonPath}default/tumblr.png\" alt=\"Tumblr\"></a></li>";
            $sharecode[] = "<li><a href=\"http://pinterest.com/pin/create/button/?url={$this->link}&amp;description={$this->description}\" target=\"_blank\" title=\"Pin it\"><img src=\"{$shareButtonPath}default/pinterest.png\" alt=\"Pinterest\"></a></li>";
            $sharecode[] = "<li><a href=\"http://www.reddit.com/submit?url={$this->link}&amp;title={$this->description}\" target=\"_blank\" title=\"Submit to Reddit\"><img src=\"{$shareButtonPath}default/reddit.png\" alt=\"Reddit\"></a></li>";
            $sharecode[] = "<li><a href=\"mailto:?subject={$this->description}&amp;body={$this->link}\" target=\"_blank\" title=\"Email\"><img src=\"{$shareButtonPath}default/email.png\" alt=\"Email\"></a></li>";
            $sharecode[] = "<!-- default button icon set powered by http://simplesharingbuttons.com/ -->";
            $params      = array('sharebuttons' => $sharecode, 'description' => $this->description, 'link' => $this->link);
            $sharecode   = $this->events->runEvent('publicParseShareButtons', $params)['sharebuttons'];

            $sharecode[] = "</ul>";
            
            array_unshift($sharecode, PHP_EOL."<!-- Start FanPress CM Share Buttons -->");
            $sharecode[] = "<!-- Stop FanPress CM Share Buttons -->".PHP_EOL;
            
            return implode(PHP_EOL, $sharecode);
            
        }
        
    }
?>