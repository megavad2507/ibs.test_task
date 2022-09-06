<?php
//подключаем основные классы для работы с модулем
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\EventManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Ibs\Test\LaptopOptionTable;
use Ibs\Test\LaptopTable;
use Ibs\Test\ManufacturerTable;
use Ibs\Test\ModelOptionTable;
use Ibs\Test\ModelTable;
use Ibs\Test\OptionTable;

Loc::loadMessages(__FILE__);

class ibs_test extends CModule
{
    const QUANTITY_SEED_ITEMS = 200;//количество элементов для автогенерации
    const QUANTITY_RELATION_ITEMS = 3;//количество связанных элементов в отношениях many to many

    public function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . '/version.php';
        //присваиваем свойствам класса переменные из нашего файла
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_GROUP_RIGHTS = 'Y';
        $this->MODULE_ID = 'ibs.test';
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('MODULE_PARTNER_NAME');
    }

    public function DoInstall()
    {
        global $APPLICATION, $step, $installTables;
        $step = (int)$step;
        if ($step == 0) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("MODULE_FIRST_STEP"),
                __DIR__ . "/step1.php"
            );
        } else {
            ModuleManager::registerModule($this->MODULE_ID);
            if (isset($installTables) && $installTables == "Y") {
                $this->InstallDB();
                $this->SeedDB();
            }
            $this->InstallFiles();
            $this->InstallEvents();
            $this->InstallComponents();
        }

    }

    //вызываем метод удаления таблицы и удаляем модуль из регистра
    public function DoUninstall()
    {
        global $APPLICATION, $step, $uninstallTables;
        if ($step == 0) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("MODULE_FIRST_UNSTEP"),
                __DIR__ . "/unstep1.php"
            );
        } else {
            if (isset($uninstallTables) && $uninstallTables == "Y") {
                $this->UnInstallDB();
            }
            $this->UnInstallEvents();
            ModuleManager::unRegisterModule($this->MODULE_ID);
        }
    }

    //вызываем метод создания таблицы из выше подключенного класса
    public function InstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $ormClasses = [
                ManufacturerTable::class,
                ModelTable::class,
                LaptopTable::class,
                OptionTable::class,
                LaptopOptionTable::class,
                ModelOptionTable::class
            ];
            foreach ($ormClasses as $class) {
                if (!Application::getConnection()->isTableExists(Base::getInstance($class)->getDBTableName())) {
                    Base::getInstance($class)->createDbTable();
                }
            }
        }
    }

    private function SeedDB()
    {
        $this->SeedManufacturer();
        $this->SeedModel();
        $this->SeedLaptop();
        $this->SeedOption();
        $this->SeedRelations(ModelTable::class, 'MODEL_ID',
            OptionTable::class, 'OPTION_ID', ModelOptionTable::class);
        $this->SeedRelations(LaptopTable::class, 'LAPTOP_ID',
            OptionTable::class, 'OPTION_ID', LaptopOptionTable::class);
    }


    private function SeedManufacturer()
    {
        for ($i = 1; $i <= self::QUANTITY_SEED_ITEMS / 5; $i++) {
            $name = 'Производитель №' . $i;
            ManufacturerTable::add([
                'NAME' => $name,
                'CODE' => CUtil::translit($name, 'ru')
            ]);
        }
    }

    private function getRandomId($model)
    {
        $result = $model::getList(array(
            'runtime' => array('RAND' => array('data_type' => 'float', 'expression' => array('RAND()'))),
            'order' => array('RAND' => 'ASC'),
            'limit' => 1,
        ));
        return $result->fetch()['ID'];
    }

    private function SeedModel()
    {
        for ($i = 1; $i <= self::QUANTITY_SEED_ITEMS; $i++) {
            $name = 'Модель №' . $i;

            ModelTable::add([
                'NAME' => $name,
                'CODE' => CUtil::translit($name, 'ru'),
                'MANUFACTURER_ID' => $this->getRandomId(ManufacturerTable::class)
            ]);
        }
    }

    private function SeedLaptop()
    {
        for ($i = 1; $i <= self::QUANTITY_SEED_ITEMS * 5; $i++) {
            $randId = rand(1, self::QUANTITY_SEED_ITEMS);
            $name = 'Ноутбук №' . $i;
            LaptopTable::add([
                'NAME' => $name,
                'YEAR' => rand(2010, 2022),
                'PRICE' => rand(10000, 10000000) / 10,
                'CODE' => CUtil::translit($name, 'ru'),
                'MODEL_ID' => $this->getRandomId(ModelTable::class)
            ]);
        }
    }

    private function SeedOption()
    {
        for ($i = 1; $i <= self::QUANTITY_SEED_ITEMS; $i++) {
            $name = 'Опция №' . $i;
            OptionTable::add([
                'NAME' => $name,
                'CODE' => CUtil::translit($name, 'ru')
            ]);
        }
    }

    private function SeedRelations($firstORMClass, string $firstRelationColumn,
                                   $secondORMClass, string $secondRelationColumn,
                                   $pivotORMClass)
    {
        $firstORMClassItems = $firstORMClass::getList();
        while ($firstItem = $firstORMClassItems->fetchObject()) {
            $randIds = [];
            while (count($randIds) < self::QUANTITY_RELATION_ITEMS) {
                $randId = $this->getRandomId($secondORMClass);
                if (!in_array($randId, $randIds))
                    $randIds[] = $randId;
                unset($randId);
            }
            $secondORMClassItems = $secondORMClass::getList([
                'filter' => [
                    '=ID' => $randIds
                ]
            ]);
            while ($secondItem = $secondORMClassItems->fetchObject()) {
                $item = $pivotORMClass::createObject()
                    ->set($firstRelationColumn, $firstItem->getId())
                    ->set($secondRelationColumn, $secondItem->getId());
                $item->save();
            }

        }
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . "/assets/js",
            Application::getDocumentRoot() . "/bitrix/js/" . $this->MODULE_ID . "/",
            true,
            true
        );

        CopyDirFiles(
            __DIR__ . "/assets/css",
            Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID . "/",
            true,
            true
        );
    }

    public function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler(
            "main",
            "OnBeforeEndBufferContent",
            $this->MODULE_ID,
            "Ibs\Test\EventsHandler",
            "appendBootstrapFiles"
        );
    }

    public function InstallComponents()
    {
        CopyDirFiles(
            __DIR__ . "/components/",
            Application::getDocumentRoot() . "/bitrix/components/",
            true,
            true
        );
    }

    //вызываем метод удаления таблицы, если она существует
    public function UnInstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $ormClasses = [
                ManufacturerTable::class,
                ModelTable::class,
                LaptopTable::class,
                OptionTable::class,
                LaptopOptionTable::class,
                ModelOptionTable::class
            ];
            foreach ($ormClasses as $class) {
                if (Application::getConnection()->isTableExists(Base::getInstance($class)->getDBTableName())) {
                    $connection = Application::getInstance()->getConnection();
                    $connection->dropTable($class::getTableName());
                }
            }

        }
    }

    public function UnInstallFiles()
    {
        Directory::deleteDirectory(
            Application::getDocumentRoot() . "/bitrix/js/" . $this->MODULE_ID
        );

        Directory::deleteDirectory(
            Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID
        );
    }

    public function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            "main",
            "OnBeforeEndBufferContent",
            $this->MODULE_ID,
            "Ibs\Test\EventsHandler",
            "appendBootstrapFiles"
        );
    }


}