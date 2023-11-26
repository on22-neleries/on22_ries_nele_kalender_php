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

    public function getMonthLabels()
    {
        return $this->monthLabels;
    }

    public function setMondayFirst($bool)
    {
        $this->mondayFirst = $bool;
        
        if(!$this->mondayFirst)
        {
            array_push($this->dayLabels, array_shift($this->dayLabels));
        }
    }

    public function setMonth($monthNumber)
    {
        $this->calendarDate = clone $this->calendarDate;
        $this->calendarDate->setDate($this->calendarDate->getYear(), $monthNumber, 1);
        $this->calendarDate->modify('first day of this month');
    }

    public function getCalendarMonth()
    {
        return $this->calendarDate->getMonthName();
    }

    protected function getMonthFirstDay()
    {
        $day = $this->calendarDate->getMonthStartDayOfWeek();

        if ($this->mondayFirst)
        {
            if ($day === 7)
            {
                return 1;
            }

            if ($day < 7)
            {
                return ($day + 1);
            }
        }

        return $day;
    }

    public function isCurrentDate($dayNumber)
    {
        if (
            $this->calendarDate->getYear() === $this->currentDate->getYear() &&
            $this->calendarDate->getMonthNumber() === $this->currentDate->getMonthNumber() &&
            $this->currentDate->getCurrentDayNumber() === $dayNumber
        ) {
            return true;
        }

        return false;
    }

    public function getWeeks()
    {
        return $this->weeks;
    }

    public static function fetchAppointmentById($id, $con){
        $sql = "SELECT termin_name, termin_datum, termin_uhrzeit FROM termine WHERE id = ".$id. " AND user_id = ". $_SESSION['uid'];
        $result = $con->query($sql);
        if ($result) {
            $data = $result->fetch_assoc();
            return $data;
        }
        return [];
    }

    public function fetchAppointmentsFromDatabase($con)
    {
       $sql = "SELECT * FROM termine WHERE MONTH(termin_datum) = " . $this->getSelectedMonth(). " AND user_id = " . $_SESSION['uid'];
       $result = $con->query($sql);

      if ($result) {
       while ($row = $result->fetch_assoc()) {
           $dayNumber = (int) date('j', strtotime($row['termin_datum']));
            foreach ($this->weeks as &$week) {
               foreach ($week as &$day) {
                    if ($day['currentMonth'] && $day['dayNumber'] == $dayNumber) {
                       if (!isset($day['appointments'])) {
                           $day['appointments'] = [];
                        }
                        $day['appointments'][] = $row;
                    }
                }
            }
        }
     } else {
        echo "Fehler bei der Datenbankabfrage: " . $con->error;
     }
    }

    public function create()
    {
        $days = array_fill(0, ($this->getMonthFirstDay() - 1), ['currentMonth' => false, 'dayNumber' => '']);

        //aktuelle Tage
        for ($x = 1; $x <= $this->calendarDate->getMonthNumberDays(); $x++)
        {
            $days[] = ['currentMonth' => true, 'dayNumber' => $x, 'appointment' => ''];
        }

        $this->weeks = array_chunk($days, 7);


        //Letzter Monat
        $firstWeek = $this->weeks[0];
        $prevMonth = clone $this->calendarDate;
        $prevMonth->modify('-1 month');
        $prevMonthNumDays = $prevMonth->getMonthNumberDays();

        for ($x = 6; $x >= 0; $x--)
        {
            if(!$firstWeek[$x]['dayNumber']) {
                $firstWeek[$x]['dayNumber'] = $prevMonthNumDays;
                $prevMonthNumDays -= 1;
            }
        }

        $this->weeks[0] = $firstWeek;

        //nächster Monat
        $lastWeek = $this->weeks[count($this->weeks) -1];
        $nextMonth = clone $this->calendarDate;
        $nextMonth->modify('+1 month');

        $c = 1;
        for ($x = 0; $x < 7; $x++) {
            if (!isset($lastWeek[$x]))
            {
                $lastWeek[$x]['currentMonth'] = false;
                $lastWeek[$x]['dayNumber'] = $c;
                $c++;
            }  
        }

        $this->weeks[count($this->weeks) -1] = $lastWeek;

    }
    
}
