<div class="replace-nav"></div>

@inject('menuUilts', 'App\Utils\MenuUtils')

<nav class="navigation">
    <ul class="nav-page justify-content-center">
        @foreach($menuUilts->build() as $mainItem)
            <li class="nav-item {{ $mainItem['active'] ? 'active' : '' }}">
                <a class="nav-link" href="{{ $mainItem['href'] }}">
                    <i class="nav-icon {{ $mainItem['icon'] }}"></i>
                    <span class="nav-title">{{ $mainItem['text'] }}</span>
                </a>
                @if (isset($mainItem['sub_menu']) && count($mainItem['sub_menu']) > 0)
                    <ul class="nav-page-1">
                        @foreach($mainItem['sub_menu'] as $level1Item)
                            <li class="nav-item {{ $level1Item['active'] ? 'active' : '' }}">
                                <a class="nav-link" href="{{ $level1Item['href'] }}">
                                    <i class="nav-icon {{ $level1Item['icon'] }}"></i>
                                    {{ $level1Item['text'] }}
                                </a>
                                @if (isset($level1Item['sub_menu']) && count($level1Item['sub_menu']) > 0)
                                    <ul class="nav-page-2">
                                        @foreach($level1Item['sub_menu'] as $level2Item)
                                            <li class="nav-item {{ $level2Item['active'] ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ $level2Item['href'] }}">
                                                    {{ $level2Item['text'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
​
        @endforeach
    </ul>
</nav>
