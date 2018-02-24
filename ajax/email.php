<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Mail\Event as Event;


$application = Application::getInstance();
$request = $application->getContext()->getRequest();

$result['success'] = 0;
$result['error'] = array();

$previewText = '';
CModule::IncludeModule("iblock");

try {

    if (!$request->isPost()) {
        throw new Exception('Ошибка! Форма не отправлена!');
    }


    // Проверка поля имени
    $name = htmlspecialchars(trim($request->getPost("name")));

    if (empty($name)) {
        $result['error']['name'] = 1;
        throw new Exception('Поле не должно быть пустым!');
    }

    if (strlen($name) < 3) {
        $result['error']['name'] = 1;
        throw new Exception('Не менее 3 символов!');
    }


    // Проверка поля email
    $email = strtolower(htmlspecialchars(trim($request->getPost("email"))));

    if (empty($email)) {
        $result['error']['email'] = 1;
        throw new Exception('Поле не должно быть пустым!');
    }

    if (!check_email($email)) {
        $result['error']['email'] = 1;
        throw new Exception('Почта некорректна!');
    }



    // Определение типа формы
    $formType = htmlspecialchars($request->getPost("form_type"));

    $element = array(
        "IBLOCK_ID"     => IBLOCK_SEND,
        "NAME"          => $name,
        "PREVIEW_TEXT"  => $email,
        "DETAIL_TEXT"   => $formType
    );

    // Ошибок нет, пишем в инфоблок и отправляем письма
    $el = new CIBlockElement;
    $resIblock = $el->Add($element);

    $eventName = "AJAX_FEEDBACK_FORM";
    $messageId = 8;

    $resSend = Event::send(array(
        "EVENT_NAME" => $eventName,
        "MESSAGE_ID" => $messageId,
        "LID" => "s1",
        "C_FIELDS" => array(
            "NAME"      => $name,
            "EMAIL"     => $email,
            "FORM_TYPE" => $formType,
            ),
        )
    );

    if($resIblock && $resSend) {
        $result['success'] = 1;
        if(empty($result['error']))
            unset($result['error']);
    }


} catch (Exception $e) {
    $result['error']['message'] = $e->getMessage();
    if(empty($result['success']))
        unset($result['success']);
    CHTTP::SetStatus('400 Bad request');
}

header('Content-Type: application/json');
die(json_encode($result, JSON_UNESCAPED_UNICODE));