const LOCALE = 'fr-FR';

export function formatDate(date, options = { day: 'numeric', month: 'short', year: 'numeric' }) {
    if (!date) {
        return null;
    }

    return new Date(date).toLocaleDateString(LOCALE, options);
}

export function formatRelative(date) {
    if (!date) {
        return null;
    }

    const seconds = Math.round((new Date(date).getTime() - Date.now()) / 1000);
    const units = [
        ['year', 31536000],
        ['month', 2592000],
        ['week', 604800],
        ['day', 86400],
        ['hour', 3600],
        ['minute', 60],
    ];
    const rtf = new Intl.RelativeTimeFormat(LOCALE, { numeric: 'auto' });

    for (const [unit, size] of units) {
        if (Math.abs(seconds) >= size) {
            return rtf.format(Math.round(seconds / size), unit);
        }
    }

    return "à l'instant";
}

export function isRecent(date, days = 7) {
    return Boolean(date) && Date.now() - new Date(date).getTime() < days * 86400000;
}

export function formatAmount(value) {
    return new Intl.NumberFormat(LOCALE).format(value);
}

export function formatSalaryRange(min, max) {
    if (min && max) {
        return `${formatAmount(min)} – ${formatAmount(max)} FCFA / mois`;
    }

    if (min) {
        return `À partir de ${formatAmount(min)} FCFA / mois`;
    }

    if (max) {
        return `Jusqu'à ${formatAmount(max)} FCFA / mois`;
    }

    return null;
}

export function initials(name = '') {
    return name
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0].toUpperCase())
        .join('');
}
