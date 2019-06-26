<?php

namespace DNADesign\Elemental\Controllers;

use SilverStripe\Control\Director;
use SilverStripe\View\Requirements;
use DNADesign\Elemental\Controllers\ElementController;

class ResponsiveTableController extends ElementController
{
    public function init()
    {
        parent::init();

        if ($this->isLiveReload()) {
            Requirements::javascript(sprintf('http://localhost:%s/livereload.js', $this->config()->live_reload_port));
        } else {
            Requirements::css('dnadesign/silverstripe-elemental-responsivetable: client/dist/responsivetable.css');
        }

        Requirements::javascript('dnadesign/silverstripe-elemental-responsivetable: client/dist/responsivetable.js');
    }

    public function isLiveReload()
    {
        return Director::isDev() && @fsockopen('localhost', $this->config()->live_reload_port, $errno, $errstr, 1);
    }
}
