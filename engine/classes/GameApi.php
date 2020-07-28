<?php

class GameApi
{

    /**
     * @param $url
     * @param $params
     * @param bool $post
     * @return mixed
     */
    public function sendGameRequest($url, $params, $post = false)
    {
        $merchantKey = '039e6e2795f027f8027e0ab1a600a1e9bad3fa45';
        $merchantId = '7b2ab9eae9d6576d1a289e8ca7848ee6';
        $baseUrl = 'https://staging.slotegrator.com/api/index.php/v1';
        $timestamp = time();
        $xNonce = md5(uniqid(mt_rand(), true));

        $headers = [
            'X-Merchant-Id' => $merchantId,
            'X-Timestamp' => $timestamp,
            'X-Nonce' => $xNonce,
        ];

        $mergedParams = array_merge($params, $headers);
        ksort($mergedParams);
        $hashString = http_build_query($mergedParams);
        $sign = hash_hmac('sha1', $hashString, $merchantKey);

        unset($headers);
        $headers = [
            "X-Merchant-Id: {$merchantId}",
            "X-Timestamp: {$timestamp}",
            "X-Nonce: {$xNonce}",
            "X-Sign: {$sign}",
        ];

        $ch = curl_init($baseUrl . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $data = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($data, true);
        return $data;
    }
}