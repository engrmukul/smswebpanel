<?php


namespace App\Classes;


class SmsCount
{
    public function isUnicode($text)
    {
        if (strlen($text) != strlen(utf8_decode($text)))
        {
            return true;
        }

        return false;
    }

    public function isGsm7bit($text)
    {
        $gsm7bitChars = "~^{}[]\|€";
        $textlen = mb_strlen($text);
        for ($i = 0; $i < $textlen; $i++) {
            if (((strpos($gsm7bitChars, $text[$i]) !== false && $text[$i] != '\\')) || $text[$i] == '\\') {
                return true;
            } //strpos not able to detect \ in string
        }
        return false;
    }


    public function countSms($text)
    {
        $TotalSegment = 0;
        $textlen = mb_strlen($text);
        if ($textlen == 0) return false; //I can see most mobile devices will not allow you to send empty sms, with this check we make sure we don't allow empty SMS

        if ($this->isUnicode($text)) {
            $SingleMax = 70;
            $ConcatMax = 67;
            $smsType = 'unicode';
        } else if ($this->isGsm7bit($text)) { //7-bit
            $SingleMax = 140;
            $ConcatMax = 134;
            $smsType = 'gsm_extend';
        } else {
             //UCS-2 Encoding (16-bit)
            $SingleMax = 160;
            $ConcatMax = 153;
            $smsType = 'plain';
        }


        if ($textlen <= $SingleMax) {
            $TotalSegment = 1;
        } else {
            $TotalSegment = ceil($textlen / $ConcatMax);
        }

        $data = [
            'smsType'   => $smsType,
            'count'     => $TotalSegment
        ];

        return (object)$data;
    }
}
