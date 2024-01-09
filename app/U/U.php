<?php

namespace App\U;


class U
{
    public static function save($func, $message)
    {
        try {
            return $func();
        } catch (\Exception $e) {
            self::myerror($e->__toString());
            throw new \Exception($message);
        }
    }
    public static function myerror($message)
    {
        \Log::channel("myerror")->info($message);
    }

    public static function toAssoc($rows, $clmid = "id", $clmname = "name")
    {
        // 連想配列に変換
        $ret = [];
        foreach ($rows as $row) {
            $ret[$row[$clmid]] = $row[$clmname];
        }
        return $ret;
    }

    public static function query2array($q)
    {
        $ret = $q->get()->map(function ($item) {
            return (array)$item;
        })->all();
        return $ret;
    }

    public static function getd($array, $key, $def)
    {
        $arr = (array)$array;
        $ret = array_key_exists($key, $arr) ? $arr[$key] : $def;
        return $ret;
    }

    public static function vald($val, $def)
    {
        $ret = $val ? $val : $def;
        return $ret;
    }

    public static function nullToEmptystring(array $arr): array
    {
        $ret = [];
        foreach ($arr as $key => $val) {
            $ret[$key] = $val === null ? "" : $val;
        }
        return $ret;
    }

    public static function publicfiletimelink($filepath)
    {
        return asset($filepath) . '?v=' . filemtime(join(DIRECTORY_SEPARATOR, [public_path(), $filepath]));
    }

    public static function whereIsNotEmpty($q, $clm, $val)
    {
        if (strlen($val) > 0) {
            $q->where($clm, "=", $val);
        }
        return $q;
    }
    public static function whereWithAll($q, $clm, $val)
    {
        if ($val != \App\L\ZzzLabel::ID_ALL) {
            $q->where($clm, "=", $val);
        }
        return $q;
    }

    public static function rndstr($len): string
    {
        return substr(bin2hex(random_bytes($len)), 0, $len);
    }

    public static function toSql(\Illuminate\Database\Eloquent\Builder $q)
    {
        return vsprintf(
            str_replace('?', '%s', $q->toSql()),
            collect($q->getBindings())->map(function ($binding) {
                return is_numeric($binding) ? $binding : "'{$binding}'";
            })->toArray()
        );
        // return preg_replace_array('/\?/', $q->getBindings(), $q->toSql());
    }

    public static function assoc_search($array, $column, $value)
    {
        return $array[array_search($value, array_column($array, $column))];
    }
}
