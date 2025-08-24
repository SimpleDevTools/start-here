<meta charset="utf-8" />
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
/>

<title>{{ $title ?? config('app.name') }}</title>

<link
    href="/favicon.ico"
    rel="icon"
    sizes="any"
>
<link
    type="image/svg+xml"
    href="/favicon.svg"
    rel="icon"
>
<link
    href="/apple-touch-icon.png"
    rel="apple-touch-icon"
>

<link
    href="https://fonts.bunny.net"
    rel="preconnect"
>
<link
    href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
    rel="stylesheet"
/>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
