<?php

/**
 * Обработчик событий инициализации, проверки и фиксации платежей
 */
class ECommerce
{
    /** @var Хранит объект с параметрами, необходимыми для работы модуля оплаты*/
    protected $settings;

    const PAYMENT_STATUS_WRONG = 2;
    const PAYMENT_STATUS_AVAILABLE = 1;

    protected $paymentVerificationResponse = null;

    public function __construct(ECommerceSettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Собирает все необходимые данные для запроса в банк на инициализацию платежа
     * Возвращает URL, на который необходимо отправить клиента, для дальнейшей оплаты
     *
     * @param Payment $payment
     * @param string $urlSkbBank
     * @return UrlSkbBank
     */
    public function paymentInitialization(Payment $payment, string $urlSkbBank = null): UrlSkbBank
    {
        $url = new UrlSkbBank($urlSkbBank);

        $getParams = [
            ECommerceSettings::MERCH_ID   => $this->settings->get(ECommerceSettings::MERCH_ID),
            ECommerceSettings::BACK_URL_S => $this->settings->get(ECommerceSettings::BACK_URL_S),
            ECommerceSettings::BACK_URL_F => $this->settings->get(ECommerceSettings::BACK_URL_F),
        ];
        empty($this->settings->get(ECommerceSettings::LANG))
            ?: $getParams[ECommerceSettings::LANG] = $this->gettings->get(ECommerceSettings::LANG);

        foreach ($payment->toArrayParams() as $key => $paymentParam) {
            $getParams[$key] = $paymentParam;
        }

        $url->addGetParams($getParams);

        return $url;
    }

    public function paymentVerification(Request $request)
    {
        $result = false;

        if ($this->validateVerificationRequest($request)) {
            $paymentData = $request->getPaymentData();
            $payment = new Payment($this->settings->get(ECommerceSettings::PAYMENT_KEY));
            $payment->setRemoteParams($paymentData);

            if ($payment->validatePayment()) {
                $result = $payment;
            }
        } else {
            $response = new Response();
            $response->setCode(static::PAYMENT_STATUS_WRONG);
            $response->setDescription("Ошибка идентификатора");

            $this->paymentVerificationResponse = $response;
        }

        return $result;
    }

    public function getPaymentVerificationResponse(Payment $payment = null)
    {
        if ($this->paymentVerificationResponse !== null) {
            return $this->paymentVerificationResponse;
        }

        if (!empty($payment) && $payment->getStatus()) {
            $this->paymentVerificationResponse = $this->getAvailableResponse($payment);
        } else {
            $this->paymentVerificationResponse = $this->getWrongResponse($payment);
        }

        return $this->paymentVerificationResponse;
    }

    public function fixingPayment(Request $request)
    {
        // Фиксация платежа
        return new ResultPayment();
    }

    public function getFixingPaymentResponse($status)
    {
        return new Response();
    }

    protected function getAvailableResponse()
    {
        //
    }

    protected function getWrongResponse()
    {
        //
    }

    protected function validateVerificationRequest(Request $request)
    {
        $isValid = false;

        if ($request->getQueryParam(ECommerceSettings::MERCH_ID) === $this->settings->get(ECommerceSettings::MERCH_ID)) {
            $isValid = true;
        }

        return $isValid;
    }
}
