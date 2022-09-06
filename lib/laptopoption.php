<?php

namespace Ibs\Test;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class LaptopOptionTable extends ModalAbstract
{
    public static function getTableName()
    {
        return 'b_ibs_laptop_option';
    }

    public static function getMap()
    {
        return array(
            (new IntegerField('LAPTOP_ID'))
                ->configurePrimary(true),

            (new Reference('LAPTOP', LaptopTable::class,
                Join::on('this.LAPTOP_ID', 'ref.ID')))
                ->configureJoinType('inner'),

            (new IntegerField('OPTION_ID'))
                ->configurePrimary(true),

            (new Reference('OPTION', OptionTable::class,
                Join::on('this.OPTION_ID', 'ref.ID')))
                ->configureJoinType('inner'),
        );
    }
}