import { useState } from 'react';
import type { AuthMode, LoginFormState, RegisterFormState } from '../types';

const PITCH_ROWS = [
    { name: 'Company Insurance', meta: 'Overdue · 2d', owner: 'Finance', color: 'var(--alert)' },
    { name: 'XYZ Business Licence', meta: 'Due · 5d', owner: 'Admin', color: 'var(--amber)' },
    { name: 'Office Tenancy', meta: 'Due · 14d', owner: 'Ops', color: 'var(--jade)' },
];

type AuthScreenProps = {
    busy: boolean;
    error: string | null;
    onSignIn: (form: LoginFormState) => void;
    onRegister: (form: RegisterFormState) => void;
};

export function AuthScreen({ busy, error, onSignIn, onRegister }: AuthScreenProps) {
    const [mode, setMode] = useState<AuthMode>('login');
    const [loginForm, setLoginForm] = useState<LoginFormState>({ email: '', password: '' });
    const [registerForm, setRegisterForm] = useState<RegisterFormState>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        company_name: '',
        company_registration_number: '',
    });

    function handleSubmit(event: React.FormEvent) {
        event.preventDefault();

        if (mode === 'login') {
            onSignIn(loginForm);
        } else {
            onRegister(registerForm);
        }
    }

    return (
        <div className="auth-shell">
            <div className="auth-pitch">
                <span className="eyebrow">Malaysia SME Compliance &amp; Renewal Tracker</span>
                <h1>Never miss a renewal again.</h1>
                <p>
                    One control tower for every SSM certificate, licence, insurance policy and work permit your
                    company depends on — sorted by what needs you today.
                </p>
                <div className="pitch-board">
                    {PITCH_ROWS.map((row) => (
                        <div className="pitch-row" key={row.name}>
                            <span className="item-name">
                                <span className="pitch-dot" style={{ background: row.color }} />
                                {row.name}
                            </span>
                            <span className="item-meta">{row.meta}</span>
                            <span className="item-meta">{row.owner}</span>
                        </div>
                    ))}
                </div>
            </div>

            <div className="auth-panel">
                <div className="auth-toggle" role="tablist" aria-label="Authentication mode">
                    <button
                        type="button"
                        className={mode === 'login' ? 'active' : ''}
                        onClick={() => setMode('login')}
                    >
                        Sign in
                    </button>
                    <button
                        type="button"
                        className={mode === 'register' ? 'active' : ''}
                        onClick={() => setMode('register')}
                    >
                        Register
                    </button>
                </div>

                {error && <p className="form-error">{error}</p>}

                {mode === 'login' ? (
                    <form onSubmit={handleSubmit}>
                        <h1>Welcome back</h1>
                        <p className="sub">Sign in to your company workspace.</p>
                        <div className="field">
                            <label htmlFor="login_email">Email</label>
                            <input
                                id="login_email"
                                type="email"
                                required
                                placeholder="you@company.com.my"
                                value={loginForm.email}
                                onChange={(event) => setLoginForm({ ...loginForm, email: event.target.value })}
                            />
                        </div>
                        <div className="field">
                            <label htmlFor="login_password">Password</label>
                            <input
                                id="login_password"
                                type="password"
                                required
                                placeholder="••••••••"
                                value={loginForm.password}
                                onChange={(event) => setLoginForm({ ...loginForm, password: event.target.value })}
                            />
                        </div>
                        <button type="submit" className="btn-primary full-width" disabled={busy}>
                            {busy ? 'Signing in…' : 'Sign in'}
                        </button>
                    </form>
                ) : (
                    <form onSubmit={handleSubmit}>
                        <h1>Set up your workspace</h1>
                        <p className="sub">Takes about a minute — no credit card.</p>
                        <div className="field">
                            <label htmlFor="register_name">Full name</label>
                            <input
                                id="register_name"
                                type="text"
                                required
                                placeholder="Nur Aisyah binti Rahman"
                                value={registerForm.name}
                                onChange={(event) => setRegisterForm({ ...registerForm, name: event.target.value })}
                            />
                        </div>
                        <div className="field">
                            <label htmlFor="register_email">Work email</label>
                            <input
                                id="register_email"
                                type="email"
                                required
                                placeholder="you@company.com.my"
                                value={registerForm.email}
                                onChange={(event) => setRegisterForm({ ...registerForm, email: event.target.value })}
                            />
                        </div>
                        <div className="field">
                            <label htmlFor="register_company">Company name</label>
                            <input
                                id="register_company"
                                type="text"
                                required
                                placeholder="Maju Jaya Sdn Bhd"
                                value={registerForm.company_name}
                                onChange={(event) =>
                                    setRegisterForm({ ...registerForm, company_name: event.target.value })
                                }
                            />
                        </div>
                        <div className="field">
                            <label htmlFor="register_reg_number">Registration number</label>
                            <input
                                id="register_reg_number"
                                type="text"
                                placeholder="Optional"
                                value={registerForm.company_registration_number}
                                onChange={(event) =>
                                    setRegisterForm({
                                        ...registerForm,
                                        company_registration_number: event.target.value,
                                    })
                                }
                            />
                        </div>
                        <div className="field">
                            <label htmlFor="register_password">Password</label>
                            <input
                                id="register_password"
                                type="password"
                                required
                                placeholder="At least 8 characters"
                                value={registerForm.password}
                                onChange={(event) =>
                                    setRegisterForm({ ...registerForm, password: event.target.value })
                                }
                            />
                        </div>
                        <div className="field">
                            <label htmlFor="register_password_confirmation">Confirm password</label>
                            <input
                                id="register_password_confirmation"
                                type="password"
                                required
                                placeholder="Repeat your password"
                                value={registerForm.password_confirmation}
                                onChange={(event) =>
                                    setRegisterForm({
                                        ...registerForm,
                                        password_confirmation: event.target.value,
                                    })
                                }
                            />
                        </div>
                        <button type="submit" className="btn-primary full-width" disabled={busy}>
                            {busy ? 'Creating workspace…' : 'Create workspace'}
                        </button>
                    </form>
                )}
            </div>
        </div>
    );
}
