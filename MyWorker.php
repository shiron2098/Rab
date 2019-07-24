<?php


class MyWorker extends Worker
{
    /**
     * @var MyDataProvider
     */
    private $provider;

    /**
     * @param MyDataProvider $provider
     */
    public function __construct(MyDataProvider $provider)
{
    $this->provider = $provider;
}

    /**
     * Вызывается при отправке в Pool.
     */
    public function run()
{
    // В этом примере нам тут делать ничего не надо
}

    /**
     * Возвращает провайдера
     *
     * @return MyDataProvider
     */
    public function getProvider()
{
    return $this->provider;
}
}