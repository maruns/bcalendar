<?php
/**
 * Klasa MinutePeriod
 */
/**
 * Przedział czasowy wyrażony w minutach
 */
class MinutePeriod
{
    private $Start;
    private $End;
    private $VersionToDisplay;
    /**
     *
     * @param int $start początek przedziału w minutach
     * @param int $end zakończenie przedziału w minutach
     * @param string $ds zakończenie przedziału do wyświetlenia
     * @param string $de zakończenie przedziału do wyświetlenia
     */
    public function __construct($start, $end, $ds, $de)
    {
        $this->Start = $start;
        $this->End = $end;
        $this->VersionToDisplay = substr($ds, 0, 5).'-'.substr($de, 0, 5);
    }
    /**
     * Zwraca początek przedziału
     * 
     * @return int początek w minutach
     */
    public function GetStart()
    {
        return $this->Start;
    }
    /**
     * Zwraca zakończenie przedziału
     * 
     * @return int zakończenie w minutach
     */
    public function GetEnd()
    {
        return $this->End;
    }
    /**
     * Zwraca przedział w wersji do wyświetlenia
     * 
     * @return string przedział do wyświetlenia
     */
    public function GetVersionToDisplay()
    {
        return $this->VersionToDisplay;
    }
}
?>
