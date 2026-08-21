@if (bouncer()->hasPermission('configuration'))
    <x-admin::form.control-group>
        <x-admin::form.control-group.label>
            @lang('varnish::app.configuration.varnish.purge_full_cache.title')
        </x-admin::form.control-group.label>

        <form
            method="post"
            action="{{ route('varnish.configuration.full.cache.purge') }}"
            ref="full-purge"
        >
            @csrf

            <div class="flex items-center gap-x-2.5">
                <button
                    type="button"
                    class="secondary-button"
                    @click.prevent="$emitter.emit('open-confirm-modal', {
                        message: '@lang('varnish::app.configuration.varnish.purge_full_cache.confirmation')',
                        agree: () => $refs['full-purge'].submit()
                    })"
                >
                    @lang('varnish::app.configuration.varnish.purge_full_cache.btn')
                </button>
            </div>
        </form>

        <p class="mt-2 block text-xs italic leading-5 text-gray-600 dark:text-gray-300">
            @lang('varnish::app.configuration.varnish.purge_full_cache.info')
        </p>
    </x-admin::form.control-group>
@endif
