<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\comments;

/**
 * Kommentar-Liste trait
 *
 * @package fpcm\controller\traits\comments\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait massEdit {

    use \fpcm\controller\traits\common\massedit;

    /**
     * Initialisiert Suchformular-Daten
     * @param array $users
     */
    protected function initCommentMassEditForm(int $mode)
    {
        $fields = [];

        if ($this->permissions->comment->approve) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('isApproved'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('COMMMENT_APPROVE')
                    ->setIcon('check-circle', 'far')
                    ->setLabelTypeFloat()
            );

            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('isSpam'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('COMMMENT_SPAM')
                    ->setIcon('flag')
                    ->setLabelTypeFloat()
            );

        }

        if ($this->permissions->comment->private) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('isPrivate'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('COMMMENT_PRIVATE')
                    ->setIcon('eye-slash')
                    ->setLabelTypeFloat()
            );
        }

        if ($mode === 1 && $this->permissions->comment->move) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\textInput('moveToArticle'))
                    ->setClass('fpcm-ui-input-articleid')
                    ->setText('COMMMENT_MOVE')
                    ->setIcon('clipboard')
                    ->setLabelTypeFloat()
                    ->setPlaceholder('COMMMENT_MOVE')
            );
        }

        $this->assignFields($fields);
        $this->assignPageToken('comments');
        $this->view->addJsLangVars(['SAVE_FAILED_COMMENTS']);
    }

}
