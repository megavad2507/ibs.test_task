<?php

namespace Ibs\Test;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

Loc::loadMessages(__FILE__);

class OptionTable extends ModalAbstract
{
    // название таблицы
    public static function getTableName()
    {
        return 'b_ibs_option';
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
            (new ManyToMany('LAPTOPS', LaptopTable::class))
                ->configureTableName('b_ibs_laptop_option')
                ->configureLocalPrimary('ID', 'OPTION_ID')
                ->configureLocalReference('OPTION')
                ->configureRemotePrimary('ID', 'LAPTOP_ID')
                ->configureRemoteReference('LAPTOP')
        ,
            (new ManyToMany('MODELS', ModelTable::class))
                ->configureTableName('b_ibs_model_option')
                ->configureLocalPrimary('ID', 'OPTION_ID')
                ->configureLocalReference('OPTION')
                ->configureRemotePrimary('ID', 'MODEL_ID')
                ->configureRemoteReference('MODEL')
        ,
        );
    }
}