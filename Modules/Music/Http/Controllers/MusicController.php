<?php

namespace Modules\Music\Http\Controllers;

use Alaouy\Youtube\Facades\Youtube;
use App\Http\Controllers\AppBaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spotify;
use Vqhteam\Ytdownload\YTDownload;

class MusicController extends AppBaseController
{
    public function search(Request $request)
    {

        $request->validate([
            'track' => 'required',
        ]);

        $limit = isset($request->limit) ? $request->limit : 10;
        $offset = isset($request->offset) ? $request->offset : 10;

        $tracks = Spotify::searchTracks($request->track)->limit($limit)->offset($offset)->get();

        $dataOutput = [];
        if (count($tracks['tracks']['items']) > 0) {
            foreach ($tracks['tracks']['items'] as $key => $value) {
                $dataOutput[$key]['artists'] = $value['artists'][0]['name'];
                $dataOutput[$key]['id'] = $value['id'];
                $dataOutput[$key]['duration_ms'] = $value['duration_ms'];
                $dataOutput[$key]['url'] = $value['external_urls']['spotify'];
                $dataOutput[$key]['name'] = $value['name'];
                $dataOutput[$key]['preview_url'] = $value['preview_url'];
                $dataOutput[$key]['uri'] = $value['uri'];
                $dataOutput[$key]['thumbnail'] = $value['album']['images'][0]['url'];
            }
        }
        return $this->responseAPI(true, '', $dataOutput, 200);
    }

    public function trackDetail(Request $request)
    {
        $request->validate([
            'track_id' => 'required',
        ]);

        $track = Spotify::track($request->track_id)->get();
        return $this->responseAPI(true, '', $track, 200);
    }

    public function searchVideo(Request $request)
    {
        $request->validate([
            'video' => 'required',
        ]);
        $results = Youtube::search($request->video);
        return $this->responseAPI(true, '', $results, 200);
    }

    public function getPopularVideos()
    {
        $videoList = Youtube::getPopularVideos('us');
        return $this->responseAPI(true, '', $videoList, 200);
    }

    public function downloadVideo(Request $request)
    {
        $request->validate([
            'video_id' => 'required',
        ]);
        try {
            $video = YTDownload::getLink($request->video_id);
            return $this->responseAPI(true, '', $video, 200);
        }catch (\Exception $e){
            return $this->responseAPI(false, '', null, 400);
        }
    }
}
