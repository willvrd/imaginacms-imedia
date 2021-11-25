<?php

namespace Modules\Media\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Gallery extends Component
{
  public $id;
  public $zones;
  public $mediaFiles;
  public $margin;
  public $responsiveClass;
  public $autoplay;
  public $autoplayHoverPause;
  public $loop;
  public $dots;
  public $nav;
  public $responsive;
  public $gallery;
  public $dataFancybox;
  public $view;
  public $columnMasonry;
  public $navText;
  public $maxImages;


  /**
   * Create a new component instance.
   *
   * @return void
   */

  public function __construct($id = "gallery", $zones = ["gallery"], $mediaFiles, $margin = 10, $responsiveClass = true,
                              $autoplay = true, $autoplayHoverPause = true, $loop = true, $dots = true, $nav = true,
                              $responsive = null, $dataFancybox = 'gallery', $layout = "gallery-layout-1",
                              $columnMasonry = 3, $navText = "", $maxImages = null, $onlyVideos = false,
                              $onlyImages = false)
  {
    $this->id = $id;
    $this->view = "media::frontend.components.gallery.layouts.$layout.index";
    $this->zones = $zones;
    $this->mediaFiles = $mediaFiles;
    $this->margin = $margin;
    $this->responsiveClass = $responsiveClass;
    $this->autoplay = $autoplay;
    $this->autoplayHoverPause = $autoplayHoverPause;
    $this->loop = $loop;
    $this->dots = $dots;
    $this->nav = $nav;
    $this->responsive = json_encode($responsive ?? [0 => ["items" => 1], 640 => ["items" => 2], 992 => ["items" => 4]]);
    $this->dataFancybox = $dataFancybox.Str::uuid();
    $this->gallery = [];
    $this->columnMasonry = $columnMasonry;
    $this->navText = json_encode($navText);
    $this->maxImages = $maxImages;


    if(!empty($mediaFiles)){
      $countImages = 0;
      foreach ($zones as $zone){
        !is_array($mediaFiles->{$zone}) ? $mediaFiles->{$zone} = [$mediaFiles->{$zone}] : false;
          foreach ($mediaFiles->{$zone} as $itemImage){
            if(empty($maxImages) || $countImages<$maxImages){
              if(($onlyImages && $itemImage->isImage) || ($onlyVideos && $itemImage->isVideo) || (!$onlyVideos && !$onlyImages)){
                $countImages++;
                array_push($this->gallery,$itemImage);
              }
              
            }
          }
        
      }
    }
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\View\View|string
   */
  public
  function render()
  {
    return view($this->view);
  }
}


