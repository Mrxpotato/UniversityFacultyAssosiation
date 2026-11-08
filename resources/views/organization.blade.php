<x-public-layout title="Organization Chart">
    <style>
        /* Expand/collapse transitions for campus charts */
        .campus-chart {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding-top: 30px;
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 5;
            min-width: 400px;
            max-width: calc(100vw - 32px);
        }
        .campus-chart.expanded {
            opacity: 1;
            max-height: 1500px;
        }
        .campus-chart .node {
            opacity: 0;
            transform: scale(0.8) translateY(-20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .campus-chart.expanded .node {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        .campus-chart .level:nth-child(2) .node { transition-delay: 0.1s; }
        .campus-chart .level:nth-child(3) .node { transition-delay: 0.2s; }
        .campus-chart .level:nth-child(4) .node { transition-delay: 0.3s; }

        /* Expandable node plus/minus indicator */
        .node-card.expandable { position: relative; }
        .node-card.expandable::after {
            content: '+';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .node-card.expandable.active::after {
            content: '−';
            transform: translateX(-50%) rotate(180deg);
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        @media (max-width: 480px) {
            .campus-chart {
                min-width: unset;
                width: calc(100vw - 32px);
            }
        }
    </style>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 py-10 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <span class="material-icons text-4xl sm:text-6xl text-primary-300">groups</span>
            <h1 class="mt-4 text-2xl sm:text-4xl font-bold">Organization Chart</h1>
            <p class="mt-2 text-base sm:text-xl text-primary-200">Cagayan State University Faculty Association</p>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="overflow-x: hidden;">
        <div class="flex flex-col items-center">

            <!-- University President -->
            @php $president = $universityOfficers->firstWhere('officer_position', 'President'); @endphp
            <div class="relative flex flex-col items-center">
                @if($president)
                <div class="node-card expandable active:scale-105 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl material-shadow p-6 sm:p-8 text-center cursor-pointer hover:shadow-lg transition-all min-w-[200px] sm:min-w-[240px] after:bg-primary-500 after:text-white"
                     data-campus-id="{{ $president->campus_id }}">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/20 rounded-full mx-auto mb-3 flex items-center justify-center text-2xl sm:text-3xl font-bold text-white shadow-lg overflow-hidden">
                        @if($president->id_picture)
                            <img src="{{ Storage::url($president->id_picture) }}" alt="{{ $president->full_name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($president->first_name, 0, 1) . substr($president->surname, 0, 1)) }}
                        @endif
                    </div>
                    <div class="text-white font-bold text-base sm:text-lg">{{ $president->full_name }}</div>
                    <div class="text-primary-200 text-xs sm:text-sm font-medium mt-1">University FA President</div>
                    <div class="text-white/60 text-xs mt-1 italic">{{ $president->campus?->name }}</div>
                </div>
                <!-- Campus expansion for President -->
                <div class="campus-chart" id="campus-chart-president">
                    @php 
                        $presidentCampusOfficers = $campusOfficers->get($president->campus_id) ?? collect();
                        $presidentCampusOfficers = $presidentCampusOfficers->reject(fn($o) => $o->id === $president->id);
                    @endphp
                    <div class="text-center mb-5">
                        <span class="inline-block px-5 py-2 bg-primary-100 text-primary-800 rounded-full text-xs sm:text-sm font-semibold uppercase tracking-wide">{{ $president->campus?->name }} Chapter</span>
                    </div>
                    @if($presidentCampusOfficers->count() > 0)
                        <div class="level flex flex-wrap justify-center gap-3">
                            @foreach($presidentCampusOfficers as $officer)
                            <div class="node bg-white rounded-xl material-shadow p-4 text-center min-w-[140px]">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full mx-auto mb-2 flex items-center justify-center text-sm font-bold text-white overflow-hidden">
                                    @if($officer->id_picture)
                                        <img src="{{ Storage::url($officer->id_picture) }}" alt="{{ $officer->full_name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($officer->first_name, 0, 1) . substr($officer->surname, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="text-gray-900 font-semibold text-sm">{{ $officer->full_name }}</div>
                                <div class="text-primary-600 text-xs font-medium mt-1">{{ str_replace('Campus ', '', $officer->campus_position) }}</div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-400 italic py-4">No other campus officers assigned</p>
                    @endif
                </div>
                @else
                <div class="bg-white rounded-xl material-shadow p-6 sm:p-8 text-center min-w-[200px] sm:min-w-[240px]">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-200 rounded-full mx-auto mb-3 flex items-center justify-center text-2xl sm:text-3xl font-bold text-gray-400">?</div>
                    <div class="text-gray-400 font-semibold italic">Vacant</div>
                    <div class="text-primary-600 text-xs sm:text-sm font-medium mt-1">University FA President</div>
                </div>
                @endif
            </div>

            <!-- Connector -->
            <div class="w-0.5 h-10 bg-gradient-to-b from-primary-400 to-primary-600 rounded-full my-2"></div>

            <!-- University Officers Row -->
            <div class="flex flex-wrap justify-center gap-4 sm:gap-5 mt-2">
                @php 
                    $officerPositions = ['Vice President', 'Secretary', 'Treasurer', 'Auditor', 'Assistant Treasurer', 'Business Manager', 'PIO'];
                @endphp
                @foreach($officerPositions as $position)
                    @php $officer = $universityOfficers->firstWhere('officer_position', $position); @endphp
                    <div class="relative flex flex-col items-center">
                        <div class="node-card {{ $officer && $officer->campus_id ? 'expandable' : '' }} bg-white rounded-xl material-shadow p-4 sm:p-5 text-center cursor-pointer hover:shadow-lg transition-all min-w-[140px] sm:min-w-[170px] after:bg-primary-500 after:text-white"
                             data-campus-id="{{ $officer?->campus_id }}"
                             data-officer-id="{{ $officer?->id }}">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full mx-auto mb-2 flex items-center justify-center text-lg sm:text-xl font-bold text-white shadow-md overflow-hidden">
                                @if($officer && $officer->id_picture)
                                    <img src="{{ Storage::url($officer->id_picture) }}" alt="{{ $officer->full_name }}" class="w-full h-full object-cover">
                                @elseif($officer)
                                    {{ strtoupper(substr($officer->first_name, 0, 1) . substr($officer->surname, 0, 1)) }}
                                @else
                                    ?
                                @endif
                            </div>
                            <div class="font-semibold text-sm {{ !$officer ? 'text-gray-400 italic' : 'text-gray-900' }}">{{ $officer ? $officer->full_name : 'Vacant' }}</div>
                            <div class="text-primary-600 text-xs font-medium mt-1">{{ $position }}</div>
                            @if($officer && $officer->campus)
                                <div class="text-gray-400 text-xs mt-1 italic">{{ $officer->campus->name }}</div>
                            @endif
                        </div>
                        @if($officer && $officer->campus_id)
                        <div class="campus-chart" id="campus-chart-{{ $officer->id }}">
                            @php 
                                $officerCampusOfficers = $campusOfficers->get($officer->campus_id) ?? collect();
                                $officerCampusOfficers = $officerCampusOfficers->reject(fn($o) => $o->id === $officer->id);
                            @endphp
                            <div class="text-center mb-5">
                                <span class="inline-block px-5 py-2 bg-primary-100 text-primary-800 rounded-full text-xs sm:text-sm font-semibold uppercase tracking-wide">{{ $officer->campus->name }} Chapter</span>
                            </div>
                            @if($officerCampusOfficers->count() > 0)
                                <div class="level flex flex-wrap justify-center gap-3">
                                    @foreach($officerCampusOfficers as $campusOfficer)
                                    <div class="node bg-white rounded-xl material-shadow p-4 text-center min-w-[140px]">
                                        <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full mx-auto mb-2 flex items-center justify-center text-sm font-bold text-white overflow-hidden">
                                            @if($campusOfficer->id_picture)
                                                <img src="{{ Storage::url($campusOfficer->id_picture) }}" alt="{{ $campusOfficer->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($campusOfficer->first_name, 0, 1) . substr($campusOfficer->surname, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="text-gray-900 font-semibold text-sm">{{ $campusOfficer->full_name }}</div>
                                        <div class="text-primary-600 text-xs font-medium mt-1">{{ str_replace('Campus ', '', $campusOfficer->campus_position) }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-gray-400 italic py-4">No other campus officers assigned</p>
                            @endif
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <p class="text-center text-gray-400 text-sm mt-16 animate-pulse">Click on any <span class="text-primary-600 font-semibold">University Officer</span> to view their campus chapter</p>
        </div>
    </div>

    <script>
        let currentlyExpanded = null;

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.node-card.expandable').forEach(node => {
                node.addEventListener('click', handleOfficerClick);
            });
        });

        function handleOfficerClick(event) {
            const clickedNode = event.currentTarget;
            const officerId = clickedNode.dataset.officerId;
            const isPresident = clickedNode.closest('.relative')?.querySelector('#campus-chart-president') !== null;
            const chartId = isPresident ? 'campus-chart-president' : `campus-chart-${officerId}`;
            const chartElement = document.getElementById(chartId);

            if (!chartElement) return;

            const expandedId = isPresident ? 'president' : officerId;

            if (currentlyExpanded === expandedId) {
                collapseChart(clickedNode, chartElement);
                currentlyExpanded = null;
                return;
            }

            // Collapse previously expanded
            if (currentlyExpanded) {
                const prevChartId = currentlyExpanded === 'president' ? 'campus-chart-president' : `campus-chart-${currentlyExpanded}`;
                const prevChart = document.getElementById(prevChartId);
                const prevNode = currentlyExpanded === 'president' 
                    ? document.querySelector('.node-card.expandable[data-campus-id]') 
                    : document.querySelector(`[data-officer-id="${currentlyExpanded}"]`);
                if (prevNode && prevChart) {
                    collapseChart(prevNode, prevChart);
                }
            }

            expandChart(clickedNode, chartElement);
            currentlyExpanded = expandedId;

            setTimeout(() => {
                clickedNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }

        function expandChart(node, chart) {
            node.classList.add('active');
            chart.classList.add('expanded');
        }

        function collapseChart(node, chart) {
            node.classList.remove('active');
            chart.classList.remove('expanded');
        }
    </script>
</x-public-layout>
