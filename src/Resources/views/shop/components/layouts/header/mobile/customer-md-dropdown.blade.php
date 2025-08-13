<!-- Guest Dropdown -->
@php
    $showWishlist = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
@endphp
@guest('customer')
    <div class="grid gap-2.5">
        <p class="font-dmserif text-xl">
            @lang('shop::app.components.layouts.header.mobile.welcome-guest')
        </p>

        <p class="text-sm">
            @lang('shop::app.components.layouts.header.mobile.dropdown-text')
        </p>
    </div>

    <p class="mt-3 w-full border border-zinc-200"></p>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.before') !!}

    <div class="mt-6 flex gap-4">
        {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.before') !!}

        <a
            href="{{ route('shop.customer.session.create') }}"
            class="m-0 mx-auto block w-max cursor-pointer rounded-2xl bg-navyBlue px-7 py-4 text-center text-base font-medium text-white ltr:ml-0 rtl:mr-0"
        >
            @lang('shop::app.components.layouts.header.mobile.sign-in')
        </a>

        <a
            href="{{ route('shop.customers.register.index') }}"
            class="m-0 mx-auto block w-max cursor-pointer rounded-2xl border-2 border-navyBlue bg-white px-7 py-3.5 text-center text-base font-medium text-navyBlue ltr:ml-0 rtl:mr-0"
        >
            @lang('shop::app.components.layouts.header.mobile.sign-up')
        </a>

        {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.after') !!}
    </div>
    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.after') !!}
@endguest

<!-- Customers Dropdown -->
@auth('customer')
    <div class="grid gap-2.5 p-5 pb-0">
        <p class="font-dmserif text-xl">
            @lang('shop::app.components.layouts.header.mobile.welcome')’
            {{ auth()->guard('customer')->user()->first_name }}
        </p>

        <p class="text-sm">
            @lang('shop::app.components.layouts.header.mobile.dropdown-text')
        </p>
    </div>

    <p class="mt-3 w-full border border-zinc-200"></p>

    <div class="mt-2.5 grid gap-1 pb-2.5">
        {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.before') !!}

        <a
            class="cursor-pointer px-5 py-2 text-base"
            href="{{ route('shop.customers.account.profile.index') }}"
        >
            @lang('shop::app.components.layouts.header.mobile.profile')
        </a>

        <a
            class="cursor-pointer px-5 py-2 text-base"
            href="{{ route('shop.customers.account.orders.index') }}"
        >
            @lang('shop::app.components.layouts.header.mobile.orders')
        </a>

        @if ($showWishlist)
            <a
                class="cursor-pointer px-5 py-2 text-base"
                href="{{ route('shop.customers.account.wishlist.index') }}"
            >
                @lang('shop::app.components.layouts.header.mobile.wishlist')
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
                    class="cursor-pointer px-5 py-2 text-base"
                >
                    @lang('shop::app.components.layouts.header.mobile.logout')
                </button>
            </form>
        @endauth
        {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.after') !!}
    </div>
@endauth
