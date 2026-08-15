export class ApiError extends Error {}

function csrfToken(): string {
    const token = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content;

    if (!token) {
        throw new ApiError('Missing CSRF token');
    }

    return token;
}

export async function requestJson<T>(url: string, init?: RequestInit): Promise<T> {
    const response = await fetch(url, {
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            ...(init?.headers ?? {}),
        },
        ...init,
    });

    if (!response.ok) {
        const payload = await response.json().catch(() => null);
        const message = payload?.message ?? `Request failed with status ${response.status}`;
        throw new ApiError(message);
    }

    return response.json() as Promise<T>;
}

export function errorMessage(error: unknown, fallback: string): string {
    return error instanceof Error ? error.message : fallback;
}
