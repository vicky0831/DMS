import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { AppRoot } from './AppRoot';
import './i18n';

const container = document.getElementById('app');

if (container) {
    createRoot(container).render(
        <StrictMode>
            <AppRoot />
        </StrictMode>,
    );
}
