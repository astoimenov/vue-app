<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" media="screen" title="no title" charset="utf-8">

    <style media="screen">
        #app {
            margin-top: 20px;
        }
    </style>

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'stripeKey' => config('services.stripe.key'),
            'user' => auth()->user() ?? ['email' => ''],
        ]); ?>
    </script>

    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script src="{{ mix('js/app.js') }}" charset="utf-8"></script>
</head>

<body>
    <header class="header">
        <nav class="container nav has-shadow">
            <div class="nav-left">
                <a class="nav-item is-tab {{ request()->route()->getName() === 'home.index' ? ' is-active' : '' }}"
                    href="{{ url('/') }}">Home</a>
                <a class="nav-item is-tab{{ request()->route()->getName() === 'plans.index' ? ' is-active' : '' }}"
                    href="{{ route('plans.index') }}">Plans</a>
                <a class="nav-item is-tab {{ request()->route()->getName() === 'products.index' ? ' is-active' : '' }}"
                    href="{{ route('products.index') }}">Products</a>
                <a class="nav-item is-tab {{ request()->route()->getName() === 'projects.create' ? ' is-active' : '' }}"
                    href="{{ route('projects.create') }}">Projects</a>
            </div>

            <div class="nav-right">
                @if (!Auth::check())
                    <a class="nav-item is-tab {{ request()->route()->getName() === 'login' ? ' is-active' : '' }}"
                        href="{{ route('login') }}">Login</a>
                    <a class="nav-item is-tab {{ request()->route()->getName() === 'register' ? ' is-active' : '' }}"
                        href="{{ route('register') }}">Register</a>
                @else
                    <form id="logout-form" action="{{ route('logout') }}" method="post">
                        {{ csrf_field() }}
                        <a class="nav-item" href="javascript:{}"
                            onclick="document.getElementById('logout-form').submit(); return false;">Logout</a>
                    </form>
                @endif
            </div>
        </nav>
    </header>

    <main class="container" id="app">
        @yield('content')
    </main>

    <script type="text/javascript">
        new Vue({
            el: '#app'
        });
    </script>
</body>

</html>
