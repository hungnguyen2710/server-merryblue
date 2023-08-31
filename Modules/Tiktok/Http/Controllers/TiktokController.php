<?php

namespace Modules\Tiktok\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\TikTok;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;
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
        $request->validate([
            'url' => 'required'
        ]);

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-RapidAPI-Key' => '827caf23d9msh472c74dfb8aeec9p120081jsnfaf3217fdc3b',
                'X-RapidAPI-Host' => 'tiktok-downloader-download-tiktok-videos-without-watermark.p.rapidapi.com',
            ])
                ->get('https://tiktok-downloader-download-tiktok-videos-without-watermark.p.rapidapi.com/index?url=' . $request->url)->json();
            if ($response['message'] == 'You are not subscribed to this API.') {
                $responses = Http::withHeaders([
                    'Accept' => 'application/json',
                ])
                    ->post('https://alldownloader.merryblue.llc/api/v1/tiktok/download',
                        [
                            'url' => $request->url
                        ]
                    )->json();
                return $responses;
            } else {

                $dataOutput = [];
                $dataOutput['links'] = [];
                $uuid = Uuid::uuid4()->toString();
                $dataOutput['title'] = $uuid;
                $dataOutput['thumbnail'] = $response['cover'][0] ? $response['cover'][0] : '';
                $dataOutput['links'][] = [
                    "url" => $response['video'][0] ? $response['video'][0] : '',
                    "format" => 'hd',
                    "type" => 'video',
                    "size" => 'N/A',
                ];


                $dataOutput['time'] = '';
                $dataInput = [
                    'url' => $request->url,
                    'version' => 'v1',
                    'api' => 'crawl',
                    'status' => 1,
                ];
                TikTok::create($dataInput);
                return $this->responseAPI(true, '', $dataOutput, 200);
            }
        } catch (Exception $e) {

            $responses = Http::withHeaders([
                'Accept' => 'application/json',
            ])
                ->post('https://alldownloader.merryblue.llc/api/v1/tiktok/download',
                    [
                        'url' => $request->url
                    ]
                )->json();
            return $responses;
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
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
            CURLOPT_ENCODING => "utf-8",
            CURLOPT_AUTOREFERER => false,
            CURLOPT_COOKIEJAR => 'cookie.txt',
            CURLOPT_COOKIEFILE => 'cookie.txt',
            CURLOPT_REFERER => 'www.tiktok.com',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_MAXREDIRS => 10,
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

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
        error_reporting(E_ALL);
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

    public function downloadV2(Request $request)
    {
        $headers = [
            'Host: www.tiktok.com',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36',
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $request->url, ['headers' => $headers, 'http_errors' => false]);
        $JsonDecode = json_decode(Str::between($response->getBody()->getContents(), "window['SIGI_STATE']=", ";window['SIGI_RETRY']="), true);
        return $JsonDecode();
    }

    public function listLog(Request $request)
    {
        $toDate = $request->toDate;
        $fromDate = $request->fromDate;
        $ins = TikTok::orderBy('created_at', 'DESC')
            ->when(isset($request->key), function ($q) use ($request) {
                $q->where('url', 'LIKE', '%' . $request->key . '%');
            })
            ->when(($toDate && $fromDate), function ($q) use ($toDate, $fromDate) {
                $q->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $toDate))
                    ->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $fromDate));
            })
            ->paginate(100);
        return $this->responseAPI(true, 'success', $ins, 200);
    }

    public function chartByToday(Request $request)
    {
        $toDate = $request->toDate;
        $fromDate = $request->fromDate;
        $results = [];
        $results['success'] = TikTok::where('status', 1)
            ->when(isset($request->key), function ($q) use ($request) {
                $q->where('url', 'LIKE', '%' . $request->key . '%');
            })
            ->when(($toDate && $fromDate), function ($q) use ($toDate, $fromDate) {
                $q->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $toDate))
                    ->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $fromDate));
            })
            ->when(($toDate == '' || $fromDate == ''), function ($q) use ($toDate, $fromDate) {
                $q->whereDate('created_at', Carbon::today());
            })
            ->count();
        $results['fail'] = TikTok::where('status', 0)->when(($toDate && $fromDate), function ($q) use ($toDate, $fromDate) {
            $q->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $toDate))
                ->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $fromDate));
        })
            ->when(isset($request->key), function ($q) use ($request) {
                $q->where('url', 'LIKE', '%' . $request->key . '%');
            })
            ->when(($toDate == '' || $fromDate == ''), function ($q) use ($toDate, $fromDate) {
                $q->whereDate('created_at', Carbon::today());
            })
            ->count();
        return $this->responseAPI(true, 'success', $results, 200);
    }

    public function reqByMonth(Request $request)
    {
        $now = Carbon::now();

        $response = [];
        $response['crawl'] = TikTok::where('api', 'crawl')->whereMonth('created_at', '=', $now->month)->count();
        $response['public'] = TikTok::where('api', 'public')->whereMonth('created_at', '=', $now->month)->count();

        return $this->responseAPI(true, 'success', $response, 200);
    }
}
