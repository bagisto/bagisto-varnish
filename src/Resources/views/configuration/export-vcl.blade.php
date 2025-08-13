<div id="varnish-vcl-export" style="display: block;">
    <div class="flex items-center gap-x-2.5">
        <a
            href="{{ route('varnish.configuration.vcl.export') }}"
            class="transparent-button bg-gray-100 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
        >
            <span class="icon-admin-export text-xl text-gray-600"></span>
            @lang('varnish::app.configuration.fpc.configuration.fpc.cache_application.varnish.export_vcl.title')
        </a>
    </div>
    <p class="mt-2 block text-xs italic leading-5 text-gray-600 dark:text-gray-300">
        @lang('varnish::app.configuration.fpc.configuration.fpc.cache_application.varnish.export_vcl.info')
    </p>
</div>