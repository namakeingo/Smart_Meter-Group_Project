<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 13/01/2019
 * Time: 17:25
 */
// imports the k nn class to be used
use Phpml\Classification\KNearestNeighbors;
require_once __DIR__ . '/../vendor/autoload.php';
require_once ('Reader.php');
class Prediction
{
    private static $classifier;
    private $samples;
    private $labels;

    public function __construct()
    {
        if(self::$classifier == null) {
            self::setClassifier();
        }
        // training data
        $this->samples = [];
        // classes for the training data
        $this->labels = [];
    }

    /**
     * @return KNearestNeighbors
     */
    public static function getClassifier()
    {
        return self::$classifier;
    }

    public static function setClassifier()
    {
        self::$classifier = new KNearestNeighbors();
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @return array
     */
    public function getSamples()
    {
        return $this->samples;
    }

    public function setSamples($dataSet, $type) {
        $this->samples = [];
        foreach ($dataSet as $value) {
            if ($type == 'multiple') {
                $this->samples[] = [$value[0], $value[1], $value[2]];
            }
            elseif ($type = 'single') {
                $this->samples[] = $value;
            }
        }
    }

    public function setLabels($dataSet) {
        $this->labels = [];
        foreach ($dataSet as $value) {
            $this->labels[] = $value;
        }
    }

    public function train($type) {
        // reader handle reads the training data from both the file
        $readerHandle = new Reader('weatherData.txt');
        $readerHandle->readData('multiple');
        $this->setSamples($readerHandle->getData(), 'multiple');
        if($type == 'Elec') {
            // consumption data is only for electricity
            $readerHandle->setLines('consumptionElec.txt');
        }
        elseif ($type == 'Gas') {
            // consumption data is only for gas
            $readerHandle->setLines('consumptionGas.txt');
        }
        $readerHandle->readData('single');
        $this->setLabels($readerHandle->getData());
        self::$classifier->train($this->samples, $this->labels);
    }

    public function predict($testArray) {
        return self::$classifier->predict($testArray);
    }

}