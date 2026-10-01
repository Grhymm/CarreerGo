<script setup>
defineProps({
    name: {
        type: String,
        required: true,
    },
    size: {
        type: String,
        default: 'md',
    },
});

const sizes = {
    xs: 'h-3.5 w-3.5',
    sm: 'h-4 w-4',
    md: 'h-5 w-5',
    lg: 'h-6 w-6',
};

const paths = {
    home: ['M3 10.5 12 3l9 7.5', 'M5 9v11h5v-6h4v6h5V9'],
    search: ['M10.5 4a6.5 6.5 0 1 0 0 13 6.5 6.5 0 0 0 0-13Z', 'm15.5 15.5 5 5'],
    briefcase: ['M3 8h18v11H3z', 'M8 8V5h8v3', 'M3 13h18'],
    inbox: ['M3 13h5l1.5 3h5L16 13h5', 'M5 5h14l2 8v6H3v-6z'],
    users: ['M9 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z', 'M2.5 20a6.5 6.5 0 0 1 13 0', 'M16 4.5a3.5 3.5 0 0 1 0 6.5', 'M18 14a6 6 0 0 1 3.5 6'],
    user: ['M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z', 'M4 21a8 8 0 0 1 16 0'],
    flag: ['M5 21V4', 'M5 4h12l-2 4 2 4H5'],
    shield: ['M12 3 4 6v6c0 4.5 3.4 8.2 8 9 4.6-.8 8-4.5 8-9V6z', 'm8.5 12 2.5 2.5 4.5-5'],
    logout: ['M15 4h4v16h-4', 'M10 8l-4 4 4 4', 'M6 12h10'],
    plus: ['M12 5v14', 'M5 12h14'],
    x: ['M6 6l12 12', 'M18 6 6 18'],
    check: ['m5 12.5 4.5 4.5L19 7.5'],
    pin: ['M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21Z', 'M12 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z'],
    clock: ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z', 'M12 7v5l3 2'],
    calendar: ['M4 6h16v14H4z', 'M4 10h16', 'M8 3v5', 'M16 3v5'],
    document: ['M6 3h8l4 4v14H6z', 'M14 3v4h4', 'M9 12h6', 'M9 16h6'],
    download: ['M12 4v11', 'm7 10 5 5 5-5', 'M5 20h14'],
    chevronLeft: ['m15 5-7 7 7 7'],
    chevronRight: ['m9 5 7 7-7 7'],
    chevronDown: ['m6 9 6 6 6-6'],
    filter: ['M4 6h16', 'M7 12h10', 'M10 18h4'],
    pencil: ['M4 20h4L19 9l-4-4L4 16z', 'm13.5 6.5 4 4'],
    trash: ['M4 7h16', 'M9 7V4h6v3', 'M6 7l1 13h10l1-13'],
    ban: ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z', 'm5.6 5.6 12.8 12.8'],
    refresh: ['M20 11a8 8 0 1 0-2.3 5.7', 'M20 4v7h-7'],
    badgeCheck: ['M12 3l2.4 1.8 3 .2.8 2.9L20.5 10l-1 2.8.4 3-2.6 1.5-1.4 2.7-3-.4L12 21l-2.4-1.4-3 .4-1.4-2.7-2.6-1.5.4-3-1-2.8 2.3-2.1.8-2.9 3-.2z', 'm8.5 12 2.5 2.5 4.5-5'],
    sparkle: ['M12 3v4', 'M12 17v4', 'M3 12h4', 'M17 12h4', 'm6 6 2.5 2.5', 'm15.5 15.5 2.5 2.5', 'm18 6-2.5 2.5', 'm8.5 15.5-2.5 2.5'],
    chart: ['M4 20V10', 'M10 20V4', 'M16 20v-7', 'M3 20h18'],
    lightbulb: ['M9 18h6', 'M10 21h4', 'M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2.1h5c0-.9.4-1.6 1-2.1A6 6 0 0 0 12 3Z'],
    send: ['M21 3 3 10.5l7 2.5 2.5 7z', 'm10 13 5-5'],
    alert: ['M12 3 2 20h20z', 'M12 10v4', 'M12 17h.01'],
    info: ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z', 'M12 11v5', 'M12 8h.01'],
    building: ['M4 21V4h10v17', 'M14 9h6v12', 'M8 8h2', 'M8 12h2', 'M8 16h2', 'M2 21h20'],
    menu: ['M4 6h16', 'M4 12h16', 'M4 18h16'],
};
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.75"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
        focusable="false"
        :class="[sizes[size], 'shrink-0']"
    >
        <path v-for="(d, index) in paths[name]" :key="index" :d="d" />
    </svg>
</template>
