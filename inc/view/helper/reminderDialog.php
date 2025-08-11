<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Dialog item
 *
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
class reminderDialog extends dialog {

    /**
     * Constructor
     * @param string $name
     */
    public function __construct(string $name = 'reminders')
    {        
        parent::__construct('reminders');

        $this->setFields([
            [
                (new \fpcm\view\helper\dateTimeInput('resub-date'))
                    ->setText('EDITOR_POSTPONED_DATE')
                    ->setIcon('calendar')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\dateTimeInput('resub-time'))
                    ->setText('EDITOR_POSTPONED_DATETIME')
                    ->setNativeTime()
                    ->setLabelTypeFloat()
                    ->setBottomSpace('')
            ],
            (new \fpcm\view\helper\select('user-id'))
                ->setText('LOGS_LIST_USER')
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setOptions(\fpcm\model\users\userList::getInstance()->getUsersNameList() )
                ->setIcon('user')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            (new \fpcm\view\helper\textInput('resub-comment'))
                ->setText('COMMMENT_TEXT')
                ->setLabelTypeFloat()
                ->setBottomSpace('')
        ]);
        
    }

}
