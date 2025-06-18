@vite('resources/css/app.css')

<div class="flex justify-center items-center mt-20" x-data="{ open: false }">
    <button class="btn bg-amber-400 p-2 rounded cursor-pointer hover:bg-amber-500" @click="open = !open "> Toggle Content </button>
    <div x-show="open">
        alpine content
    </div>  
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>