<?php

namespace App\helper;

class TikTok
{
    public $url;
    private $useragent = '';

    public function __construct($url = null)
    {
        $this->url = $url;
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }

    private function getVideoWithOutWatermark($url)
    {
        $binary = file_get_contents($url);
        preg_match_all('/vid:(.+?)\%/', $binary, $matches);
        return 'https://api2.musical.ly/aweme/v1/playwm/?video_id=' . $matches[1][0];
    }

    private function getVideoWithWatermark()
    {
        $html = $this->get($this->url);
        preg_match_all('/{"props"(.+?)<\/script>/', $html, $matches);

        if (sizeof($matches[1]) == 0) {
            return false;
        }

        $data = '{"props"' . $matches[1][0];
        $data = json_decode($data, true);

        // full params: https://pastebin.com/9fi0QRPf
        $res['user']['verified'] = $data['props']['pageProps']['videoData']['authorInfos']['verified'];
        $res['user']['username'] = $data['props']['pageProps']['videoData']['authorInfos']['uniqueId'];
        $res['user']['name'] = $data['props']['pageProps']['videoData']['authorInfos']['nickName'];
        $res['user']['avatar'] = $data['props']['pageProps']['videoData']['authorInfos']['covers'][0];

        $res['user']['stats']['followers'] = $data['props']['pageProps']['videoData']['authorStats']['followerCount'];
        $res['user']['stats']['likes'] = $data['props']['pageProps']['videoData']['authorStats']['heartCount'];

        $res['music']['title'] = $data['props']['pageProps']['videoData']['musicInfos']['musicName'];
        $res['music']['author'] = $data['props']['pageProps']['videoData']['musicInfos']['authorName'];
        $res['music']['cover'] = $data['props']['pageProps']['videoData']['musicInfos']['covers'][0];
        $res['music']['page'] = $data['props']['pageProps']['videoObjectPageProps']['videoProps']['audio']['mainEntityOfPage']['@id'];

        // Previously, the link was accessible by the key playUrl,
        // now it has been removed and done differently.
        // I have neither the time nor the desire to figure out
        // how to get the link now. If you want, you can make a Pull Request.
        // $res['music']['link'] = $this->getAudioLink($res['music']['page']);
        $res['music']['link'] = null;

        $res['video']['cover'] = $data['props']['pageProps']['videoData']['itemInfos']['covers'][0];
        $res['video']['links']['raw'] = $data['props']['pageProps']['videoData']['itemInfos']['video']['urls'][0];
        $res['video']['meta'] = $data['props']['pageProps']['videoData']['itemInfos']['video']['videoMeta'];
        $res['video']['text'] = $data['props']['pageProps']['videoData']['itemInfos']['text'];

        return $res;
    }

    private function getAudioLink($url)
    {
        $html = $this->get($url);
        preg_match_all('/{"props"(.+?)<\/script>/', $html, $matches);
        $data = '{"props"' . $matches[1][0];
        $data = json_decode($data, true);

        return $data['props']['pageProps']['musicInfo']['music']['playUrl'];
    }

    public function getData()
    {
        if ($this->url == '') {
            return false;
        }

        $res = $this->getVideoWithWatermark();

        if (!$res) {
            return false;
        }

        if ($res['video']['links']['raw'] == '') {
            return false;
        }

        $res['video']['links']['clean'] = preg_replace('/[\x00-\x1F\x7F]/u', '', $this->getVideoWithOutWatermark($res['video']['links']['raw']));

        return $res;
    }

    public function setUseragent($useragent = false)
    {
        if (!$useragent) {
            return;
        }

        $this->useragent = $useragent;

        return $this;
    }

    private function get($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_USERAGENT => $this->useragent,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
