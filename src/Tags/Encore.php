<?php

namespace Innocenzi\LaravelEncore\Tags;

use Innocenzi\LaravelEncore\Facade\Encore as Facade;
use Statamic\Tags\Tags;

class Encore extends Tags
{
    public function scripts()
    {
        return Facade::getScriptsTags($this->get('entry'), $this->get('nodefer'));
    }

    public function link()
    {
        return Facade::getLinkTags($this->get('entry'));
    }
}
