@if (bouncer()->hasPermission('configuration'))
    <div class="mt-3.5">
        <form
            method="post"
            action="{{ route('varnish.configuration.cache.purge') }}"
            ref="purge-via-url"
        >
            @csrf

            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('varnish::app.configuration.varnish.purge_cache.via_url.title')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="textarea"
                    name="purge_url"
                    rules="required"
                    :value="old('purge_url')"
                    :label="trans('varnish::app.configuration.varnish.purge_cache.via_url.title')"
                    :placeholder="trans('varnish::app.configuration.varnish.purge_cache.via_url.placeholder')"
                />

                <x-admin::form.control-group.error control-name="purge_url" />

                <p class="mt-2 block text-xs italic leading-5 text-gray-600 dark:text-gray-300">
                    @lang('varnish::app.configuration.varnish.purge_cache.via_url.info')
                </p>
            </x-admin::form.control-group>

            <div class="flex items-center gap-x-2.5">
                <button
                    type="button"
                    class="secondary-button"
                    @click.prevent="$emitter.emit('open-confirm-modal', {
                        message: '@lang('varnish::app.configuration.varnish.purge_cache.via_url.confirmation')',
                        agree: () => {
                            const form = $refs['purge-via-url'];

                            form.checkValidity() ? form.submit() : form.reportValidity();
                        }
                    })"
                >
                    @lang('varnish::app.configuration.varnish.purge_cache.via_url.btn')
                </button>
            </div>
        </form>
    </div>
@endif
