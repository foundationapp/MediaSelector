# MediaSelector Power-Up

This Power-Up will allow users to select an emoji or an image from a media selector.

[]

After you enable this power-up in your application you can render the media selector using:

```
<livewire:powerup.media-selector />
```

You may want to trigger the select to open when a user clicks a button. You can easily do this with the help of Alpine:

```
<div x-data="{ open: false }"  class="relative" @click.outside="open=false" @close-project-favicon-selector.window="open=false">
    <p @click="open=!open" class="flex items-center justify-center w-10 h-10 rounded cursor-pointer bg-neutral-100">👍</p>
    <div x-show="open" class="absolute bottom-0 left-0 z-20 pt-1 mt-12 ml-6 -translate-x-1/2 translate-y-full" x-cloak>
        <livewire:powerup.media-selector />
    </div>
</div>
```

You may want to add some animation to the media-selector, simple enough you can add the following transition attributes, like so:

```
<div x-data="{ open: false }"  class="relative" @click.outside="open=false" @close-project-favicon-selector.window="open=false">
    <p @click="open=!open" class="flex items-center justify-center w-10 h-10 rounded cursor-pointer bg-neutral-100">👍</p>
    <div x-show="open" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="absolute bottom-0 left-0 z-20 pt-1 mt-12 ml-6 -translate-x-1/2 translate-y-full" x-cloak>
        <livewire:powerup.media-selector />
    </div>
</div>
```





