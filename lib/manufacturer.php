<?php

namespace Ibs\Test;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

Loc::loadMessages(__FILE__);

class ManufacturerTable extends ModalAbstract
{
    // название таблицы
    public static function getTableName()
    {
        return 'b_ibs_manufacturer';
    }

    // поля таблицы
    public static function getMap()
    {
        return array(
            new IntegerField('ID', array(
                'autocomplete' => true,
                'primary' => true
            )),
            new StringField('NAME', array(
                'required' => true,
                'title' => Loc::getMessage('TABLE_COLUMN_NAME')
            )),
            new StringField('CODE', array(
                'required' => true,
                'title' => Loc::getMessage('TABLE_COLUMN_CODE')
            )),
            (new OneToMany(
                'MODELS',
                ModelTable::class,
                'MANUFACTURER'
            ))
        );
    }

}
