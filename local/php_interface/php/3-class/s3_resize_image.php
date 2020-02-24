<?php
namespace Asdrubael\S3;

use Bitrix\Main;

/**
 * Class ImageTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> WIDTH string(8) mandatory
 * <li> HEIGHT string(8) mandatory
 * <li> IMG_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Resize
 **/

class ImageTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 's3_resize_image';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => 'IMAGE_ENTITY_ID_FIELD',
            ),
            'WIDTH' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateWidth'),
                'title' => 'IMAGE_ENTITY_WIDTH_FIELD',
            ),
            'HEIGHT' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateHeight'),
                'title' => 'IMAGE_ENTITY_HEIGHT_FIELD',
            ),
            'IMG_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => 'IMAGE_ENTITY_IMG_ID_FIELD',
            ),
        );
    }
    /**
     * Returns validators for WIDTH field.
     *
     * @return array
     */
    public static function validateWidth()
    {
        return array(
            new Main\Entity\Validator\Length(null, 8),
        );
    }
    /**
     * Returns validators for HEIGHT field.
     *
     * @return array
     */
    public static function validateHeight()
    {
        return array(
            new Main\Entity\Validator\Length(null, 8),
        );
    }
}