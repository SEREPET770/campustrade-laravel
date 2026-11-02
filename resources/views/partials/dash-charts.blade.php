<script>
    window.CTChart = (() => {
        const NS = 'http://www.w3.org/2000/svg';
        let uid = 0;

        const el = (tag, attrs = {}, parent = null) => {
            const node = document.createElementNS(NS, tag);
            Object.entries(attrs).forEach(([k, v]) => node.setAttribute(k, v));
            if (parent) parent.appendChild(node);
            return node;
        };
        const fmt = (n) => Number(n).toLocaleString('id-ID');

        function niceStep(raw) {
            const v = Math.max(1, raw);
            const p = 10 ** Math.floor(Math.log10(v));
            const n = v / p;
            return (n <= 1 ? 1 : n <= 2 ? 2 : n <= 5 ? 5 : 10) * p;
        }

        function line(box, {
            labels,
            series
        }) {
            box.textContent = '';
            const W = 640,
                H = 250,
                L = 38,
                R = 12,
                T = 12,
                B = 30;
            const svg = el('svg', {
                viewBox: `0 0 ${W} ${H}`,
                class: 'chart-svg',
                role: 'img',
                'aria-label': 'Grafik garis'
            }, box);
            const defs = el('defs', {}, svg);

            const max = Math.max(0, ...series.flatMap((s) => s.values));
            const step = niceStep(max / 4);
            const top = step * 4;
            const n = labels.length;
            const x = (i) => L + (n <= 1 ? (W - L - R) / 2 : (i * (W - L - R)) / (n - 1));
            const y = (v) => T + (H - T - B) * (1 - v / top);

            for (let i = 0; i <= 4; i++) {
                const gy = y(step * i);
                el('line', {
                    x1: L,
                    x2: W - R,
                    y1: gy,
                    y2: gy,
                    class: 'chart-grid'
                }, svg);
                const t = el('text', {
                    x: L - 8,
                    y: gy + 4,
                    'text-anchor': 'end',
                    class: 'chart-axis'
                }, svg);
                t.textContent = fmt(step * i);
            }

            const every = Math.max(1, Math.ceil(n / 7));
            labels.forEach((lb, i) => {
                if ((n - 1 - i) % every !== 0) return;
                const t = el('text', {
                    x: x(i),
                    y: H - 8,
                    'text-anchor': 'middle',
                    class: 'chart-axis'
                }, svg);
                t.textContent = lb;
            });

            series.forEach((s) => {
                const id = `ctg${++uid}`;
                const grad = el('linearGradient', {
                    id,
                    x1: 0,
                    y1: 0,
                    x2: 0,
                    y2: 1
                }, defs);
                el('stop', {
                    offset: '0%',
                    'stop-color': s.color,
                    'stop-opacity': 0.22
                }, grad);
                el('stop', {
                    offset: '100%',
                    'stop-color': s.color,
                    'stop-opacity': 0
                }, grad);

                const pts = s.values.map((v, i) => [x(i), y(v)]);
                const d = pts.map(([px, py], i) => `${i ? 'L' : 'M'}${px.toFixed(1)},${py.toFixed(1)}`)
                    .join(' ');

                if (pts.length > 1) {
                    const last = pts[pts.length - 1][0].toFixed(1);
                    const first = pts[0][0].toFixed(1);
                    el('path', {
                        d: `${d} L${last},${y(0)} L${first},${y(0)} Z`,
                        fill: `url(#${id})`
                    }, svg);
                }
                el('path', {
                    d,
                    fill: 'none',
                    stroke: s.color,
                    'stroke-width': 2.4,
                    'stroke-linejoin': 'round',
                    'stroke-linecap': 'round'
                }, svg);

                if (n <= 31) {
                    pts.forEach(([px, py], i) => {
                        const c = el('circle', {
                            cx: px,
                            cy: py,
                            r: 3.2,
                            fill: '#fff',
                            stroke: s.color,
                            'stroke-width': 2
                        }, svg);
                        el('title', {}, c).textContent =
                            `${s.name}: ${fmt(s.values[i])} (${labels[i]})`;
                    });
                }
            });
        }

        function donut(box, items, total, label) {
            box.textContent = '';
            const S = 190,
                r = 70,
                sw = 26,
                mid = S / 2,
                c = 2 * Math.PI * r;
            const svg = el('svg', {
                viewBox: `0 0 ${S} ${S}`,
                class: 'donut-svg',
                role: 'img',
                'aria-label': label
            }, box);
            el('circle', {
                cx: mid,
                cy: mid,
                r,
                fill: 'none',
                stroke: '#eef2f7',
                'stroke-width': sw
            }, svg);

            const sum = items.reduce((a, b) => a + b.value, 0);
            let offset = 0;
            if (sum > 0) {
                items.forEach((it) => {
                    if (!it.value) return;
                    const len = (it.value / sum) * c;
                    const seg = el('circle', {
                        cx: mid,
                        cy: mid,
                        r,
                        fill: 'none',
                        stroke: it.color,
                        'stroke-width': sw,
                        'stroke-dasharray': `${len} ${c - len}`,
                        'stroke-dashoffset': -offset,
                        transform: `rotate(-90 ${mid} ${mid})`,
                    }, svg);
                    el('title', {}, seg).textContent = `${it.label}: ${fmt(it.value)}`;
                    offset += len;
                });
            }

            el('text', {
                x: mid,
                y: mid + 4,
                'text-anchor': 'middle',
                class: 'donut-total'
            }, svg).textContent = fmt(total);
            el('text', {
                x: mid,
                y: mid + 24,
                'text-anchor': 'middle',
                class: 'donut-label'
            }, svg).textContent = label;
        }

        return {
            line,
            donut
        };
    })();
</script>
