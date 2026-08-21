<?php

namespace Webkul\Varnish\Listeners;

use Webkul\Core\Contracts\Channel;
use Webkul\Theme\Contracts\Section;
use Webkul\Theme\Repositories\SectionRepository;
use Webkul\Varnish\Facades\VarnishCache;

class Appearance
{
    /**
     * Types the layout draws on every page rather than the home page alone.
     *
     * @var array
     */
    public const LAYOUT_TYPES = ['footer_links', 'services_content'];

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected SectionRepository $sectionRepository) {}

    /**
     * After section create.
     *
     * @param  Section  $section
     * @return void
     */
    public function afterCreate($section)
    {
        $this->forget($section?->type);
    }

    /**
     * After section update.
     *
     * @param  Section  $section
     * @return void
     */
    public function afterUpdate($section)
    {
        $this->forget($section?->type);
    }

    /**
     * Before section delete.
     *
     * @param  int  $sectionId
     * @return void
     */
    public function beforeDelete($sectionId)
    {
        $this->forget($this->sectionRepository->find($sectionId)?->type);
    }

    /**
     * After a channel is switched to another theme, or otherwise changed.
     *
     * @param  Channel  $channel
     * @return void
     */
    public function afterChannelUpdate($channel)
    {
        $this->flush();
    }

    /**
     * Drop the pages the given section type is rendered on.
     *
     * The home page carries the sections it is built from, while the footer and the service
     * promises are drawn by the layout and so reach every page.
     */
    protected function forget(?string $type): void
    {
        if (in_array($type, self::LAYOUT_TYPES, true)) {
            $this->flush();

            return;
        }

        $this->once('home', fn () => VarnishCache::forget('/'));
    }

    /**
     * Drop everything Varnish is holding.
     */
    protected function flush(): void
    {
        $this->once('all', fn () => VarnishCache::flush());
    }

    /**
     * Send a purge unless this request has already sent it.
     *
     * Publishing settles a theme's sections together and raises the event once per section,
     * which would otherwise repeat the same ban as many times over.
     */
    protected function once(string $key, callable $purge): void
    {
        $key = 'varnish.purged.'.$key;

        if (
            app()->bound($key)
            || app()->bound('varnish.purged.all')
        ) {
            return;
        }

        app()->instance($key, true);

        $purge();
    }
}
