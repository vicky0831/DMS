import { useEffect, useState } from 'react';
import type { CategoryPayload, ItemFormState, Priority } from '../types';
import { emptyItemForm } from '../types';
import { IconClose } from './Icons';

type AddItemDrawerProps = {
    open: boolean;
    busy: boolean;
    categories: CategoryPayload[];
    onClose: () => void;
    onSubmit: (form: ItemFormState) => void;
};

const PRIORITIES: { value: Priority; label: string }[] = [
    { value: 'low', label: 'Low' },
    { value: 'normal', label: 'Normal' },
    { value: 'high', label: 'High' },
    { value: 'urgent', label: 'Urgent' },
];

export function AddItemDrawer({ open, busy, categories, onClose, onSubmit }: AddItemDrawerProps) {
    const [form, setForm] = useState<ItemFormState>(emptyItemForm);

    useEffect(() => {
        if (open) {
            setForm(emptyItemForm);
        }
    }, [open]);

    if (!open) {
        return null;
    }

    function update<K extends keyof ItemFormState>(key: K, value: ItemFormState[K]) {
        setForm((current) => ({ ...current, [key]: value }));
    }

    function handleSubmit(event: React.FormEvent) {
        event.preventDefault();
        onSubmit(form);
    }

    return (
        <div
            className="drawer-overlay"
            onClick={(event) => {
                if (event.target === event.currentTarget) {
                    onClose();
                }
            }}
        >
            <form className="drawer" onSubmit={handleSubmit}>
                <div className="drawer-head">
                    <div>
                        <span className="eyebrow">New entry</span>
                        <h2>Add tracked item</h2>
                    </div>
                    <button type="button" className="drawer-close" onClick={onClose} aria-label="Close">
                        <IconClose style={{ width: 16, height: 16 }} />
                    </button>
                </div>
                <p style={{ fontSize: 13, color: 'var(--slate)', margin: '0 0 22px' }}>
                    Capture a renewal in a few fields — you can fill in the rest later.
                </p>

                <div className="field">
                    <label htmlFor="item_name">Item name</label>
                    <input
                        id="item_name"
                        type="text"
                        required
                        placeholder="Fire safety certificate"
                        value={form.name}
                        onChange={(event) => update('name', event.target.value)}
                    />
                </div>

                <div className="field-row">
                    <div className="field">
                        <label htmlFor="item_category">Category</label>
                        <select
                            id="item_category"
                            value={form.category_id}
                            onChange={(event) => update('category_id', event.target.value)}
                        >
                            <option value="">Select a category</option>
                            {categories.map((category) => (
                                <option key={category.id} value={category.id}>
                                    {category.name}
                                    {category.group_name ? ` · ${category.group_name}` : ''}
                                </option>
                            ))}
                        </select>
                    </div>
                    <div className="field">
                        <label htmlFor="item_priority">Priority</label>
                        <select
                            id="item_priority"
                            value={form.priority}
                            onChange={(event) => update('priority', event.target.value as Priority)}
                        >
                            {PRIORITIES.map((option) => (
                                <option key={option.value} value={option.value}>
                                    {option.label}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>

                <div className="field-row">
                    <div className="field">
                        <label htmlFor="item_reference">Reference number</label>
                        <input
                            id="item_reference"
                            type="text"
                            placeholder="Optional"
                            value={form.reference_number}
                            onChange={(event) => update('reference_number', event.target.value)}
                        />
                    </div>
                    <div className="field">
                        <label htmlFor="item_expiry">Expiry date</label>
                        <input
                            id="item_expiry"
                            type="date"
                            value={form.expiry_date}
                            onChange={(event) => update('expiry_date', event.target.value)}
                        />
                    </div>
                </div>

                <div className="field">
                    <label htmlFor="item_responsible">Responsible person ID</label>
                    <input
                        id="item_responsible"
                        type="number"
                        min={1}
                        placeholder="Assign a teammate"
                        value={form.responsible_user_id}
                        onChange={(event) => update('responsible_user_id', event.target.value)}
                    />
                </div>

                <div className="field">
                    <label htmlFor="item_description">Description</label>
                    <textarea
                        id="item_description"
                        rows={3}
                        value={form.description}
                        onChange={(event) => update('description', event.target.value)}
                    />
                </div>

                <div className="field" style={{ flexDirection: 'row', alignItems: 'center', gap: 10 }}>
                    <input 
                        type="checkbox" 
                        id="notify_whatsapp" 
                        style={{ width: 'auto' }}
                        onChange={(e) => {
                            // Note: backend needs to be mapped to read this preference.
                            const currentNotes = form.notes || '';
                            const wsTag = '[WhatsApp Reminder]';
                            if (e.target.checked && !currentNotes.includes(wsTag)) {
                                update('notes', currentNotes ? `${currentNotes}\n${wsTag}` : wsTag);
                            }
                        }}
                    />
                    <label htmlFor="notify_whatsapp" style={{ display: 'inline-flex', alignItems: 'center', gap: 6, cursor: 'pointer' }}>
                        <span style={{ color: 'var(--jade)' }}>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        </span>
                        Notify via WhatsApp
                    </label>
                </div>

                <div className="field">
                    <label htmlFor="item_notes">Notes</label>
                    <textarea
                        id="item_notes"
                        rows={3}
                        placeholder="Optional"
                        value={form.notes}
                        onChange={(event) => update('notes', event.target.value)}
                    />
                </div>

                <button type="submit" className="btn-primary full-width" disabled={busy}>
                    {busy ? 'Saving item…' : 'Save tracked item'}
                </button>
            </form>
        </div>
    );
}
