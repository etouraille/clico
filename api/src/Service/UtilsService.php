<?php


namespace App\Service;


use Psr\Log\LoggerInterface;

class UtilsService
{

    public static function mapFromTo($from, $to, $map ) {
        foreach ($map as $fromString => $toString ) {
            $tabFrom = explode('.', $fromString);
            $tabTo = explode('.', $toString);


            $currentGet = $from;
            foreach($tabFrom as $elem) {
                $getter = 'get' . ucfirst($elem);
                $currentGet = $currentGet->$getter();

            }
            $currentTo = $to;
            foreach($tabTo as $index => $elem) {

                $getter = 'get' . ucfirst($elem);
                $setter = 'set' . ucfirst($elem);
                if($index + 1 < count($tabTo)) {
                    $class = $currentTo->$getter();
                    if(!$class) {
                        $class = '\\App\\Model\\' . ucfirst($elem);
                        $currentTo->$setter(new $class());
                    }
                    $currentTo = $currentTo->$getter();
                } else {
                    $currentTo->$setter($currentGet);
                }
            }
        }
        return $to;
    }
}
