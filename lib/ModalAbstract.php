<?php

namespace Ibs\Test;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Localization\Loc;

class ModalAbstract extends DataManager
{
    private function getFieldByFilterClass($class)
    {
        $resultArray = [];
        $fields = $this->getEntity()->getFields();
        foreach ($fields as $field) {
            if (is_a($field, $class))
                $resultArray[] = $field;
        }
        return $resultArray;
    }

    public function getRelations()
    {
        $oneToManyRelations = $this->getFieldByFilterClass(\Bitrix\Main\ORM\Fields\Relations\OneToMany::class);
        $manyToManyRelations = $this->getFieldByFilterClass(\Bitrix\Main\ORM\Fields\Relations\ManyToMany::class);
        $referenceRalation = $this->getFieldByFilterClass(\Bitrix\Main\ORM\Fields\Relations\Reference::class);;
        return array_merge($oneToManyRelations, $manyToManyRelations, $referenceRalation);
    }

    public static function getTitle()
    {
        return Loc::getMessage('MODEL_TITLE_VALUE');
    }
}