import type { TrackedItemPayload } from '../types';
import { formatDate } from '../lib/format';
import { StatusChip } from './StatusChip';

type ItemsTableProps = {
    items: TrackedItemPayload[];
};

export function ItemsTable({ items }: ItemsTableProps) {
    return (
        <div className="panel">
            <div className="panel-head">
                <div>
                    <span className="eyebrow">Manifest</span>
                    <h2>Tracked items</h2>
                </div>
                <button type="button" className="btn-ghost">
                    Filter
                </button>
            </div>

            <div className="table-wrap">
                <table className="manifest">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        {items.length === 0 ? (
                            <tr className="empty-row">
                                <td colSpan={4}>No tracked items yet. Add your first renewal above.</td>
                            </tr>
                        ) : (
                            items.map((item) => (
                                <tr key={item.id}>
                                    <td className="name">{item.name}</td>
                                    <td className="cat">{item.category?.name ?? 'Uncategorised'}</td>
                                    <td>
                                        <StatusChip status={item.status} />
                                    </td>
                                    <td className="expiry">{formatDate(item.expiry_date)}</td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
