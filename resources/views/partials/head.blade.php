<meta charset="utf-8" />
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
/>

<title>{{ $title ?? 'Laravel' }}</title>

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
