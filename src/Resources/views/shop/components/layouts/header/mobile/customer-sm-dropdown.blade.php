@guest('customer')
    <a
        href="{{ route('shop.customer.session.create') }}"
        aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
    >
        <span class="icon-users cursor-pointer text-2xl"></span>
    </a>
@endguest

<!-- Customers Dropdown -->
@auth('customer')
    <a
        href="{{ route('shop.customers.account.index') }}"
        aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
    >
        <span class="icon-users cursor-pointer text-2xl"></span>
    </a>
@endauth
