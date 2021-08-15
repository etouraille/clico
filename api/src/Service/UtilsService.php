<?php


namespace App\Service;


use Psr\Log\LoggerInterface;

class UtilsService
{

    private static $logger;

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

    // list des associations possible
    // a 1 i
    // a 1 j
    // a 2 i
    // a 2 j etc ...
    public static function associations($tabs = [[1, 2], ['a', 'b'], ['i', 'j']]): array {
        $keys = [];
        if(count($tabs) === 0 ) return [];
        self::recurse($tabs, 0, '', $keys);
        $ret = [];
        foreach($keys as $key) {
            $elements = array_map(function($elem) { return (int) $elem; }, explode('_', $key));
            $res = [];
            foreach($elements as $index => $key ) {
                $res[] = $tabs[$index][$key];
            }
            $ret[] = $res;
        }
        return $ret;
    }



    private static function recurse($tabs, $index, $key, &$ret) {
        if(isset($tabs[$index])) {
            foreach($tabs[$index] as $_key => $elem) {

                self::recurse($tabs, $index + 1, strlen($key) > 0 ? $key.'_'.$_key:''.$_key, $ret);
            }
        } else {
            $ret[] = $key;
        }
    }


    public static function combinaisonRecurse($debut,$tags,$profondeur, &$ret) {
        if($profondeur == 0) {
            array_push($ret,$debut);
            return;
        }
        $n = count($tags);
        $array = [];
        for($i=0;$i<$n;$i++) {
            $array[$i] = $debut;
        }
        for($i=0;$i<$n;$i++) {
            $array[$i][] = $tags[$i];
            self::combinaisonRecurse(
                $array[$i],
                array_slice($tags,$i+1),
                $profondeur-1,
                $ret
            );
        }
    }
    // Display all combinaison of a tab
    public static function combination($tab) {
        $res = [];
        for($i=1;$i<=count($tab);$i++) {
            self::combinaisonRecurse([], $tab,$i, $res);
        }
        return $res;

    }

    public static function permutation($tab) {
        $ret = [];
        return self::recurse_permutations($ret, $tab);
    }

    private static function recurse_permutations(&$list,$items,$perms = array( ))
    {

        if (empty($items)) {
            $list[] = $perms;
        } else {
            for ($i = count($items)-1;$i>=0;--$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                self::recurse_permutations($list, $newitems, $newperms);
            };
            return $list;
        };
    }
    // a contains b
    // tout les element de b sont contenus dans a.
    public static function contains($a, $b, &$comp = []) {
        $ret = true;
        foreach($b as $elem ) {
            if(false === array_search($elem, $a)) {
                $ret = false;
            }
        }
        foreach($a as $elem) {
            if(false === array_search($elem, $b)) {
                $comp[] = $elem;
            }
        }
        return $ret;
    }

    public static function setLogger($logger) {
        self::$logger = $logger;
    }

    public static function associationAndCombination($tab) {
        $ret = [];
        foreach(self::associations($tab) as $association) {
            foreach(self::combination($association) as $comb) {
                $ret[] = $comb;
            }
        }

        $res = array_unique($ret, SORT_REGULAR);
        return self::sort($res);
    }

    private static function sort($tab, $precise = false) {
        usort($tab, function($a,$b) use($precise) {
            if(count($a) < count($b)) return -1;
            if(count($a) > count($b)) return 1;
            elseif(count($a) === count($b)) {
                return $precise ? strcmp(implode('', $a), implode('', $b)) : 0;
            } else {
                return 0;
            }
        });
        return $tab;
    }
}
