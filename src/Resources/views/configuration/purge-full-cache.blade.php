<x-admin::form.control-group>
    <x-admin::form.control-group.label>
        @lang('varnish::app.configuration.fpc.cache_management.purge_full_cache.title')
    </x-admin::form.control-group.label>
 

    @if (bouncer()->hasPermission('configuration'))    
         <form
            method="post"
            action="{{ route('varnish.configuration.full.cache.purge') }}"
            ref="full-purge"
        >
            @csrf                                     

            <div class="flex items-center gap-x-2.5">
                <button
                    type="button"
                    class="primary-button"                      
                    @click.prevent="$emitter.emit('open-confirm-modal', {
                        message: '@lang('varnish::app.configuration.fpc.cache_management.purge_full_cache.confirmation')',
                        agree: () => {
                            const form = $refs['full-purge'];
                            if (form.checkValidity()) {
                                form.submit();
                            } else {
                                form.reportValidity(); // Shows native validation error messages
                            }
                        }
                    })"
                >
                    @lang('varnish::app.configuration.fpc.cache_management.purge_full_cache.btn')
                </button>
            </div>
        </form>
    @endif
    <x-admin::form.control-group.error control-name="name" />
    <p class="mt-2 block text-xs italic leading-5 text-gray-600 dark:text-gray-300">
        @lang('varnish::app.configuration.fpc.cache_management.purge_full_cache.info')
    </p>
</x-admin::form.control-group>
 