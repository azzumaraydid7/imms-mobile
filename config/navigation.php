<?php

/*
|--------------------------------------------------------------------------
| Application navigation
|--------------------------------------------------------------------------
|
| Single source of truth for the module navigation rendered by the tab bar and
| the "More" sheet. A `route` of null means the module has no screen in this
| client yet and is rendered as a disabled "soon" entry.
|
| Icons are inline SVG fragments drawn on a 24x24 grid with `currentColor`
| strokes.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Overview
    |--------------------------------------------------------------------------
    |
    | The landing destination — the first tab in the bar.
    |
    */
    'overview' => [
        'label' => 'Overview',
        'route' => 'dashboard',
        'icon' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tab shortcuts
    |--------------------------------------------------------------------------
    |
    | Pinned to the bottom bar between Overview and the "More" sheet, so the
    | most used modules sit within one thumb reach.
    |
    */
    'tabs' => [
        [
            'label' => 'Students',
            'route' => null,
            'icon' => '<circle cx="12" cy="8" r="3.5"/><path d="M4.5 20a7.5 7.5 0 0 1 15 0"/>',
        ],
        [
            'label' => 'Bookings',
            'route' => null,
            'icon' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/><path d="M9 15l2 2 4-4"/>',
        ],
        [
            'label' => 'Finance',
            'route' => null,
            'icon' => '<path d="M4 19V10M9.5 19V5M15 19v-6M20.5 19V8"/>',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sections
    |--------------------------------------------------------------------------
    |
    | The full module list, grouped by domain. Powers the "More" sheet.
    |
    */
    'sections' => [
        [
            'label' => 'Operations',
            'items' => [
                ['label' => 'Students', 'route' => null, 'icon' => '<circle cx="12" cy="8" r="3.5"/><path d="M4.5 20a7.5 7.5 0 0 1 15 0"/>'],
                ['label' => 'Bookings', 'route' => null, 'icon' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/><path d="M9 15l2 2 4-4"/>'],
                ['label' => 'Schedule', 'route' => null, 'icon' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>'],
                ['label' => 'Instructors', 'route' => null, 'icon' => '<path d="M12 3l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L3.5 9.2l5.9-.9z"/>'],
                ['label' => 'Vehicles', 'route' => null, 'icon' => '<path d="M3 14l1.7-5A2 2 0 0 1 6.6 7.5h10.8a2 2 0 0 1 1.9 1.5L21 14"/><rect x="3" y="14" width="18" height="5" rx="1.5"/><circle cx="7" cy="19" r="1.6"/><circle cx="17" cy="19" r="1.6"/>'],
                ['label' => 'Courses', 'route' => null, 'icon' => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 3v18"/><path d="M12 8h5M12 12h5"/>'],
                ['label' => 'JPJ', 'route' => null, 'icon' => '<path d="M12 3l7.5 3v5.5c0 4.3-3 7.6-7.5 9-4.5-1.4-7.5-4.7-7.5-9V6z"/><path d="M9 12l2 2 4-4"/>'],
                ['label' => 'Agents', 'route' => null, 'icon' => '<circle cx="8.5" cy="9" r="3"/><path d="M2.5 19a6 6 0 0 1 12 0"/><circle cx="17" cy="10.5" r="2.2"/><path d="M15 15.8a4.6 4.6 0 0 1 6.5 3.2"/>'],
            ],
        ],
        [
            'label' => 'Finance',
            'items' => [
                ['label' => 'Invoices', 'route' => null, 'icon' => '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/>'],
                ['label' => 'Payments', 'route' => null, 'icon' => '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10.5h18"/><path d="M7 14.5h4"/>'],
                ['label' => 'Wallet', 'route' => null, 'icon' => '<path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H18v3"/><rect x="3" y="8" width="18" height="11" rx="2.5"/><circle cx="16.5" cy="13.5" r="1.3"/>'],
            ],
        ],
        [
            'label' => 'System',
            'items' => [
                ['label' => 'Identity', 'route' => null, 'icon' => '<circle cx="12" cy="8" r="3.5"/><path d="M4.5 20a7.5 7.5 0 0 1 15 0"/>'],
                ['label' => 'Staff', 'route' => null, 'icon' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7"/><path d="M3 12h18"/>'],
                ['label' => 'Notifications', 'route' => null, 'icon' => '<path d="M18 16H6l1.2-1.8V10a4.8 4.8 0 0 1 9.6 0v4.2z"/><path d="M10.5 19a1.8 1.8 0 0 0 3 0"/>'],
                ['label' => 'Configuration', 'route' => null, 'icon' => '<path d="M4 7h6M14 7h6"/><circle cx="12" cy="7" r="2"/><path d="M4 17h10M18 17h2"/><circle cx="16" cy="17" r="2"/>'],
                ['label' => 'Documents', 'route' => null, 'icon' => '<path d="M7 3h6l5 5v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z"/><path d="M13 3v5h5"/>'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module readiness
    |--------------------------------------------------------------------------
    |
    | Mirrors the modules scaffolded on the IMMS platform, so a client user can
    | tell what is actually available versus announced.
    |
    */
    'modules' => [
        ['label' => 'Identity', 'status' => 'live'],
        ['label' => 'Student', 'status' => 'scaffolded'],
        ['label' => 'Staff', 'status' => 'scaffolded'],
        ['label' => 'Agent', 'status' => 'scaffolded'],
        ['label' => 'Course', 'status' => 'scaffolded'],
        ['label' => 'Booking', 'status' => 'scaffolded'],
        ['label' => 'Schedule', 'status' => 'scaffolded'],
        ['label' => 'Instructor', 'status' => 'scaffolded'],
        ['label' => 'Vehicle', 'status' => 'scaffolded'],
        ['label' => 'JPJ', 'status' => 'scaffolded'],
        ['label' => 'Invoice', 'status' => 'scaffolded'],
        ['label' => 'Payment', 'status' => 'scaffolded'],
        ['label' => 'Wallet', 'status' => 'scaffolded'],
        ['label' => 'Notification', 'status' => 'scaffolded'],
        ['label' => 'Configuration', 'status' => 'scaffolded'],
        ['label' => 'Document', 'status' => 'scaffolded'],
    ],
];
