<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates;

/**
 * Description of literals
 *
 * @author stefan
 */
enum variables: string {
    
    case article = 'article';

    case articles = 'articles';

    case comments = 'comments';
    
    case pager = 'pager_settings';
    
    case commentsActive = 'commentsActive';

    case form = 'form';

    case commentsListId = 'fpcm-id-pub-comments';

}
