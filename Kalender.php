<?php

trait DateHelpers
{
    public function getMonthNumberDays()
    {
        return (int) $this->format('t');
    }

    public function getCurrentDayNumber()
    {
       return (int) $this->format('j');
    }

    public function getMonthNumber()
    {
        return (int) $this->format('n');
    }

    public function getMonthName()
    {
        return $this->format('M');
    }

    public function getYear()
    {
        return $this->format('Y');
    }
}

class CurrentDate extends DateTimeImmutable
{
    use DateHelpers;

    public function __construct()
    {
        parent::__construct();
    }
}
