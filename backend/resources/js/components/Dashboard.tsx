import { useState } from 'react';
import type { CategoryPayload, DashboardPayload, ItemFormState, SessionPayload, TrackedItemPayload } from '../types';
import { Sidebar } from './Sidebar';
import { FlapTile } from './FlapTile';
import { UrgentQueue } from './UrgentQueue';
import { ItemsTable } from './ItemsTable';
import { AddItemDrawer } from './AddItemDrawer';
import { IconPlus } from './Icons';

type DashboardProps = {
    session: SessionPayload;
    dashboard: DashboardPayload | null;
    items: TrackedItemPayload[];
    categories: CategoryPayload[];
    busy: boolean;
    notice: string | null;
    onCreateItem: (form: ItemFormState) => void;
    onSignOut: () => void;
};

export function Dashboard({ session, dashboard, items, categories, busy, notice, onCreateItem, onSignOut }: DashboardProps) {
    const [drawerOpen, setDrawerOpen] = useState(false);

    const overview = dashboard?.overview;
    const urgency = dashboard?.urgency;
    const recentActivity = dashboard?.recent_activity.length
        ? dashboard.recent_activity
        : [
              'Document uploaded for Company Insurance',
              'Expiry changed for SSM Certificate',
              'Renewal started for Vehicle Road Tax',
              'Reminder sent to HR for employee passport renewal',
          ];

    function handleCreate(form: ItemFormState) {
        onCreateItem(form);
        setDrawerOpen(false);
    }

    return (
        <div className="app-shell">
            <Sidebar company={session.company} itemCount={overview?.total ?? items.length} />

            <div className="main-panel">
                <div className="topbar">
                    <div>
                        <span className="eyebrow">Dashboard</span>
                        <h1>Good morning, {session.user?.name ?? 'there'}</h1>
                        <p>
                            {dashboard
                                ? `${overview?.overdue ?? 0} items need attention today, ${urgency?.expiring_7_days ?? 0} more within the week.`
                                : 'Loading your company dashboard…'}
                        </p>
                    </div>
                    <div style={{ display: 'flex', gap: 10 }}>
                        <button type="button" className="btn-ghost" onClick={onSignOut} disabled={busy}>
                            Sign out
                        </button>
                        <button type="button" className="btn-add" onClick={() => setDrawerOpen(true)}>
                            <IconPlus />
                            Add tracked item
                        </button>
                    </div>
                </div>

                {notice && <div className="notice-banner">{notice}</div>}

                <div className="flap-row">
                    <FlapTile value={overview?.overdue ?? 0} label="Overdue" hint="Needs action now" tone="alert" delay={0} />
                    <FlapTile
                        value={urgency?.expiring_7_days ?? 0}
                        label="Due in 7 days"
                        hint="Urgent this week"
                        tone="amber"
                        delay={80}
                    />
                    <FlapTile
                        value={urgency?.expiring_30_days ?? 0}
                        label="Due in 30 days"
                        hint="Plan the next wave"
                        tone="paper"
                        delay={160}
                    />
                    <FlapTile value={overview?.active ?? 0} label="Active" hint="Under control" tone="jade" delay={240} />
                </div>

                <UrgentQueue items={items} onOpen={() => setDrawerOpen(true)} />
                <ItemsTable items={items} />

                <div className="dual-grid">
                    <div className="panel">
                        <div className="panel-head">
                            <div>
                                <span className="eyebrow">Categories</span>
                                <h2>{categories.length ? `${categories.length} ready to use` : 'Loading categories'}</h2>
                            </div>
                        </div>
                        <div className="cat-list">
                            {categories.map((category) => (
                                <div className="cat-row" key={category.id}>
                                    <span className="name">{category.name}</span>
                                    <span className="group">{category.group_name ?? 'Custom'}</span>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="panel">
                        <div className="panel-head">
                            <div>
                                <span className="eyebrow">Audit trail</span>
                                <h2>Recent activity</h2>
                            </div>
                        </div>
                        <ul className="activity-list">
                            {recentActivity.map((entry, index) => (
                                <li key={index}>
                                    <span className="dot" />
                                    <span>{entry}</span>
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>
            </div>

            <AddItemDrawer
                open={drawerOpen}
                busy={busy}
                categories={categories}
                onClose={() => setDrawerOpen(false)}
                onSubmit={handleCreate}
            />
        </div>
    );
}
