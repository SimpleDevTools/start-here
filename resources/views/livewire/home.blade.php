<div
    class="flex h-screen flex-col items-center justify-center gap-y-5 bg-gray-100 px-10 text-center"
    x-data="{ quote: {} }"
    x-init="fetch('https://api.quotable.io/quotes/random?tags=inspirational')
        .then(response => response.json())
        .then(response => quote = response[0])"
>
    <h3
        class="block max-w-[500px] font-serif text-xl font-semibold text-gray-600"
        x-text="quote.content"
    ></h3>
    <p x-text="'- '+quote.author"></p>
</div>
