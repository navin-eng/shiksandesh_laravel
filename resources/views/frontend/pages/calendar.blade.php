@extends('frontend.layout.master')
@section('frontend-content')

@php
    $typeColors = [
        'holiday' => '#ef4444',
        'exam' => '#8b5cf6',
        'test' => '#f97316',
        'cca_eca' => '#06b6d4',
        'result' => '#eab308',
        'other' => '#10b981',
    ];
@endphp

<div class="page-hero" style="background: linear-gradient(135deg, var(--dark), var(--primary)); padding: 80px 0;">
    <div class="container">
        <div class="page-hero-content text-center text-white" data-aos="fade-up">
            <h1 style="font-family: var(--font-heading); font-weight: 900; font-size: 3rem;">Academic Calendar</h1>
            <p class="mt-3 opacity-75" style="font-size: 1.1rem;">Explore holidays, exams, and events throughout the academic year.</p>
        </div>
    </div>
</div>

<section class="section-block" style="padding: 80px 0; background: #f9fafb;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="calendar-shell">
                    {{-- Calendar Header --}}
                    <div class="calendar-header d-flex justify-content-between align-items-center mb-4">
                        <button id="prevMonthBtn" class="btn btn-outline-secondary rounded-circle" style="width: 45px; height: 45px;"><i class="fa-solid fa-chevron-left"></i></button>
                        <div class="text-center">
                            <h3 id="currentMonthYearBS" style="font-family: var(--font-heading); font-weight: 800; color: var(--dark); margin: 0;"></h3>
                            <span id="currentMonthYearAD" style="color: #6b7280; font-weight: 600; font-size: 14px;"></span>
                        </div>
                        <button id="nextMonthBtn" class="btn btn-outline-secondary rounded-circle" style="width: 45px; height: 45px;"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>

                    {{-- Calendar Grid --}}
                    <div class="calendar-grid">
                        <div class="calendar-day-header text-danger">Sun</div>
                        <div class="calendar-day-header">Mon</div>
                        <div class="calendar-day-header">Tue</div>
                        <div class="calendar-day-header">Wed</div>
                        <div class="calendar-day-header">Thu</div>
                        <div class="calendar-day-header">Fri</div>
                        <div class="calendar-day-header text-danger">Sat</div>
                        
                        <!-- Cells will be injected here via JS -->
                        <div id="calendarCells" class="calendar-cells-container"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="calendar-sidebar">
                    <h4 style="font-family: var(--font-heading); font-weight: 800; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">Calendar Guide</h4>
                    <div class="calendar-legend">
                        <div><span style="background:#ef4444;"></span> Holiday</div>
                        <div><span style="background:#8b5cf6;"></span> Exam</div>
                        <div><span style="background:#f97316;"></span> Test</div>
                        <div><span style="background:#06b6d4;"></span> CCA / ECA</div>
                        <div><span style="background:#eab308;"></span> Result</div>
                        <div><span style="background:#10b981;"></span> Other</div>
                    </div>
                    
                    <div class="mt-5">
                        <h4 style="font-family: var(--font-heading); font-weight: 800; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">Upcoming Events</h4>
                        @forelse($entries->sortBy('start_date')->take(6) as $entry)
                            <div class="calendar-item-card">
                                <div class="calendar-item-badge" style="background: {{ $typeColors[$entry->entry_type] ?? '#10b981' }};">
                                    {{ $entry->entry_type_label }}
                                </div>
                                <h6 style="font-weight: 700; color: var(--dark); margin: 5px 0;">{{ $entry->title }}</h6>
                                <p class="mb-0" style="font-size: 13px; color: #6b7280; font-weight: 600;">
                                    <i class="fa-regular fa-calendar me-1"></i>
                                    {{ format_system_date($entry->start_date) }}
                                    @if($entry->end_date && $entry->end_date !== $entry->start_date)
                                        - {{ format_system_date($entry->end_date) }}
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-muted">No upcoming events.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .calendar-shell, .calendar-sidebar {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.03);
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .calendar-cells-container {
            display: contents; /* Allows children to participate in the parent grid */
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            color: #4b5563;
            padding: 10px 0;
            border-bottom: 2px solid #f3f4f6;
            margin-bottom: 10px;
        }

        .calendar-cell {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            min-height: 100px;
            padding: 10px;
            position: relative;
            display: flex;
            flex-direction: column;
            transition: all 0.2s ease;
        }

        .calendar-cell:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(13, 122, 62, 0.1);
        }

        .calendar-cell.empty {
            background: #f9fafb;
            border: 1px dashed #e5e7eb;
            pointer-events: none;
        }

        .calendar-cell.today {
            background: rgba(13, 122, 62, 0.05);
            border-color: var(--primary);
        }

        .calendar-cell.holiday {
            background: rgba(239, 68, 68, 0.05);
        }

        .bs-date {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
        }

        .ad-date {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
        }
        
        .cell-events {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .event-pill {
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            padding: 3px 6px;
            border-radius: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .calendar-legend {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .calendar-legend div {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
        }
        .calendar-legend span {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            display: inline-block;
        }

        .calendar-item-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            border: 1px solid #f3f4f6;
            transition: transform 0.2s ease;
        }
        .calendar-item-card:hover {
            transform: translateX(5px);
            border-color: #e5e7eb;
        }
        .calendar-item-badge {
            color: #fff;
            display: inline-block;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        @media (max-width: 991px) {
            .calendar-cell { min-height: 80px; padding: 5px; }
            .bs-date { font-size: 18px; }
            .ad-date { font-size: 9px; top: 4px; right: 4px; }
            .event-pill { font-size: 9px; padding: 2px 4px; }
        }
        @media (max-width: 576px) {
            .calendar-grid { gap: 5px; }
            .calendar-day-header { font-size: 11px; }
            .calendar-cell { min-height: 60px; }
            .event-pill { display: none; } /* Hide pills on very small screens, rely on color dots if needed */
            .calendar-cell.has-event::after {
                content: '';
                position: absolute;
                bottom: 5px; left: 5px;
                width: 6px; height: 6px;
                border-radius: 50%;
                background: var(--primary);
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Include Nepali Datepicker utility for AD/BS conversions -->
    <script src="https://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v4.0.1.min.js" type="text/javascript"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Event Data from Backend (AD Dates)
            const eventsData = [
                @foreach($entries as $entry)
                {
                    title: @json($entry->title),
                    start: @json($entry->start_date),
                    end: @json($entry->end_date ?? $entry->start_date),
                    type: @json($entry->entry_type),
                    color: @json($typeColors[$entry->entry_type] ?? '#10b981'),
                },
                @endforeach
            ];

            const bsMonths = ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
            const adMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // Get current BS Date
            const currentBsDate = NepaliFunctions.GetCurrentBsDate();
            let currentViewYear = currentBsDate.year;
            let currentViewMonth = currentBsDate.month; // 1-12

            const cellsContainer = document.getElementById('calendarCells');
            const monthYearBsLabel = document.getElementById('currentMonthYearBS');
            const monthYearAdLabel = document.getElementById('currentMonthYearAD');

            function renderCalendar(bsYear, bsMonth) {
                cellsContainer.innerHTML = '';
                
                // Set Header Labels
                monthYearBsLabel.innerText = `${bsMonths[bsMonth - 1]} ${bsYear}`;
                
                // Get Total days in this BS month
                const totalDays = NepaliFunctions.GetBsDaysInMonth(bsYear, bsMonth);
                
                // Find what day of the week the 1st of the month falls on
                // Convert 1st day of BS month to AD
                const firstDayAdObj = NepaliFunctions.BS2AD({year: bsYear, month: bsMonth, day: 1});
                const firstDayAdDate = new Date(firstDayAdObj.year, firstDayAdObj.month - 1, firstDayAdObj.day);
                const startingDayOfWeek = firstDayAdDate.getDay(); // 0 (Sun) to 6 (Sat)

                // Get AD month range for the label
                const lastDayAdObj = NepaliFunctions.BS2AD({year: bsYear, month: bsMonth, day: totalDays});
                monthYearAdLabel.innerText = `${adMonths[firstDayAdObj.month - 1]} ${firstDayAdObj.year} - ${adMonths[lastDayAdObj.month - 1]} ${lastDayAdObj.year}`;

                // Padding empty cells before the 1st of the month
                for (let i = 0; i < startingDayOfWeek; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'calendar-cell empty';
                    cellsContainer.appendChild(emptyCell);
                }

                // Render the days
                for (let day = 1; day <= totalDays; day++) {
                    const cell = document.createElement('div');
                    cell.className = 'calendar-cell';
                    
                    // Convert this BS day to AD to match events and show AD date
                    const adDateObj = NepaliFunctions.BS2AD({year: bsYear, month: bsMonth, day: day});
                    
                    // Format AD date as YYYY-MM-DD for easy event matching
                    const adMonthStr = String(adDateObj.month).padStart(2, '0');
                    const adDayStr = String(adDateObj.day).padStart(2, '0');
                    const adDateString = `${adDateObj.year}-${adMonthStr}-${adDayStr}`;

                    // Check if today
                    if (bsYear === currentBsDate.year && bsMonth === currentBsDate.month && day === currentBsDate.day) {
                        cell.classList.add('today');
                    }

                    // Determine if it's Saturday (index 6 in standard week starting Sunday)
                    const currentDayOfWeek = (startingDayOfWeek + day - 1) % 7;
                    if(currentDayOfWeek === 6) {
                        cell.classList.add('holiday'); // Saturdays are holidays
                    }

                    let cellHTML = `
                        <div class="bs-date ${currentDayOfWeek === 6 ? 'text-danger' : ''}">${day}</div>
                        <div class="ad-date">${adDateObj.day} ${adMonths[adDateObj.month - 1]}</div>
                        <div class="cell-events">
                    `;

                    // Find events for this AD date
                    let hasEvent = false;
                    eventsData.forEach(event => {
                        if (adDateString >= event.start && adDateString <= event.end) {
                            hasEvent = true;
                            if(event.type === 'holiday') cell.classList.add('holiday');
                            cellHTML += `<div class="event-pill" style="background-color: ${event.color};" title="${event.title}">${event.title}</div>`;
                        }
                    });

                    if(hasEvent) {
                        cell.classList.add('has-event');
                    }

                    cellHTML += `</div>`;
                    cell.innerHTML = cellHTML;
                    cellsContainer.appendChild(cell);
                }
            }

            renderCalendar(currentViewYear, currentViewMonth);

            document.getElementById('prevMonthBtn').addEventListener('click', () => {
                currentViewMonth--;
                if (currentViewMonth < 1) {
                    currentViewMonth = 12;
                    currentViewYear--;
                }
                renderCalendar(currentViewYear, currentViewMonth);
            });

            document.getElementById('nextMonthBtn').addEventListener('click', () => {
                currentViewMonth++;
                if (currentViewMonth > 12) {
                    currentViewMonth = 1;
                    currentViewYear++;
                }
                renderCalendar(currentViewYear, currentViewMonth);
            });
        });
    </script>
@endpush

@include('frontend.layout.sections', ['page' => 'calendar'])
@endsection
