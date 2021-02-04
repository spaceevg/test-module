<?php

/**
 * Формирует URL к платежной системе банка
 */
class UrlSkbBank
{
    /** @var string Адрес платежной системы */
    private $urlSkbBank = 'https://mpi.skbbank.ru/payment/start.wsm';

    /** @var array Массив GET параметров для добавления к запросу */
    private $getParams = [];

    /**
     * Дает возможность переопределить стандартный адрес платежной системы
     *
     * @param string $urlSkbBank
     */
    public function __construct(string $urlSkbBank = null)
    {
        if ($urlSkbBank) {
            $this->urlSkbBank = $urlSkbBank;
        }
    }

    /**
     * Добавляет или изменяет GET параметры
     *
     * @param array $params
     * @return void
     */
    public function addGetParams(array $params)
    {
        foreach ($params as $name => $value) {
            $this->getParams[$name] = $value;
        }
    }

    /**
     * При обращении к классу как к строке сформирует URL строку
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Возвращает готовый URL в формате строки
     *
     * @return string
     */
    public function toString()
    {
        $url = $this->urlSkbBank;
        $getParams = http_build_query($this->getParams);

        return $url . '?' . $getParams;
    }
}
