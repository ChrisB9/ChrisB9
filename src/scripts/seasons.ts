type Season = 'spring' | 'summer' | 'autumn' | 'winter';

const ORDER: Season[] = ['spring', 'summer', 'autumn', 'winter'];

interface Particle {
  x: number;
  y: number;
  size: number;
  vx: number;
  vy: number;
  phase: number;
  rot: number;
  vr: number;
  alpha: number;
  color: string;
  shape: Path2D | null;
}

const MAPLE = new Path2D(
  'M0 -11 L2.4 -4.6 L8.6 -7.6 L5.6 -1.6 L11 0.6 L5 2.6 L6.8 8 L1.2 4.6 L0 11 L-1.2 4.6 L-6.8 8 L-5 2.6 L-11 0.6 L-5.6 -1.6 L-8.6 -7.6 L-2.4 -4.6 Z',
);
const GINKGO = new Path2D('M0 10 L0 2 M0 2 C-9 0 -11 -8 -9 -10 C-5 -7 5 -7 9 -10 C11 -8 9 0 0 2 Z');
const PETAL = new Path2D('M0 8 C-6 4 -6 -4 -2 -8 L0 -5 L2 -8 C6 -4 6 4 0 8 Z');

const pick = <T>(items: readonly T[]) => items[Math.floor(Math.random() * items.length)];
const between = (min: number, max: number) => min + Math.random() * (max - min);

const SEASONS: Record<
  Season,
  { count: [number, number]; spawn: (w: number, h: number, initial: boolean) => Particle }
> = {
  spring: {
    count: [14, 24],
    spawn: (w, h, initial) => ({
      x: Math.random() * w,
      y: initial ? Math.random() * h : -20,
      size: between(0.5, 1),
      vx: between(0.1, 0.4),
      vy: between(0.3, 0.6),
      phase: Math.random() * Math.PI * 2,
      rot: Math.random() * Math.PI * 2,
      vr: between(-0.02, 0.02),
      alpha: between(0.25, 0.5),
      color: pick(['#ffc1d6', '#ffb0c8', '#ffe1ea']),
      shape: PETAL,
    }),
  },
  summer: {
    count: [10, 18],
    spawn: (w, h) => ({
      x: Math.random() * w,
      y: between(h * 0.25, h),
      size: between(1.6, 3),
      vx: between(-0.2, 0.2),
      vy: between(-0.15, 0.05),
      phase: Math.random() * Math.PI * 2,
      rot: 0,
      vr: between(0.01, 0.025),
      alpha: 0.85,
      color: pick(['#e9f77a', '#f6d365']),
      shape: null,
    }),
  },
  autumn: {
    count: [14, 24],
    spawn: (w, h, initial) => {
      const ginkgo = Math.random() < 0.28;
      return {
        x: Math.random() * w,
        y: initial ? Math.random() * h : -20,
        size: between(0.5, 1.1),
        vx: 0,
        vy: between(0.25, 0.65),
        phase: Math.random() * Math.PI * 2,
        rot: Math.random() * Math.PI * 2,
        vr: between(-0.01, 0.01),
        alpha: between(0.2, 0.45),
        color: ginkgo ? '#f2b544' : pick(['#e5533a', '#f07a3a', '#c8143c']),
        shape: ginkgo ? GINKGO : MAPLE,
      };
    },
  },
  winter: {
    count: [24, 40],
    spawn: (w, h, initial) => ({
      x: Math.random() * w,
      y: initial ? Math.random() * h : -10,
      size: between(1, 2.6),
      vx: 0,
      vy: between(0.3, 0.8),
      phase: Math.random() * Math.PI * 2,
      rot: 0,
      vr: 0,
      alpha: between(0.35, 0.75),
      color: '#f0f4ff',
      shape: null,
    }),
  },
};

