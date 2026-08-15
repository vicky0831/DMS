type IconProps = { className?: string; style?: React.CSSProperties };

const base = { fill: 'none', stroke: 'currentColor', strokeWidth: 1.4, strokeLinecap: 'round' as const, strokeLinejoin: 'round' as const };

export function IconGrid({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <rect x="2.5" y="2.5" width="6" height="6" rx="1.2" {...base} />
            <rect x="11.5" y="2.5" width="6" height="6" rx="1.2" {...base} />
            <rect x="2.5" y="11.5" width="6" height="6" rx="1.2" {...base} />
            <rect x="11.5" y="11.5" width="6" height="6" rx="1.2" {...base} />
        </svg>
    );
}

export function IconList({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <circle cx="3.6" cy="5" r="1" fill="currentColor" />
            <circle cx="3.6" cy="10" r="1" fill="currentColor" />
            <circle cx="3.6" cy="15" r="1" fill="currentColor" />
            <line x1="7" y1="5" x2="17" y2="5" {...base} />
            <line x1="7" y1="10" x2="17" y2="10" {...base} />
            <line x1="7" y1="15" x2="17" y2="15" {...base} />
        </svg>
    );
}

export function IconRefresh({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <path d="M4 10a6 6 0 0 1 10-4.3M16 10a6 6 0 0 1-10 4.3" {...base} />
            <path d="M13.5 4.5v3h-3M6.5 15.5v-3h3" {...base} />
        </svg>
    );
}

export function IconCalendar({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <rect x="3" y="4" width="14" height="13" rx="1.6" {...base} />
            <line x1="3" y1="8" x2="17" y2="8" {...base} />
            <line x1="6.5" y1="2.3" x2="6.5" y2="5.3" {...base} />
            <line x1="13.5" y1="2.3" x2="13.5" y2="5.3" {...base} />
        </svg>
    );
}

export function IconFile({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <path d="M5.5 2.8h6l3 3v10.4a1 1 0 0 1-1 1h-8a1 1 0 0 1-1-1V3.8a1 1 0 0 1 1-1z" {...base} />
            <path d="M11.5 2.8v3h3" {...base} />
        </svg>
    );
}

export function IconChart({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <line x1="3.5" y1="16.5" x2="16.5" y2="16.5" {...base} />
            <rect x="5" y="10" width="3" height="6" rx="0.6" fill="currentColor" />
            <rect x="9.5" y="6.5" width="3" height="9.5" rx="0.6" fill="currentColor" />
            <rect x="14" y="3" width="3" height="13" rx="0.6" fill="currentColor" />
        </svg>
    );
}

export function IconUsers({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <circle cx="7.3" cy="7" r="2.6" {...base} />
            <path d="M2.8 16c0-2.7 2-4.4 4.5-4.4S11.8 13.3 11.8 16" {...base} />
            <circle cx="14" cy="6.5" r="2" {...base} strokeWidth={1.3} />
            <path d="M13 11.8c2 .1 3.6 1.6 3.7 4.2" {...base} strokeWidth={1.3} />
        </svg>
    );
}

export function IconSettings({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <circle cx="10" cy="10" r="2.6" {...base} />
            <path
                d="M10 3v2.1M10 14.9V17M17 10h-2.1M5.1 10H3M14.9 5.1l-1.5 1.5M6.6 13.4l-1.5 1.5M14.9 14.9l-1.5-1.5M6.6 6.6 5.1 5.1"
                {...base}
            />
        </svg>
    );
}

export function IconPlus({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <line x1="10" y1="4" x2="10" y2="16" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" />
            <line x1="4" y1="10" x2="16" y2="10" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" />
        </svg>
    );
}

export function IconClose({ className, style }: IconProps) {
    return (
        <svg className={className} style={style} viewBox="0 0 20 20">
            <line x1="5" y1="5" x2="15" y2="15" stroke="currentColor" strokeWidth={1.6} strokeLinecap="round" />
            <line x1="15" y1="5" x2="5" y2="15" stroke="currentColor" strokeWidth={1.6} strokeLinecap="round" />
        </svg>
    );
}
