import { useEffect, useState } from 'react';
import type {
    ApiPayload,
    CategoryPayload,
    DashboardPayload,
    ItemFormState,
    LoginFormState,
    PaginatedPayload,
    RegisterFormState,
    SessionPayload,
    TrackedItemPayload,
} from './types';
import { errorMessage, requestJson } from './lib/api';
import { AuthScreen } from './components/AuthScreen';
import { Dashboard } from './components/Dashboard';

const EMPTY_SESSION: SessionPayload = { user: null, company: null };

export function AppRoot() {
    const [session, setSession] = useState<SessionPayload>(EMPTY_SESSION);
    const [dashboard, setDashboard] = useState<DashboardPayload | null>(null);
    const [items, setItems] = useState<TrackedItemPayload[]>([]);
    const [categories, setCategories] = useState<CategoryPayload[]>([]);
    const [booting, setBooting] = useState(true);
    const [busy, setBusy] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [notice, setNotice] = useState<string | null>(null);

    useEffect(() => {
        void loadSession();
    }, []);

    async function loadSession() {
        try {
            const response = await requestJson<ApiPayload<SessionPayload>>('/api/me');
            setSession(response.data);

            if (response.data.user) {
                await loadDashboard();
            }
        } catch {
            setSession(EMPTY_SESSION);
        } finally {
            setBooting(false);
        }
    }

    async function loadDashboard() {
        try {
            const [dashboardResponse, itemsResponse, categoriesResponse] = await Promise.all([
                requestJson<ApiPayload<DashboardPayload>>('/api/dashboard'),
                requestJson<ApiPayload<PaginatedPayload<TrackedItemPayload>>>('/api/items'),
                requestJson<ApiPayload<CategoryPayload[]>>('/api/categories'),
            ]);

            setDashboard(dashboardResponse.data);
            setItems(itemsResponse.data.data);
            setCategories(categoriesResponse.data);
            setError(null);
        } catch (loadError) {
            setError(errorMessage(loadError, 'Unable to load dashboard'));
        }
    }

    async function handleSignIn(form: LoginFormState) {
        setBusy(true);
        setError(null);

        try {
            const response = await requestJson<ApiPayload<SessionPayload>>('/api/auth/login', {
                method: 'POST',
                body: JSON.stringify(form),
            });

            setSession(response.data);
            await loadDashboard();
        } catch (signInError) {
            setError(errorMessage(signInError, 'That email or password looks wrong. Try again.'));
        } finally {
            setBusy(false);
        }
    }

    async function handleRegister(form: RegisterFormState) {
        setBusy(true);
        setError(null);

        try {
            const response = await requestJson<ApiPayload<SessionPayload>>('/api/auth/register', {
                method: 'POST',
                body: JSON.stringify(form),
            });

            setSession(response.data);
            await loadDashboard();
        } catch (registerError) {
            setError(errorMessage(registerError, 'Could not create your workspace. Check the details and try again.'));
        } finally {
            setBusy(false);
        }
    }

    async function handleSignOut() {
        setBusy(true);

        try {
            await requestJson('/api/auth/logout', { method: 'POST' });
        } catch {
            // Session is cleared locally regardless of network failure.
        } finally {
            setSession(EMPTY_SESSION);
            setDashboard(null);
            setItems([]);
            setCategories([]);
            setBusy(false);
        }
    }

    async function handleCreateItem(form: ItemFormState) {
        setBusy(true);
        setError(null);
        setNotice(null);

        try {
            const payload = {
                name: form.name,
                reference_number: form.reference_number || null,
                category_id: form.category_id ? Number(form.category_id) : null,
                expiry_date: form.expiry_date || null,
                responsible_user_id: form.responsible_user_id ? Number(form.responsible_user_id) : null,
                priority: form.priority,
                description: form.description || null,
                notes: form.notes || null,
                status: form.status,
            };

            await requestJson('/api/items', {
                method: 'POST',
                body: JSON.stringify(payload),
            });

            setNotice('Tracked item saved.');
            await loadDashboard();
        } catch (createError) {
            setError(errorMessage(createError, 'Unable to save that tracked item.'));
        } finally {
            setBusy(false);
        }
    }

    if (booting) {
        return null;
    }

    if (!session.user) {
        return <AuthScreen busy={busy} error={error} onSignIn={handleSignIn} onRegister={handleRegister} />;
    }

    return (
        <Dashboard
            session={session}
            dashboard={dashboard}
            items={items}
            categories={categories}
            busy={busy}
            notice={notice}
            onCreateItem={handleCreateItem}
            onSignOut={handleSignOut}
        />
    );
}
