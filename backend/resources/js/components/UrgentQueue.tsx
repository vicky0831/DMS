import type { TrackedItemPayload } from '../types';
import { daysUntil, urgencyLabel } from '../lib/format';

type UrgentQueueProps = {
    items: TrackedItemPayload[];
    onOpen: (item: TrackedItemPayload) => void;
};

function actionLabel(item: TrackedItemPayload): string {
    const days = daysUntil(item.expiry_date);

    if (days !== null && days < 0) {
        return 'Resolve';
    }

    if (item.status === 'RENEWAL_IN_PROGRESS') {
        return 'Review';
    }

    return 'Start renewal';
}

export function UrgentQueue({ items, onOpen }: UrgentQueueProps) {
    const queue = items
        .filter((item) => item.expiry_date && item.status !== 'RENEWED' && item.status !== 'CANCELLED')
        .map((item) => ({ item, days: daysUntil(item.expiry_date) as number }))
        .filter(({ days }) => days <= 30)
        .sort((a, b) => a.days - b.days)
        .slice(0, 5);

    return (
        <div className="panel">
            <div className="panel-head">
                <div>
                    <span className="eyebrow">Needs attention</span>
                    <h2>Sorted by urgency</h2>
                </div>
                <button type="button" className="btn-ghost">
                    View all
                </button>
            </div>

            {queue.length === 0 ? (
                <p style={{ color: 'var(--slate)', fontSize: 13.5 }}>Nothing urgent — everything is on track.</p>
            ) : (
                queue.map(({ item }, index) => {
                    const urgency = urgencyLabel(item.expiry_date);

                    return (
                        <div className="queue-row" key={item.id}>
                            <span className="queue-num">{String(index + 1).padStart(2, '0')}</span>
                            <div className="queue-main">
                                <p className="name">{item.name}</p>
                                <p className="meta">{item.category?.name ?? 'Uncategorised'}</p>
                            </div>
                            <span className={`queue-time tone-${urgency.tone}`}>{urgency.text}</span>
                            <button type="button" className="btn-row-action" onClick={() => onOpen(item)}>
                                {actionLabel(item)}
                            </button>
                        </div>
                    );
                })
            )}
        </div>
    );
}
