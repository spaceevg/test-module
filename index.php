<?php
// Пример функций для реализации в wordpress

function paymentInitialization ()
{
    // Собрать настройки платежного модуля в массив
    $arraySettings = [];

    $settings = ECommerceSettings::init($arraySettings);
    $commerceService = new ECommerce($settings);

    $payment = new Payment($settings->get(ECommerceSettings::PAYMENT_KEY));
    // Заполнить параметры платежа
    $payment->addParams([]);

    $url = $commerceService->paymentInitialization($payment);
    redirect($url);
}

function paymentVerification()
{
    // Собрать настройки платежного модуля в массив
    $arraySettings = [];

    $settings = ECommerceSettings::init($arraySettings);
    $commerceService = new ECommerce($settings);

    // Заполнить Request параметрами запроса
    $request = new Request();
    $request->setQueryParams([]);

    // Происходит проверка запроса на достоверность
    $payment = $commerceService->paymentVerification($request);

    // Заполнить необходимую информацию для подстверждения или отказа платежа
    $payment->confirm([]);
    $payment->refuse([]);

    // Заполнить статус платежа в магазине
    $trxId = $payment->getTrxId();

    // Получить сформированный ответ
    $response = $commerceService->getPaymentVerificationResponse($payment);

    // Вернуть заголовки и тело ответа
    $headers = $response->getHeaders();
    $body = $response->getBody();
}

function fixingPayment()
{
    // Собрать настройки платежного модуля в массив
    $arraySettings = [];

    $settings = ECommerceSettings::init($arraySettings);
    $commerceService = new ECommerce($settings);

    // Заполнить Request параметрами запроса
    $request = new Request();
    $request->setQueryParams([]);

    // Происходит проверка запроса на достоверность и формирование платежной информации
    $resultPayment = $commerceService->fixingPayment($request);

    // Сохраняем платежную информацию
    $status = save($resultPayment->toArray());

    $response = $commerceService->getFixingPaymentResponse($status);
    // Вернуть заголовки и тело ответа
    $headers = $response->getHeaders();
    $body = $response->getBody();
}
