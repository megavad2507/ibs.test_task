<?php

namespace Ibs\Test;

use Bitrix\Main\Page\Asset;

class EventsHandler
{
    public function appendBootstrapFiles()
    {
        if (!defined("ADMIN_SECTION") && ADMIN_SECTION !== true) {

            $module_id = pathinfo(dirname(__DIR__))["basename"];

            Asset::getInstance()->addJs("/bitrix/js/" . $module_id . "/jquery-3.6.1.min.js");
            Asset::getInstance()->addJs("/bitrix/js/" . $module_id . "/bootstrap.bundle.min.js");
            Asset::getInstance()->addCss("/bitrix/css/" . $module_id . "/bootstrap.min.css");

        }
    }
}