export type TrackedItemStatus =
    | 'ACTIVE'
    | 'EXPIRING_SOON'
    | 'RENEWAL_REQUIRED'
    | 'RENEWAL_IN_PROGRESS'
    | 'PENDING_APPROVAL'
    | 'RENEWED'
    | 'EXPIRED'
    | 'CANCELLED';

export type Priority = 'low' | 'normal' | 'high' | 'urgent';

export type AuthMode = 'login' | 'register';

export type CompanyPayload = {
    id: number;
    name: string;
    slug: string;
    timezone: string;
    locale: string;
};

export type UserPayload = {
    id: number;
    name: string;
    email: string;
};

export type CategoryPayload = {
    id: number;
    name: string;
    group_name: string | null;
    is_default: boolean;
    sort_order: number;
};

export type TrackedItemPayload = {
    id: number;
    name: string;
    reference_number: string | null;
    status: TrackedItemStatus;
    expiry_date: string | null;
    priority?: Priority | null;
    category?: {
        id: number;
        name: string;
    } | null;
};

export type DashboardPayload = {
    overview: {
        total: number;
        active: number;
        overdue: number;
        expiring_soon: number;
        renewal_in_progress: number;
        completed_this_month: number;
    };
    urgency: {
        overdue: number;
        expiring_7_days: number;
        expiring_30_days: number;
        expiring_60_days: number;
        expiring_90_days: number;
    };
    recent_activity: string[];
};

export type SessionPayload = {
    user: UserPayload | null;
    company: CompanyPayload | null;
};

export type ApiPayload<T> = {
    data: T;
};

export type PaginatedPayload<T> = {
    data: T[];
};

export type RegisterFormState = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    company_name: string;
    company_registration_number: string;
};

export type LoginFormState = {
    email: string;
    password: string;
};

export type ItemFormState = {
    name: string;
    reference_number: string;
    category_id: string;
    expiry_date: string;
    responsible_user_id: string;
    priority: Priority;
    notes: string;
    description: string;
    status: TrackedItemStatus;
};

export const emptyItemForm: ItemFormState = {
    name: '',
    reference_number: '',
    category_id: '',
    expiry_date: '',
    responsible_user_id: '',
    priority: 'normal',
    notes: '',
    description: '',
    status: 'ACTIVE',
};
