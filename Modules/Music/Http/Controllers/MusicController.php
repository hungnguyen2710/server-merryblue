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
    public function search(Request $request){

      $video = YTDownload::getLink("rie-hPVJ7Sw");
      return $video;
    }
}
