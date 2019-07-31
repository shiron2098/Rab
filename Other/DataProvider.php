<?php

class DataProvider extends Threaded
{
    /**
     * @var int
     */
    private $total = 5;

    /**
     * @var int
     */
    private $processed = 0;

    /**
     *
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
