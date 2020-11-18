<?php

require_once dirname(dirname(dirname(__DIR__))).'/inc/common.php';

class pubTemplateTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var bool
     */
    protected $backupGlobals = false;
    
    protected function setUp() : void {
        $GLOBALS['fpcm']['urls']['data'] = 'http://localhost/data/';
    }

    public function testParseArticleTemplate()
    {
        $co = $this->initConfigObject();
        $uo = $this->initUserObject();
        
        $ts = time() - 3600;

        $template = new fpcm\model\pubtemplates\article($co->article_template_active);

        $article = new \fpcm\model\articles\article();
        $article->setTitle('Lorem ipsum dolor sit amet, consetetur sadipscing elitr!');
        $article->setContent('Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.<!--- Page Break --->Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.');
        $article->setPinned(0);
        $article->setSources($co->system_url);
        $article->setCategories([1,2,3]);
        $article->setCreatetime($ts);
        $article->setChangetime(time());
        $article->setId(1);

        $template->assignByObject(
            $article,
            ['author' => $uo, 'changeUser' => $uo],
            ['Category 1' => (new fpcm\model\categories\category(1))->getCategoryImage()], 0
        );

        $output = $template->parse();

        $this->assertStringContainsString('Lorem ipsum dolor sit amet, consetetur sadipscing elitr!', $output);
        $this->assertStringContainsString($co->system_url, $output);
        $this->assertStringContainsString($uo->getDisplayname(), $output);
        $this->assertStringContainsString((string) new fpcm\view\helper\dateText($ts), $output);
        
    }

    public function testParseCommentTemplate()
    {
        $co = $this->initConfigObject();
        $uo = $this->initUserObject();
        
        $ts = time() - 1800;

        $template = new fpcm\model\pubtemplates\comment($co->comments_template_active);

        $comment = new \fpcm\model\comments\comment();
        $comment->setName($uo->getDisplayname());
        $comment->setEmail($uo->getEmail());
        $comment->setWebsite($co->system_url);
        $comment->setText('Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. ');
        $comment->setCreatetime($ts);
        
        $template->assignByObject($comment, 1);

        $output = $template->parse();

        $this->assertStringContainsString('Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. ', $output);
        $this->assertStringContainsString($co->system_url, $output);
        $this->assertStringContainsString($uo->getDisplayname(), $output);
        $this->assertStringContainsString((string) new fpcm\view\helper\dateText($ts), $output);
        
    }

    /**
     * 
     * @return \fpcm\model\users\author
     */
    private function initUserObject()
    {
        if (isset($GLOBALS['usr'])) {
            return $GLOBALS['usr'];
        }

        $GLOBALS['usr'] = new \fpcm\model\users\author();
        $GLOBALS['usr']->setDisplayName('FPCM Unit test User');
        $GLOBALS['usr']->setEmail('test@fpcm.net');
        return $GLOBALS['usr'];
    }

    /**
     * 
     * @return \fpcm\model\users\author
     */
    private function initConfigObject()
    {
        if (isset($GLOBALS['cfg'])) {
            return $GLOBALS['cfg'];
        }

        $GLOBALS['cfg'] = new fpcm\model\system\config();
        return $GLOBALS['cfg'];
    }


}
