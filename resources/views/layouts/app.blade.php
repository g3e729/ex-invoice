<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Invoice') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.datepicker').datepicker({
                language: 'en',
                format: 'yyyy-mm-dd'
            });
        });
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Invoice') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                Hi {{ auth()->user()->name }}!</span>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="row justify-content-center {{ ! auth()->user() ? "d-none" : "" }}">
                <div class="col-md-2">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item {{ request()->is('invoices') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('invoices.index') }}">Home</a>
                                </li>
                                <li class="nav-item {{ request()->is('invoices/create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('invoices.create') }}">Create</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            </div>
            @yield('content')
        </main>
    </div>

    <script type="text/javascript">
        function copyElement(item, container) {
            let type = container == '#cart' ? '.product' : '.payment';
            let type2 = container == '#cart' ? 'products' : 'payments';
            let items = $(type).length - 1;
            let productItem = $($(item).clone()[0]);

            productItem.removeClass('d-none');
            productItem.removeAttr('id');
            let inputs = productItem.find('.field');

            inputs.each(function (index, item) {
                let inpName = $(item).data('name');
                $(item).attr('name', type2 + '[' + items + '][' + inpName + ']');
            });

            $(container).append(productItem);
        }

        function compute() {
            let total = 0;
            let items = $(".product");

            items.each(function (index, item) {
                if (! $(item).hasClass('d-none')) {  
                    let price = $($(item).find("[data-price]")[0]).val();
                    let quantity = $($(item).find("[data-quantity]")[0]).val();
                    let tax = $($(item).find("[data-tax]")[0]).val();
                    let amount = price * quantity;
                    amount = (amount * (tax / 100)) + amount;

                    total = total + amount;
                }
            });

            $("#total span").text(total.toFixed(2));
            $("input[name='amount']").val(total.toFixed(2));
        }

        $(document).ready(function() {
            if ($('.product').length < 2) {
                copyElement("#product-holder", "#cart");
            } else {
                $('.product-select').each(function (index, item) {
                    if (! $(item).data('name')) {
                        getPrice(item);
                    }
                });
            }

            if ($('.payment').length < 2) {
                copyElement("#payment-holder", "#payment");
            }

            compute();
        });

        $("#add-product").on('click', function () {
            copyElement("#product-holder", "#cart");
        });

        $("#add-payment").on('click', function () {
            copyElement("#payment-holder", "#payment");
        });

        $('#delete').on('click', function () {
            if (confirm("Are you sure?")) {
                document.getElementById('delete-form').submit();
            }
        });

        function removeParent(e) {
            let parentDiv = $($(e).parent("div"));
            let type = parentDiv.hasClass("product") ? '.product' : '.payment';
            let items = $(type).length - 1;

            $(e).parent("div").remove();

            if (items < 2) {
                if (type == '.product') {
                    copyElement("#product-holder", "#cart");
                } else {
                    copyElement("#payment-holder", "#payment");
                }
            }

            compute();
        }

        function getPrice(e) {
            let productId = $(e).val();
            let price = $('#product-' + productId).val();
            $(e).next('input').val(price);
            compute();
        }
    </script>
</body>
</html>
