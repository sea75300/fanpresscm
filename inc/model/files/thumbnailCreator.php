<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

include_once \fpcm\classes\loader::libGetFilePath('PHPImageWorkshop');
include_once \fpcm\classes\loader::libGetFilePath('intervention/image');

/**
 * Image file objekt
 *
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0-rc2
 */
class thumbnailCreator {

    /**
     * Source full path
     * @var string
     */
    private string $source = '';

    /**
     * Desination full path
     * @var string
     */
    private string $destination = '';

    /**
     * Thumbnail parent path
     * @var string
     */
    private string $parent = '';

    /**
     * Thumbnail size in pixel
     * @var int
     */
    private int $thumbSize;

    /**
     * Creator function name
     * @var string
     */
    private static $fn = null;

    /**
     * Constructor
     * @param string $source
     * @param string $destination
     */
    public function __construct(string $source, string $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->parent = dirname($destination);
        $this->thumbSize = \fpcm\model\system\config::getInstance()->file_thumb_size;
    }

    /**
     * Create thumbnail with \Intervention\Image
     * @return bool
     */
    public function create(string $type) : bool
    {
        try {
            $mgr = new \Intervention\Image\ImageManager( \Intervention\Image\Drivers\Gd\Driver::class );
            $img = $mgr->read($this->source);
            $img->coverDown($this->thumbSize, $this->thumbSize);

            if (!ops::isValidDataFolder($this->parent, $type)) {
                trigger_error(sprintf('Error while creating file thumbnail %s, invalid data path: %s', $this->destination, $this->parent));
                return false;
            }

            if (!is_dir($this->parent) && !mkdir($this->parent)) {
                trigger_error(sprintf('Error while creating file thumbnail %s, unable to create parent folder: %s', $this->destination, $this->parent));
                return false;
            }

            $img->save($this->destination);
        } catch (Exception $exc) {
            trigger_error('Error while creating file thumbnail '.$this->destination.PHP_EOL.$exc);
            return false;
        }

        return true;
    }

    /**
     * Create thumbnail with PHPImageWorkshop
     * @return bool
     */
    public function createLegacy() : bool
    {
        try {
            $phpImgWsp = \PHPImageWorkshop\ImageWorkshop::initFromPath($this->source);
            $phpImgWsp->cropToAspectRatio(
                \PHPImageWorkshop\Core\ImageWorkshopLayer::UNIT_PIXEL,
                $this->thumbSize,
                $this->thumbSize,
                0, 0, 'MM'
            );

            $phpImgWsp->resizeInPixel($this->thumbSize, $this->thumbSize);
            $phpImgWsp->save($this->parent, basename($this->destination), true, null, 85);
        } catch (\ErrorException $exc) {
            trigger_error('Error while creating file thumbnail '.$this->getThumbnail().PHP_EOL.$exc->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Creator function name
     * @return string
     */
    public static function getFunctionName() : string
    {
        if (self::$fn !== null) {
            return self::$fn;
        }

        self::$fn = defined('FPCM_IMAGE_PROCESSOR_NEW') && FPCM_IMAGE_PROCESSOR_NEW
            ? 'create'
            : 'createLegacy';

        return self::$fn;
    }


}
