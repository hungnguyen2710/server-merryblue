<?php

namespace Modules\Tiktok\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpClient\HttpClient;


class TiktokController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {


        $headers = [
            'sec-fetch-user' => '?1',
            'sec-ch-ua-mobile' => '?0',
            'sec-fetch-site' => 'none',
            'sec-fetch-dest' => 'document',
            'sec-fetch-mode' => 'navigate',
            'cache-control' => 'max-age=0',
            'authority' => 'https://www.tiktok.com/',
            'upgrade-insecure-requests' => '1',
            'accept-language' => 'en-GB,en;q=0.9,tr-TR;q=0.8,tr;q=0.7,en-US;q=0.6',
            'sec-ch-ua' => '"Google Chrome";v="89", "Chromium";v="89", ";Not A Brand";v="99"',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36',
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'cookie' => 'sb=Rn8BYQvCEb2fpMQZjsd6L382; datr=Rn8BYbyhXgw9RlOvmsosmVNT; c_user=100003164630629; _fbp=fb.1.1629876126997.444699739; wd=1920x939; spin=r.1004812505_b.trunk_t.1638730393_s.1_v.2_; xs=28%3A8ROnP0aeVF8XcQ%3A2%3A1627488145%3A-1%3A4916%3A%3AAcWIuSjPy2mlTPuZAeA2wWzHzEDuumXI89jH8a_QIV8; fr=0jQw7hcrFdas2ZeyT.AWVpRNl_4noCEs_hb8kaZahs-jA.BhrQqa.3E.AAA.0.0.BhrQqa.AWUu879ZtCw',
        ];

        $redirectCheckClient = HttpClient::create([
            'headers' => $headers,
        ]);

        $client = HttpClient::create([
            'headers' => $headers,
        ]);
        $response = $client->request('GET', $request->url);
        $datas = $response->getContent();



        $resp = $datas;
        //echo "$resp";
        $check = explode('"downloadAddr":"', $resp);
        if (count($check) > 1) {
            $contentURL = explode("\"", $check[1])[0];
            $contentURL = str_replace("\\u0026", "&", $contentURL);
            $contentURL = str_replace("\\u002F", "/", $contentURL);
            $thumb = explode("\"", explode('og:image" content="', $resp)[1])[0];
            $username = explode('"', explode('"uniqueId":"', $resp)[1])[0];
            $create_time = explode('"', explode('"createTime":"', $resp)[1])[0];
            $dt = new DateTime("@$create_time");
            $create_time = $dt->format("d M Y H:i:s A");
            $videoKey = $this->getKey($contentURL);
            $cleanVideo = "https://api2-16-h2.musical.ly/aweme/v1/play/?video_id=$videoKey&vr_type=0&is_play_url=1&source=PackSourceEnum_PUBLISH&media_type=4";
            $cleanVideo = $this->getContent($cleanVideo, true);
            $link = $this->downloadVideo($contentURL);
            $dataOutput = [];
            $dataOutput['links'] = [];
            $uuid = Uuid::uuid4()->toString();
            $dataOutput['title'] = $username . $uuid;
            $dataOutput['thumbnail'] = $thumb;
            $dataOutput['links'][] = [
                "url" => config('app.url'). 'storage/'. $link,
                "format" => 'hd',
                "type" => 'video',
                "size" => 'N/A',
            ];


            $dataOutput['time'] = '';
            return $this->responseAPI(true, '', $dataOutput, 200);
        }
    }


    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getContent($url, $geturl = false)
    {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Mobile Safari/537.36',
            CURLOPT_ENCODING => "utf-8",
            CURLOPT_AUTOREFERER => false,
            CURLOPT_COOKIEJAR => 'cookie.txt',
            CURLOPT_COOKIEFILE => 'cookie.txt',
            CURLOPT_REFERER => 'https://www.tiktok.com/',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_MAXREDIRS => 10,
        );
        curl_setopt_array($ch, $options);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($geturl === true) {
            return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        }
        curl_close($ch);
        return strval($data);
    }

    public function getKey($playable)
    {
        $ch = curl_init();
        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: en-US,en;q=0.9',
            'Range: bytes=0-200000'
        ];

        $options = array(
            CURLOPT_URL => $playable,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
            CURLOPT_ENCODING => "utf-8",
            CURLOPT_AUTOREFERER => false,
            CURLOPT_COOKIEJAR => 'cookie.txt',
            CURLOPT_COOKIEFILE => 'cookie.txt',
            CURLOPT_REFERER => 'https://www.tiktok.com/',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_MAXREDIRS => 10,
        );
        curl_setopt_array($ch, $options);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $tmp = explode("vid:", $data);
        if (count($tmp) > 1) {
            $key = trim(explode("%", $tmp[1])[0]);
        } else {
            $key = "";
        }
        return $key;
    }

    public function downloadVideo($video_url, $geturl = false)
    {
        $ch = curl_init();
        $headers = array(
            'Range: bytes=0-',
        );
        $options = array(
            CURLOPT_URL => $video_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_USERAGENT => 'okhttp',
            CURLOPT_ENCODING => "utf-8",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_COOKIEJAR => 'cookie.txt',
            CURLOPT_COOKIEFILE => 'cookie.txt',
            CURLOPT_REFERER => 'https://www.tiktok.com/',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_MAXREDIRS => 10,
        );
        curl_setopt_array($ch, $options);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($geturl === true) {
            return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        }
        curl_close($ch);
        $filename = "tiktok/" . $this->generateRandomString() . ".mp4";

        Storage::disk('public')->put($filename, $data);
        return $filename;
    }
}
