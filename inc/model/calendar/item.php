<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\calendar;

/**
 * Word Ban Item Object
 * 
 * @package fpcm\model\calendar
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0-b3
 */
class item extends \fpcm\model\abstracts\staticModel implements \JsonSerializable {

    /**
     * Calendar item label
     * @var string
     */
    private string $label = '';

    /**
     * Calendar item source
     * @var string
     */
    private string $src = '';

    /**
     * Calendar item CSS class
     * @var bool
     */
    private string $class = 'btn-info bg-opacity-75';

    /**
     * Calendar item ID
     * @var string
     */
    private string $id = '';

    /**
     * Calendar date time object
     * @var \DateTime|int
     */
    private \DateTime $dateTime;
    
    /**
     * Constructor
     * @param \DateTime|string|int $dateTime
     */
    public function __construct(\DateTime|string|int $dateTime)
    {
        parent::__construct();
        
        if ($dateTime instanceof \DateTime) {
            $this->dateTime = $dateTime;
        }
        elseif (is_string($dateTime)) {
            $this->dateTime = new \DateTime($dateTime);
        }
        elseif (is_int($dateTime)) {
            $this->dateTime = new \DateTime();
            $this->dateTime->setTimestamp($dateTime);
        }
        
        $this->dateTime->setTime(0, 0, 0);
        $this->id = $this->dateTime->format('Y-n-j');
    }

    /**
     * Get item label
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get item source
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * Get item class
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Set item label
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label, array $replaceParams = [], bool $spf = false)
    {
        $this->label = $this->language->translate($label, $replaceParams, $spf);
        return $this;
    }

    /**
     * Set item source
     * @param string $src
     * @return $this
     */
    public function setSrc(string $src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * Set item css class
     * @param string $class
     * @return $this
     */
    public function setClass(string $class)
    {
        $this->class = $class;
        return $this;
    }
    
    /**
     * Get calendar item ID
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get date string
     * @return string
     */
    public function getDate(): string
    {
        return $this->dateTime->format($this->config->system_dtmask);
    }

    /**
     * Retrieve JSON object data
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'label' => $this->label,
            'class' => $this->class,
            'src' => $this->src
        ];
    }

}
