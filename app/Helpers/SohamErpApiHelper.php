<?php

namespace App\Helpers;

class SohamErpApiHelper
{
    // Call API Using cURL
    public static function cURL($url, $data = [], $method = 'POST')
    {
        $headers  = [
            'Accept: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response     = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($statusCode == 200) {
            $response = @json_decode($response, true);
            return !empty($response['result']) ? $response['result'] : [];
        }
        return [];
    }

    // Get Category Using cURL API
    public static function getAllCategory()
    {
        return SohamErpApiHelper::cURL('http://vlpl.lrdevteam.com/api/getallcategories', [
            "perPage" => 10,
            "filter" => "",
            "sort" => "ASC"
        ]);
    }
}
