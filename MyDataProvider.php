<?php

class MyDataProvider extends Threaded
{
    /**
     * @var int
     */
    private $total = 5;

    /**
     * @var int Сколько элементов было обработано
     */
    private $processed = 0;

    /**
     * Переходим к следующему элементу и возвращаем его
     *
     * @return mixed
     */
    public function getNext()
    {
        if ($this->processed === $this->total) {
            return null;
        }

        $this->processed++;

        return $this->processed;
    }
}
