<?php
class Qrcode
{
    private $apiUrl = 'https://chart.apis.google.com/chart';
    private $data;
    // Function which is used to generate the URL type of QR Code.
    public function URL($url = null)
    {
        $this->data = preg_match("#^https?\:\/\/#", $url) ? $url : "https://{$url}";
    }
    public function QRCODE($size = 400, $filename = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "chs={$size}x{$size}&amp;cht=qr&amp;chl=" . urlencode($this->data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $img = curl_exec($ch);
        curl_close($ch);
        if ($img) {
            if ($filename) {
                if (!preg_match("#\.png$#i", $filename)) {
                    $filename .= ".png";
                }
                return file_put_contents($filename, $img);
            } else {
                header("Content-type: image/png");
                print $img;
                return true;
            }
        }
        return false;
    }
}
