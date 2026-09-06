@php
    $links = [
        'github' => 'fab fa-github',
        'linkedin' => 'fab fa-linkedin',
        'twitter' => 'fab fa-twitter',
        'instagram' => 'fab fa-instagram',
    ];
@endphp

<div class="{{ $wrapper ?? 'flex space-x-4' }}">
    @foreach($links as $key => $icon)
        @if($url = ($socials[$key] ?? null))
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
               class="{{ $classes ?? '' }}" aria-label="{{ ucfirst($key) }}">
                <i class="{{ $icon }} text-xl" aria-hidden="true"></i>
            </a>
        @endif
    @endforeach
</div>
