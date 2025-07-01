@vite('resources/css/app.css')

<div class="justify-center items-center mt-20" x-data="{ open: false }">
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


<div x-data="{
username: 'sohag'
}">
    Username: <strong x-text="username" ></strong>
</div>


<div x-data="{
    message: '',
}">

    <input type="text" x-model="message" class="bg-red-400">
    <span x-text="message"></span>
</div>

<div x-data="{ message: '' }">
    <input type="text" x-model="message">
    <button x-on:click="message = 'changed'">Change Message</button>
</div>


<div x-data="{ number: 5, numbers: 10 }">
    <div x-data="{ count: 0 }" x-modelable="count" x-model="numbers">
        <button @click="count++">Increment</button>
    </div>
 
    Number: <span x-text="number"></span>
</div>

<!-- transition directive -->
 <div x-data="{ open: false }">
    <button @click="open = ! open">Toggle</button>
 
    <div x-show="open" x-transition.delay.500ms x-transition.duration.500ms >
        Hello 👋
    </div>
</div>


<!-- x-effect -->

<div x-data="{ label: 'Hello' }" x-effect="console.log(label)">
    <button @click="label += ' World!'">Change Message</button>
</div>


<body>
    <div x-data="{ open: false }">
        <button @click="open = ! open">Toggle Modal</button>
 
        <template x-teleport="div">
            <div x-show="open">
                <div x-show="open">this si isis siis kkdk </div>
            <div x-show="open">
                Modal contents... yes this
            </div>
            </div>
            
        </template>
    </div>

    <div>Some other content placed AFTER the modal markup.</div>
    <div>this is next</div>


    <div id="modal-root">this is modal root </div>

    <!-- Teleported content -->
    <template x-teleport="#modal-root">
    <div class="modal">This is a modal</div>
    </template>

     
    <!-- magic functions -->

    <button class="bg-red-400" @click="$el.innerHTML = 'Hello World!'">Replace me with "Hello World!"</button>

    <div x-data="{ open: false }" x-init="$watch('open', value => console.log(value))">
        <button @click="open = ! open">Toggle Open</button>
    </div>



    </body>




<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>