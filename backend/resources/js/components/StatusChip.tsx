import type { TrackedItemStatus } from '../types';
import { statusChipClass, statusLabel } from '../lib/format';

export function StatusChip({ status }: { status: TrackedItemStatus }) {
    return <span className={`flap-chip ${statusChipClass(status)}`}>{statusLabel(status)}</span>;
}
