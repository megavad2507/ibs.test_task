<?php

namespace Ibs\Test;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use CCurrencyLang;

Loc::loadMessages(__FILE__);

class LaptopTable extends ModalAbstract
{
    // название таблицы
    public static function getTableName()
    {
        return 'b_ibs_laptop';
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
            new IntegerField('YEAR', array(
                'required' => true,
                'title' => Loc::getMessage('TABLE_COLUMN_YEAR')
            )),
            new FloatField('PRICE', array(
                'required' => true,
                'title' => Loc::getMessage('TABLE_COLUMN_PRICE')
            )),
            new StringField('CODE', array(
                'required' => true,
                'title' => Loc::getMessage('TABLE_COLUMN_CODE')
            )),
            new IntegerField('MODEL_ID', array(
                'required' => true,
            )),
            (new Reference(
                'MODEL',
                ModelTable::class,
                Join::on('this.MODEL_ID', 'ref.ID')
            ))
                ->configureJoinType('left'),
            (new ManyToMany('OPTIONS', OptionTable::class))
                ->configureTableName('b_ibs_laptop_option')
                ->configureLocalPrimary('ID', 'LAPTOP_ID')
                ->configureLocalReference('LAPTOP')
                ->configureRemotePrimary('ID', 'OPTION_ID')
                ->configureRemoteReference('OPTION')
        ,
        );
    }
}