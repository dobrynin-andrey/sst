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


    if (!empty($request->getPost("name"))) {
        // Проверка поля имени
        $name = htmlspecialchars(trim($request->getPost("name")));

        switch ($name) {
            case (empty($name)):
                $result['error']['name'] = 1;
                throw new Exception('Поле не должно быть пустым!');
                break;
            case (strlen($name) < 3):
                $result['error']['name'] = 1;
                throw new Exception('Не менее 3 символов!');
                break;
        }
    }


    if (!empty($request->getPost("email"))) {
        // Проверка поля email
        $email = strtolower(htmlspecialchars(trim($request->getPost("email"))));

        switch ($email) {
            case (empty($email)):
                $result['error']['email'] = 1;;
                throw new Exception('Поле не должно быть пустым!');
                break;
            case (check_email($email) < 3):
                $result['error']['email'] = 1;
                throw new Exception('Почта некорректна!');
                break;
        }
        $previewText = $email;
    }


    if (!empty($request->getPost("phone"))) {
        // Проверка поля телефон
        $phone = strtolower(htmlspecialchars(trim($request->getPost("phone"))));

        switch ($phone) {
            case (empty($phone)):
                $result['error']['phone'] = 1;
                throw new Exception('Поле не должно быть пустым!');
                break;
            case (strlen($phone) < 11):
                $result['error']['phone'] = 1;
                throw new Exception('Не менее 11 символов!');
                break;
        }

        $previewText = $phone;
    }


    // Определение типа формы
    $formType = htmlspecialchars($request->getPost("form_type"));

    $element = array(
        "IBLOCK_ID"     => IBLOCK_SEND,
        "NAME"          => $name,
        "PREVIEW_TEXT"  => $previewText,
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
            "PHONE"     => $phone,
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
    CHTTP::SetStatus('400 Bad request');
}

header('Content-Type: application/json');
die(json_encode($result, JSON_UNESCAPED_UNICODE));