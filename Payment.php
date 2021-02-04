<?php

/**
 * Формируует параметры заказа для инициализации и проверки платежа
 * Проверка платежа будет происходить путем объединения всех параметров "o."
 * и ключа хранящегося только в онлайн магазине для создания hash
 */
class Payment
{
    const HASH_KEY = 'hash';

    private $hashKey;

    private $keyPrefix = 'o.';

    private $params = [];

    private $amount;

    private $terminalId;

    private $currency = 643;

    private $exponent = 2;

    private $shortDescription = '';

    private $longDesctioption = '';

    private $status = false;

    private $trxId = null;

    public function __construct(string $paymentKey)
    {
        $this->hashKey = $paymentKey;
    }

    public function addParams(array $params)
    {
        foreach ($params as $key => $param) {
            $paymentParamKey = $this->keyPrefix . $key;
            $this->params[$paymentParamKey] = $param;
        }
    }

    public function setRemoteParams(array $params)
    {
        $this->params = $params;
    }

    public function get(string $name)
    {
        return $this->params[$name];
    }

    /**
     * Добавляет параметр hash для возможности проверки достоверности информации
     * и возвращает массив параметров платежа
     *
     * @return array
     */
    public function toArrayParams()
    {
        $hash = $this->getHash();

        return array_merge(
            $this->params,
            [$this->keyPrefix . static::HASH_KEY => $hash]
        );
    }

    /**
     * Проверяет установленный hash и сгенерированый, должны совпадать
     *
     * @return bool
     */
    public function validatePayment()
    {
        $isValidated = false;
        $generatedHash = $this->getHash();
        
        if ($generatedHash === $this->params[static::HASH_KEY]) {
            $isValidated = true;
        }

        return $isValidated;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function confirm(array $params)
    {
        // Установить сумму и описание
        $this->status = true;
    }

    public function refuse(array $params)
    {
        // Установить описание отказа
    }

    public function getTrxId()
    {
        return $this->trxId;
    }

    /**
     * Генерирует hash и добавляет к массиву параметров
     *
     * @return void
     */
    protected function getHash()
    {
        $string = '';

        foreach ($this->params as $key => $param) {
            if ($key !== static::HASH_KEY) {
                $string .= $param;
            }
        }
        $string .= $this->hashKey;

        $hash = md5($string);
        
        return $hash;
    }
}
