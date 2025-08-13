@guest('customer')
    <div class="grid gap-2.5">
        <p class="font-dmserif text-xl">
            @lang('shop::app.components.layouts.header.desktop.bottom.welcome-guest')
        </p>

        <p class="text-sm">
            @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
        </p>
    </div>

    <p class="mt-3 w-full border border-zinc-200"></p>

    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.before') !!}

    <div class="mt-6 flex gap-4">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before') !!}

        <a
            href="{{ route('shop.customer.session.create') }}"
            class="primary-button m-0 mx-auto block w-max rounded-2xl px-7 text-center text-base max-md:rounded-lg ltr:ml-0 rtl:mr-0"
        >
            @lang('shop::app.components.layouts.header.desktop.bottom.sign-in')
        </a>

        <a
            href="{{ route('shop.customers.register.index') }}"
            class="secondary-button m-0 mx-auto block w-max rounded-2xl border-2 px-7 text-center text-base max-md:rounded-lg max-md:py-3 ltr:ml-0 rtl:mr-0"
        >
            @lang('shop::app.components.layouts.header.desktop.bottom.sign-up')
        </a>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up_button.after') !!}
    </div>

    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.after') !!}
@endguest

@auth('customer')
    <div class="grid gap-2.5 p-5 pb-0">
        <p class="font-dmserif text-xl">
            @lang('shop::app.components.layouts.header.desktop.bottom.welcome')’
            {{ auth()->guard('customer')->user()->first_name }}
        </p>

        <p class="text-sm">
            @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
        </p>
    </div>

    <p class="mt-3 w-full border border-zinc-200"></p>

    <div class="mt-2.5 grid gap-1 pb-2.5">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before') !!}

        <a
            class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
            href="{{ route('shop.customers.account.profile.index') }}"
        >
            @lang('shop::app.components.layouts.header.desktop.bottom.profile')
        </a>

        <a
            class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
            href="{{ route('shop.customers.account.orders.index') }}"
        >
            @lang('shop::app.components.layouts.header.desktop.bottom.orders')
        </a>

        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
            <a
                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                href="{{ route('shop.customers.account.wishlist.index') }}"
            >
                @lang('shop::app.components.layouts.header.desktop.bottom.wishlist')
            </a>
        @endif

        <!--Customers logout-->
       @auth('customer')
            <form
                method="POST"
                action="{{ route('shop.customer.session.destroy') }}"
                id="customerLogout"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                >
                    @lang('shop::app.components.layouts.header.desktop.bottom.logout')
                </button>
            </form>
        @endauth

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.after') !!}
    </div>
@endauth
