<html>
    <head>
        <title>Exchange</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    </head>
    <body>
        <nav class="navbar navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="{{ route('exchange.index') }}">
                    Exchange
                </a>
            </div>
        </nav>
        <div class="container" style="padding-top:50px;">
            {{ $slot }}
        </div>
    </body>
</html>
