<?php

namespace App\Http\Helper;

class StringHelper
{
    public static function randomString($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public static function randomNumber($length) {
        $key = '';
        $keys = array_merge(range(0, 9));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public static function randomCapitalString($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('A', 'Z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public static function getRandomHex($num_bytes=4) {
        $hex = bin2hex(openssl_random_pseudo_bytes($num_bytes/2));
        $addition_char = "";
        if($num_bytes%2 == 1){
            $char = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "a", "b", "c", "d", "e", "f"];
            $addition_char = $char[array_rand($char)];
        }

        return $hex . $addition_char;
    }
}
