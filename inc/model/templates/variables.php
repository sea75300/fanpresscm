<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates;

/**
 * Frontend twig template literals
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
enum variables: string {
    
    case article = 'article';

    case articles = 'articles';

    case comments = 'comments';
    
    case pager = 'pager_settings';
    
    case commentsActive = 'commentsActive';

    case form = 'form';

    case commentsListId = 'fpcm-id-pub-comments';
    
    case drafts = 'drafts';
    
    case extTplOverview = 's.html.twig';

}
