@props(['title', 'value', 'subtitle' => ''])

<div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
    <p class="text-sm text-gray-500 font-medium">{{ $title }}</p>
    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
    @if ($subtitle)
    <p class="text-xs text-gray-400 mt-1">{{ $subtitle }}</p>
    @endif
</div>