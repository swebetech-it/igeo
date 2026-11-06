<!-- Page Header Component -->
@props(['title', 'subtitle' => null, 'breadcrumbs' => [], 'actions' => null])

<div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-xl border border-slate-200/50 p-8 mb-8">
    <!-- Breadcrumbs -->
    @if(!empty($breadcrumbs))
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            @foreach($breadcrumbs as $index => $breadcrumb)
                <li class="inline-flex items-center">
                    @if($index > 0)
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                    @endif

                    @if(isset($breadcrumb['url']))
                        <a href="{{ $breadcrumb['url'] }}"
                           class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200">
                            @if(isset($breadcrumb['icon']))
                                <i class="{{ $breadcrumb['icon'] }} mr-2"></i>
                            @endif
                            {{ $breadcrumb['label'] }}
                        </a>
                    @else
                        <span class="inline-flex items-center text-sm font-medium text-slate-700">
                            @if(isset($breadcrumb['icon']))
                                <i class="{{ $breadcrumb['icon'] }} mr-2"></i>
                            @endif
                            {{ $breadcrumb['label'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1">
            <h1 class="text-4xl font-black bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-2">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-lg text-slate-600 font-medium">{{ $subtitle }}</p>
            @endif
        </div>

        @if($actions)
        <div class="mt-6 sm:mt-0 sm:ml-6 flex items-center space-x-3">
            {{ $actions }}
        </div>
        @endif
    </div>

    <!-- Additional Content Slot -->
    @if(isset($slot) && !empty(trim($slot)))
    <div class="mt-6 pt-6 border-t border-slate-200/50">
        {{ $slot }}
    </div>
    @endif
</div>