export function mountSeasons(canvas: HTMLCanvasElement) {
  const root = document.documentElement;
  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  let season = ORDER[0];
  let config = SEASONS[season];
  const reduce = matchMedia('(prefers-reduced-motion: reduce)');
  let w = 0;
  let h = 0;
  let particles: Particle[] = [];
  let frame = 0;

  const resize = () => {
    const dpr = Math.min(2, devicePixelRatio || 1);
    w = innerWidth;
    h = innerHeight;
    canvas.width = w * dpr;
    canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  };

  const step = (p: Particle) => {
    p.phase += season === 'summer' ? p.vr : 0.012;
    p.rot += season === 'summer' ? 0 : p.vr;
    p.x += p.vx + (season === 'summer' ? Math.sin(p.phase * 0.7) * 0.25 : 0);
    p.y += p.vy + (season === 'summer' ? Math.cos(p.phase * 0.5) * 0.2 : 0);
  };

  const outside = (p: Particle) =>
    season === 'summer' ? p.x < -20 || p.x > w + 20 || p.y < -20 || p.y > h + 20 : p.y > h + 20;

  const draw = () => {
    ctx.clearRect(0, 0, w, h);
    for (const p of particles) {
      ctx.save();
      ctx.fillStyle = p.color;
      ctx.strokeStyle = p.color;
      if (season === 'summer') {
        ctx.globalAlpha = p.alpha * (0.3 + 0.7 * Math.max(0, Math.sin(p.phase * 2)));
        ctx.shadowColor = p.color;
        ctx.shadowBlur = 10;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fill();
      } else if (!p.shape) {
        ctx.globalAlpha = p.alpha;
        ctx.beginPath();
        ctx.arc(p.x + Math.sin(p.phase) * 12, p.y, p.size, 0, Math.PI * 2);
        ctx.fill();
      } else {
        ctx.globalAlpha = p.alpha;
        ctx.translate(p.x + Math.sin(p.phase) * 18, p.y);
        ctx.rotate(p.rot);
        ctx.scale(p.size * (season === 'spring' ? Math.cos(p.phase * 2) : 1), p.size);
        ctx.fill(p.shape);
        if (p.shape === GINKGO) ctx.stroke(p.shape);
      }
      ctx.restore();
    }
  };

  const tick = () => {
    for (let i = 0; i < particles.length; i++) {
      step(particles[i]);
      if (outside(particles[i])) particles[i] = config.spawn(w, h, false);
    }
    draw();
    frame = requestAnimationFrame(tick);
  };

  const enabled = () => root.dataset.seasons !== 'off' && !reduce.matches && !document.hidden;

  const sync = () => {
    cancelAnimationFrame(frame);
    const current = root.dataset.season as Season;
    if (current !== season && current in SEASONS) {
      season = current;
      config = SEASONS[season];
      particles = [];
    }
    if (!enabled()) {
      ctx.clearRect(0, 0, w, h);
      return;
    }
    if (!particles.length) {
      const count = w < 640 ? config.count[0] : config.count[1];
      particles = Array.from({ length: count }, () => config.spawn(w, h, true));
    }
    frame = requestAnimationFrame(tick);
  };

  const cycle = (event: KeyboardEvent) => {
    if (!event.shiftKey || event.code !== 'KeyS' || event.ctrlKey || event.metaKey || event.altKey)
      return;
    if (
      event.target instanceof HTMLElement &&
      event.target.closest('input, textarea, select, [contenteditable]')
    )
      return;
    // not persisted: a reload shows the real season again
    root.dataset.season = ORDER[(ORDER.indexOf(season) + 1) % ORDER.length];
    delete root.dataset.seasons;
    document.dispatchEvent(new Event('seasons-change'));
  };

  resize();
  addEventListener('resize', resize);
  addEventListener('keydown', cycle);
  document.addEventListener('visibilitychange', sync);
  document.addEventListener('seasons-change', sync);
  reduce.addEventListener('change', sync);
  sync();
}
