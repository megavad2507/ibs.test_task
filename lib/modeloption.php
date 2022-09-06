<?php

namespace Ibs\Test;

use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class ModelOptionTable extends ModalAbstract
{
    public static function getTableName()
    {
        return 'b_ibs_model_option';
    }

    public static function getMap()
    {
        return array(
            (new IntegerField('MODEL_ID'))
                ->configurePrimary(true),

            (new Reference('MODEL', LaptopTable::class,
                Join::on('this.MODEL_ID', 'ref.ID')))
                ->configureJoinType('inner'),

            (new IntegerField('OPTION_ID'))
                ->configurePrimary(true),

            (new Reference('OPTION', OptionTable::class,
                Join::on('this.OPTION_ID', 'ref.ID')))
                ->configureJoinType('inner'),
        );
    }
}