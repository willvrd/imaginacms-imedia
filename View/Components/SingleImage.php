<?php

namespace Modules\Media\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

use Modules\Setting\Entities\Setting;

class SingleImage extends Component
{
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public $src;
  public $zone;
  public $alt;
  public $title;
  public $fallbackExtension;
  public $url;
  public $extraLargeSrc;
  public $fallback;
  public $largeSrc;
  public $mediumSrc;
  public $smallSrc;
  public $imgClasses;
  public $linkClasses;
  public $linkRel;
  public $defaultLinkClasses;
  public $imgStyles;
  public $width;
  public $dataFancybox;
  public $dataCaption;
  public $target;
  public $isVideo;
  public $mediaFiles;
  public $uid;
  public $dataTarget;
  public $dataSlideTo;
  public $autoplayVideo;
  public $mutedVideo;
  public $loopVideo;
  public $withVideoControls;


  public function __construct($src = '', $alt = '', $title = null, $url = null, $isMedia = false, $mediaFiles = null,
                              $zone = 'mainimage', $extraLargeSrc = null, $largeSrc = null, $mediumSrc = null,
                              $smallSrc = null, $fallback = null, $imgClasses = '', $linkClasses = '', $linkRel = '',
                              $defaultLinkClasses = 'image-link w-100', $imgStyles = '', $width = "300px",
                              $dataFancybox = null, $dataTarget = null, $dataSlideTo = null, $dataCaption = null,
                              $target = "_self", $setting = '', $autoplayVideo = false, $loopVideo = true,
                              $mutedVideo = true, $central = false, $withVideoControls = false)
  {
    $this->src = $src;
    $this->alt = !empty($alt) ? $alt : $mediaFiles->{$zone}->alt ?? $mediaFiles->alt ?? "";
    $this->title = $title;
    $this->url = $url;
    $this->imgClasses = $imgClasses;
    $this->linkClasses = $linkClasses;
    $this->defaultLinkClasses = $defaultLinkClasses;
    $this->imgStyles = $imgStyles;
    $this->linkRel = $linkRel;
    $this->width = $width;
    $this->dataFancybox = $dataFancybox;
    $this->dataCaption = $dataCaption;
    $this->dataTarget = $dataTarget;
    $this->dataSlideTo = $dataSlideTo;
    $this->target = $target;
    $this->uid = Str::uuid();
    $this->autoplayVideo = $autoplayVideo;
    $this->loopVideo = $loopVideo;
    $this->mutedVideo = $mutedVideo;
    $this->withVideoControls = $withVideoControls;
    if (!empty($setting)) {

      // Old
      //$settingRepository = app("Modules\Setting\Repositories\SettingRepository");
      //$setting = $settingRepository->findByName($setting);
      
      // New
      $setting = Setting::where("name", $setting);

      if($central)
        $setting->withoutTenancy()->whereNull("organization_id");

      $setting= $setting->with('files')->first();
     
      if (isset($setting->id)) {
        $isMedia = true;
        $zone = "setting::mainimage";
        $mediaFiles = $setting->mediaFiles();
      }

    }

    if (!empty($fallback)) {
      $this->fallbackExtension = pathinfo($fallback, PATHINFO_EXTENSION);
      if ($this->fallbackExtension == "jpg") $this->fallbackExtension = "jpeg";
    }


    if ($isMedia && !empty($mediaFiles)) {
      $this->mediaFiles = $mediaFiles;
      $this->zone = $zone ?? "mainimage";
      $this->src = $mediaFiles->{$zone}->extraLargeThumb ?? $mediaFiles->extraLargeThumb;
      $this->fallback = $mediaFiles->{$zone}->path ?? $mediaFiles->path;
      $this->extraLargeSrc = $mediaFiles->{$zone}->extraLargeThumb ?? $mediaFiles->extraLargeThumb;
      $this->largeSrc = $mediaFiles->{$zone}->largeThumb ?? $mediaFiles->largeThumb;
      $this->mediumSrc = $mediaFiles->{$zone}->mediumThumb ?? $mediaFiles->mediumThumb;
      $this->smallSrc = $mediaFiles->{$zone}->smallThumb ?? $mediaFiles->smallThumb;
      $this->isVideo = $mediaFiles->{$zone}->isVideo ?? $mediaFiles->isVideo ?? false;

    } else {
      $this->extraLargeSrc = $extraLargeSrc;
      $this->largeSrc = $largeSrc;
      $this->mediumSrc = $mediumSrc;
      $this->smallSrc = $smallSrc;
      $this->fallback = $fallback ?? $src;
    }

  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\View\View|string
   */
  public function render()
  {
    return view('media::frontend.components.singleimage');
  }
}
