<?php

namespace Ibs\Test;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

class ModelTable extends ModalAbstract
{
    // название таблицы
    public static function getTableName()
    {
        return 'b_ibs_model';
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
            new IntegerField('MANUFACTURER_ID'),
            new StringField('CODE', array(
                'required' => true,
                'title' => Loc::getMessage('TABLE_COLUMN_CODE')
            )),
            (new Reference(
                'MANUFACTURER',
                ManufacturerTable::class,
                Join::on('this.MANUFACTURER_ID', 'ref.ID')
            ))
                ->configureJoinType('left'),
            (new OneToMany(
                'LAPTOPS',
                LaptopTable::class,
                'MODEL'
            )),
            (new ManyToMany('OPTIONS', OptionTable::class))
                ->configureTableName('b_ibs_model_option')
                ->configureLocalPrimary('ID', 'MODEL_ID')
                ->configureLocalReference('MODEL')
                ->configureRemotePrimary('ID', 'OPTION_ID')
                ->configureRemoteReference('OPTION')

        );
    }
}