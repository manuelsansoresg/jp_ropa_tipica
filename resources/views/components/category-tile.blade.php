@props(['category'])
<a href="{{ route('collections.show', $category->slug) }}" class="group relative block aspect-[4/5] overflow-hidden bg-ink image-reveal">
    <img src="{{ $category->image }}" alt="Colección de {{ strtolower($category->name) }}" loading="lazy" class="h-full w-full object-cover transition duration-1000 ease-out group-hover:scale-[1.035]" width="700" height="875">
    <span class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-transparent"></span>
    <span class="absolute bottom-0 left-0 right-0 p-6 text-white md:p-8"><span class="font-display text-4xl md:text-5xl">{{ $category->name }}</span><span class="mt-2 block translate-y-2 text-[10px] font-semibold uppercase tracking-[.18em] opacity-0 transition duration-500 group-hover:translate-y-0 group-hover:opacity-100">Ver colección →</span></span>
</a>
