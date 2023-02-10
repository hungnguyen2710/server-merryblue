<?php

namespace Modules\Music\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spotify;

class MusicController extends AppBaseController
{
    public function search(Request $request){

      $test = Spotify::audioAnalysisForTrack('5dGczTAZ6SZvtU2d2LioyC')->get();
      return $test;
    }
}
