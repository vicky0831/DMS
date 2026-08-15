import type { CompanyPayload } from '../types';
import {
    IconCalendar,
    IconChart,
    IconFile,
    IconGrid,
    IconList,
    IconRefresh,
    IconSettings,
    IconUsers,
} from './Icons';

type NavItem = { label: string; icon: typeof IconGrid; active?: boolean };

const NAV_ITEMS: NavItem[] = [
    { label: 'Dashboard', icon: IconGrid, active: true },
    { label: 'Tracked items', icon: IconList },
    { label: 'Renewals', icon: IconRefresh },
    { label: 'Calendar', icon: IconCalendar },
    { label: 'Documents', icon: IconFile },
    { label: 'Reports', icon: IconChart },
    { label: 'Team', icon: IconUsers },
    { label: 'Settings', icon: IconSettings },
];

type SidebarProps = {
    company: CompanyPayload | null;
    itemCount: number;
};

export function Sidebar({ company, itemCount }: SidebarProps) {
    const today = new Date().toLocaleDateString('en-MY', {
        weekday: 'short',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });

    return (
        <aside className="sidebar">
            <div>
                <div className="brand-mark">
                    <span className="sq" />
                    <span className="eyebrow">Compliance tracker</span>
                </div>
                <h1 className="sidebar-title">{company?.name ?? 'Your company'}</h1>
            </div>

            <nav className="nav-list" aria-label="Primary navigation">
                {NAV_ITEMS.map(({ label, icon: Icon, active }) => (
                    <button key={label} type="button" className={`nav-item ${active ? 'active' : ''}`}>
                        <Icon />
                        {label}
                        {label === 'Tracked items' && itemCount > 0 && <span className="count">{itemCount}</span>}
                    </button>
                ))}
            </nav>

            <div className="company-card">
                <span className="eyebrow">Company</span>
                <strong>{company?.name ?? 'No company selected'}</strong>
                <span>{company?.timezone ?? 'Asia/Kuala_Lumpur'}</span>
            </div>

            <div className="sidebar-foot">
                <span className="eyebrow">Today</span>
                <div className="date">{today}</div>
            </div>
        </aside>
    );
}
