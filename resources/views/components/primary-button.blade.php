<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 bg-[#334155] text-white font-medium rounded-md hover:bg-[#1E293B] focus:outline-none focus:ring-2 focus:ring-[#334155] transition']) }}>
    {{ $slot }}
</button>
