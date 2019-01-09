<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 09/01/2019
 * Time: 14:23
 */
require_once ('ConsumptionDataSet.php');
class Connection
{
    // stores the url base
    private $urlBase;
    // stores the type of consumption ELECTRICITY or GAS
    private $type;
    // stores the date from where the consumption needs to be taken from
    private $dateFrom;
    // the period upon which the consumption needed to be taken
    private $period;
    // stores the date to where the consumption needs to be taken till
    private $dateTo;
    // the curl client which passes the get requests to the URL
    private static $curl;

    // data array is variable for setting the different fields
    public function __construct(array $dataArray)
    {
        // assigns the time of the dateTo and dateFrom to dateTime objects with london time zones
        $this->dateFrom = new DateTime('now', new DateTimeZone('Europe/London'));
        $this->dateTo = new DateTime('now', new DateTimeZone('Europe/London'));
        // sets the date to 1 hour before the current date
        $this->dateFrom->modify("-1 hour");
        $this->setType($dataArray['type']);
        //$this->setDateFrom($dataArray['dateFrom']);
        //$this->setDateTo($dataArray['dateTo']);
        $this->setPeriod($dataArray['period']);
        // sets the values of the URL base with the appropriate parameters
        $this->urlBase = 'https://adhocapi.energyhive.com/hive/ac89ccdce8e878e227a93f050413c7d8/type/'. $this->type .
            '/?units=kWh&from='.
            $this->dateFrom->format('Y-m-d').'T'. $this->dateFrom->format('H:i:s').
            '&to=' . $this->dateTo->format('Y-m-d') .
            'T' . $this->dateTo->format('H:i:s').'&offset=-0&period='.
            $this->period .'&function=sum';
        // initialises the curl client only if the client is not set
        if (self::$curl == null) {
            self::setCurl();
        }
        // sets the operations of the curl client
        // RETURN TRANSFER is used to store the output instead of directly displaying it
        // URL is for setting the url of the curl client to the base url
        // HEADER is for adding headers to the url of the client, it can only accept the data as an array
        // HTTP HEADERS is for removing headers from the display output
        curl_setopt_array(self::$curl,
            array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->urlBase,
                CURLOPT_HTTPHEADER => $this->setAuthentication(), CURLOPT_HEADER => 0));
    }

    // sets the api key and value
    private function setAuthentication() {
        $apiKey[] = 'X-Auth-Token:78c4da90ba4a93adc4f50e89427caef9';
        return $apiKey;
    }

    private static function setCurl() {
        self::$curl = curl_init();
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @return mixed
     */
    public static function getCurl()
    {
        return self::$curl;
    }

    /**
     * @return mixed
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @return mixed
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @return string
     */
    public function getUrlBase()
    {
        return $this->urlBase;
    }

    /**
     * @param mixed $dateFrom
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = new DateTime($dateFrom);
    }

    /**
     * @param mixed $dateTo
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = new DateTime($dateTo);
    }

    /**
     * @param mixed $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    // gets the data stored by the curl client
    // the data is received as a jason string and given as an input to a consumption data set object
    // this object stores an array of consumption data
    public function getData() {
        return new ConsumptionDataSet(curl_exec(self::$curl));
    }


}