<?php
use Bitrix\Main\Loader;

define('IBLOCK_SEND', 1);

CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    array(
        // ключ - имя класса, значение - путь относительно корня сайта к файлу с классом
        '' => '',
    )
);

require "debug.php";
require "constants.php";