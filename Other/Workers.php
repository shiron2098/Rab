<?php


class Workers extends Worker
{
    /**
     * @var DataProvider
     */
    private $provider;

    /**
     * @param DataProvider $provider
     */
    public function __construct(DataProvider $provider)
{
    $this->provider = $provider;
}


    public function run()
{

}

    /**
     *
     *
     * @return DataProvider
     */
    public function getProvider()
{
    return $this->provider;
}
}