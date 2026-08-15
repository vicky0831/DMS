import { useEffect, useState } from 'react';

type Tone = 'alert' | 'amber' | 'jade' | 'paper';

type FlapTileProps = {
    value: number;
    label: string;
    hint: string;
    tone: Tone;
    delay?: number;
};

export function FlapTile({ value, label, hint, tone, delay = 0 }: FlapTileProps) {
    const [entered, setEntered] = useState(false);

    useEffect(() => {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (reduceMotion) {
            setEntered(true);
            return;
        }

        const timer = window.setTimeout(() => setEntered(true), delay);
        return () => window.clearTimeout(timer);
    }, [delay]);

    return (
        <div className={`flap-tile tone-${tone} ${entered ? 'in' : ''}`}>
            <div className="flap-num">{String(value).padStart(2, '0')}</div>
            <div className="flap-seam" />
            <div className="flap-label">{label}</div>
            <div className="flap-hint">{hint}</div>
        </div>
    );
}
