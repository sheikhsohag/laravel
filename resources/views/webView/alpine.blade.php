@vite('resources/css/app.css')

<div class="flex justify-center items-center mt-20" x-data="{ open: false }">
    <button class="btn bg-amber-400 p-2 rounded cursor-pointer hover:bg-amber-500" @click="open = !open "> Toggle Content </button>
    <div x-show="open">
        alpine content
    </div>  
</div>

<div class="text-3xl" x-data="{foo:'bar'}">
    <span x-text="foo"></span>
    
    <div x-data="{bar: 'baz'}">
        <span x-text="bar"></span>
        <div x-data="{foo: 'bob'}">
            <span x-text="foo" x-text="bar" ></span>
            <span x-text="bar"></span>
        </div>
    </div>

</div>

<div x-data="{
   open: false,
   toggle(){
   console.log('sohag');
    this.open = !this.open;
   }
}">


<div x-data>
    <span x-init="console.log('I can initialize')"></span>
</div>
 
<span x-init="console.log('I can initialize too')"></span>

<div x-data="{
    init() {
        console.log('I am called automatically')
    }
}">

</div>

<button class="bg-green-400 p-2 rounded hover:bg-green-500 cursor-pointer"  @click="toggle()">Toggle button</button>
<div x-show="open">this is toggle content</div>
</div>


<div x-data="{
    open: false,
    toggle(){
        console.log('clicked here', open);
        this.open= !this.open;
    }
}">

<button class="btn bg-amber-500 mt-10 p-2 rounded ml-3 hover:bg-amber-600" @click="toggle()">show content</button>


<div x-show="open" x-cloak >this is hiden content! Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet voluptate consequuntur ducimus obcaecati. Aspernatur illo labore saepe. Dolorem, facere fugiat!</div>
</div>

<div x-data="{ placeholder: 'Type here...' }">
    <input type="text" x-bind:placeholder="placeholder">
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>