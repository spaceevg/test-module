<?php

/**
 * Реализует проверку всех параметров для работы модуля
 * Позволяет получать, изменять значения
 * Содержит метод преобразования в массив для возможности организации последующего длительного хванения
 * 
 * Необходимо реализовать проверку минимально возможных параметоров для работы модуля
 */
class ECommerceSettings
{
    /** @var array Хранит массив с настройками */
    protected $settings;

    const MERCH_ID = 'merch_id';

    const BACK_URL_S = 'back_url_s';

    const BACK_URL_F = 'back_url_f';

    const LANG = 'lang';

    const PAYMENT_KEY = 'payment_key';

    /**
     * Метод закрыт,cоздание объекта происходит через статичный метод init
     * для реализации предварительной проверки входных параметров
     *
     * @param [type] $settings
     */
    protected function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Необходим для заполнения объекта всеми необходимыми настройками единоразово
     * Инициализирует объект при условии проверенных входных данных
     *
     * @param array $settings Ассоциативный массив с параметрами для работы модуля оплаты
     * @return ECommerceSettings
     * 
     * @throws Exception
     */
    public static function init(array $settings = []): ECommerceSettings
    {
        if (static::validateSettings($settings))
        {
            return new static($settings);
        } else {
            throw new Exception("Invalid arguments");
        }
    }

    /**
     * Метод для получения определенной настройки
     *
     * @param string $name Название необходимого параметра
     * @return mixed
     */
    public function get(string $name)
    {
        if (isset($this->settings[$name])) {
            return $this->settings[$name];
        } else {
            return false;
        }
    }

    /**
     * Метод для возможности получения всех настроек в массив
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->settings;
    }

    /**
     * Функция изменения значения параметра
     *
     * @param string $name Наименование параметра
     * @param mixed $value Значение параметра
     * @return boolean
     */
    public function save(string $name, $value): bool
    {
        $result = false;

        if ($this->validate($name, $value)) {
            $this->settings[$name] = $value;
            $result = true;
        }

        return $result;
    }

    /**
     * Метод для проверки входных параметров перед созданием объекта
     *
     * @param array $settings
     * @return bool
     */
    protected static function validateSettings(array $settings): bool
    {
        foreach ($settings as $key => $value) {
            if (!static::validate($key, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Функция проверки значения параметра
     *
     * @param string $name Наименование параметра
     * @param mixed $value Значение параметра
     * @return boolean
     */
    protected static function validate($name, $value): bool
    {
        throw new Exception("Необходимо реализовать метод проверки входных параметров");
    }
}
