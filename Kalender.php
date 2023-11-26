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

class CalendarDate extends DateTime
{
    use DateHelpers;

    public function __construct()
    {
        parent::__construct();
        $this->modify('first day of this month');
    }

    public function getMonthStartDayOfWeek()
    {
        // Aktualisierte Implementierung für den korrekten Tag der Woche
        $dayOfWeek = (int) $this->format('N');
        return ($dayOfWeek === 1) ? 7 : ($dayOfWeek - 1);
    }
}

class Calendar
{

    use DateHelpers;

    protected $currentDate;
    protected $calendarDate;

    protected $dayLabels = [
        'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'
    ];

    protected $monthLabels = [
        'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober',
        'November', 'Dezember'
    ];

    protected $mondayFirst = true;
    protected $weeks = [];

    public function __construct(CurrentDate $currentDate, CalendarDate $calendarDate)
    {
        $this->currentDate = $currentDate;
        $this->calendarDate = clone $calendarDate;
        $this->calendarDate->modify('first day of this month');
    }

    public function getCurrentDate()
    {
        return $this->currentDate;
    }

    public function getMonthNumber() {
        return $this->currentDate->getMonthNumber();
    }

    public function getSelectedMonth()
    {
        return $this->calendarDate->getMonthNumber();
    }


    public function getDayLabels()
    {
        return $this->dayLabels;
    }