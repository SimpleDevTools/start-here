<div
    class="flex flex-col justify-center items-center h-screen bg-gray-100 px-10 text-center gap-y-5"
    x-data="{ quote: {} }"
    x-init="
        fetch('https://api.quotable.io/quotes/random?tags=inspirational')
            .then(response => response.json())
            .then(response => quote = response[0])
    "
>
    <h3 class="text-xl font-semibold text-gray-600 block max-w-[500px] font-serif" x-text="quote.content"></h3>
    <p x-text="'- '+quote.author"></p>
</div>
