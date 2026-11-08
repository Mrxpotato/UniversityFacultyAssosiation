// @ts-check
import { test } from '@playwright/test';
import fs from 'node:fs';
import path from 'node:path';

const OUT = 'ui-audit';
fs.mkdirSync(OUT, { recursive: true });

// The audit walks many pages sequentially, so allow more than the 30s default.
test.setTimeout(180_000);

/** @type {any[]} */
const report = [];

const PUBLIC = [
  ['home', '/'],
  ['login', '/login'],
  ['register', '/register'],
  ['transparency', '/transparency'],
  ['trainings', '/trainings'],
  ['trainings-calendar', '/trainings/calendar'],
  ['organization', '/organization'],
  ['campuses', '/campuses'],
];

const ADMIN = [
  ['admin-dashboard', '/dashboard'],
  ['admin-faculty-profiles', '/admin/faculty-profiles'],
  ['admin-pending-registrations', '/admin/pending-registrations'],
  ['admin-claims-monitoring', '/admin/claims-monitoring'],
  ['admin-fund', '/admin/fund'],
  ['admin-dues', '/admin/dues'],
  ['admin-campuses', '/admin/campuses'],
  ['admin-deductions', '/admin/deductions'],
  ['admin-member-create', '/admin/members/create'],
  ['admin-transparency-wall', '/admin/transparency-wall'],
  ['profile', '/profile'],
];

const FACULTY = [
  ['faculty-dashboard', '/dashboard'],
  ['faculty-claims', '/claims'],
  ['faculty-beneficiaries', '/beneficiaries'],
  ['faculty-my-profile', '/my-profile'],
];

/** @param {import('@playwright/test').Page} page */
async function login(page, employeeId) {
  await page.goto('/login');
  const form = page.locator('form[action$="/login"]');
  await form.locator('input[name="employee_id"]').fill(employeeId);
  await form.locator('input[name="password"]').fill('password');
  await form.locator('button[type="submit"]').click();
  await page.waitForURL('**/dashboard', { timeout: 20000 });
}

/**
 * @param {import('@playwright/test').Page} page
 */
async function auditPage(page, name, url, group) {
  const consoleErrors = [];
  const failed = [];
  const onConsole = (m) => { if (m.type() === 'error') consoleErrors.push(m.text()); };
  const onFailed = (r) => failed.push(`${r.method()} ${r.url()} ${r.failure()?.errorText ?? ''}`);

  page.on('console', onConsole);
  page.on('requestfailed', onFailed);

  let status = null;
  try {
    const res = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 20000 });
    status = res?.status() ?? null;
  } catch {
    status = -1;
  }
  await page.waitForTimeout(1000);

  const data = await page.evaluate(() => {
    const doc = document;
    const describe = (el) => ({
      tag: el.tagName.toLowerCase(),
      id: el.id || null,
      cls: typeof el.className === 'string' ? el.className.slice(0, 100) : null,
      text: (el.textContent || '').trim().replace(/\s+/g, ' ').slice(0, 70),
    });

    const imgs = [...doc.images];
    const controls = [...doc.querySelectorAll('input,select,textarea')].filter((el) => {
      const t = (el.type || '').toLowerCase();
      return !['hidden', 'submit', 'button', 'reset', 'image'].includes(t);
    });
    const labelled = (el) => {
      if (el.getAttribute('aria-label') || el.getAttribute('aria-labelledby')) return true;
      if (el.id && doc.querySelector(`label[for="${CSS.escape(el.id)}"]`)) return true;
      if (el.closest('label')) return true;
      return false;
    };
    const ctlInfo = (el) => ({
      tag: el.tagName.toLowerCase(),
      type: el.getAttribute('type'),
      name: el.getAttribute('name'),
      id: el.id || null,
      ph: el.getAttribute('placeholder'),
      aria: el.getAttribute('aria-label'),
      val: (el.value || '').slice(0, 30),
    });
    const clickables = [...doc.querySelectorAll('a,button')];
    const accessibleName = (el) =>
      (el.getAttribute('aria-label') || el.getAttribute('title') || el.textContent || '').trim();

    const ids = {};
    [...doc.querySelectorAll('[id]')].forEach((el) => { ids[el.id] = (ids[el.id] || 0) + 1; });

    // Contrast helpers
    const parseColor = (c) => {
      const m = c.match(/rgba?\(([^)]+)\)/);
      if (!m) return null;
      const p = m[1].split(',').map((x) => parseFloat(x));
      return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
    };
    const lum = ({ r, g, b }) => {
      const f = (v) => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); };
      return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b);
    };
    const effBg = (el) => {
      let e = el;
      while (e) {
        const bg = parseColor(getComputedStyle(e).backgroundColor);
        if (bg && bg.a > 0.05) return bg;
        e = e.parentElement;
      }
      return { r: 255, g: 255, b: 255, a: 1 };
    };
    const lowContrast = [];
    [...doc.querySelectorAll('p,span,a,li,td,th,label,h1,h2,h3,h4,h5,h6,button')].forEach((el) => {
      const hasOwnText = [...el.childNodes].some((n) => n.nodeType === 3 && n.textContent.trim().length > 1);
      if (!hasOwnText) return;
      const cs = getComputedStyle(el);
      if (cs.display === 'none' || cs.visibility === 'hidden' || parseFloat(cs.opacity) === 0) return;
      const fg = parseColor(cs.color);
      if (!fg || fg.a < 0.9) return;
      const bg = effBg(el);
      const L1 = lum(fg), L2 = lum(bg);
      const ratio = (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
      const fontSize = parseFloat(cs.fontSize);
      const bold = parseInt(cs.fontWeight, 10) >= 700;
      const large = fontSize >= 24 || (fontSize >= 18.66 && bold);
      const min = large ? 3 : 4.5;
      if (ratio < min) {
        lowContrast.push({ ...describe(el), ratio: +ratio.toFixed(2), min, fontSize, color: cs.color, bg: `rgb(${bg.r},${Math.round(bg.g)},${Math.round(bg.b)})` });
      }
    });

    const de = doc.documentElement;
    const offenders = [];
    [...doc.querySelectorAll('*')].forEach((el) => {
      const r = el.getBoundingClientRect();
      if (r.width > 0 && r.right > window.innerWidth + 1) {
        offenders.push({ ...describe(el), right: Math.round(r.right), width: Math.round(r.width) });
      }
    });

    const smallTargets = clickables
      .filter((el) => {
        const r = el.getBoundingClientRect();
        return r.width > 0 && r.height > 0 && (r.width < 24 || r.height < 24);
      })
      .map((el) => ({ ...describe(el), w: Math.round(el.getBoundingClientRect().width), h: Math.round(el.getBoundingClientRect().height) }));

    return {
      title: doc.title,
      lang: doc.documentElement.lang,
      viewport: doc.querySelector('meta[name="viewport"]')?.content ?? null,
      metaDescription: doc.querySelector('meta[name="description"]')?.content ?? null,
      nodes: doc.querySelectorAll('*').length,
      pageHeight: doc.body.scrollHeight,
      images: {
        total: imgs.length,
        missingAlt: imgs.filter((i) => !i.hasAttribute('alt')).map(describe),
        broken: imgs.filter((i) => i.complete && i.naturalWidth === 0).map((i) => i.currentSrc || i.src),
      },
      unlabeledControls: controls.filter((el) => !labelled(el)).map(ctlInfo),
      emptyClickables: clickables.filter((el) => accessibleName(el) === '').map((el) => ({ ...describe(el), role: el.getAttribute('role'), toggle: el.closest('[x-data]') ? 'x-data' : null })),
      duplicateIds: Object.entries(ids).filter(([, c]) => c > 1).map(([id, count]) => ({ id, count })),
      headings: [...doc.querySelectorAll('h1,h2,h3,h4,h5,h6')].map((h) => ({ level: +h.tagName[1], text: (h.textContent || '').trim().replace(/\s+/g, ' ').slice(0, 55) })),
      h1Count: doc.querySelectorAll('h1').length,
      horizontalOverflow: de.scrollWidth > window.innerWidth + 1,
      scrollWidth: de.scrollWidth,
      innerWidth: window.innerWidth,
      overflowingElements: offenders.slice(0, 20),
      smallTargets: smallTargets.slice(0, 30),
      unsafeTargetBlank: [...doc.querySelectorAll('a[target="_blank"]')].filter((a) => !/noopener/.test(a.rel)).map((a) => a.href).slice(0, 15),
      lowContrast: lowContrast.slice(0, 30),
    };
  });

  report.push({ name, group, url, status, consoleErrors, failedRequests: failed, ...data });

  page.off('console', onConsole);
  page.off('requestfailed', onFailed);
}

test('public pages', async ({ page }) => {
  for (const [n, u] of PUBLIC) await auditPage(page, n, u, 'public');
});

test('admin pages', async ({ page }) => {
  await login(page, 'ADMIN-001');
  for (const [n, u] of ADMIN) await auditPage(page, n, u, 'admin');
});

test('faculty pages', async ({ page }) => {
  await page.context().clearCookies();
  await login(page, 'FAC-001');
  for (const [n, u] of FACULTY) await auditPage(page, n, u, 'faculty');
});

test.afterAll(async () => {
  fs.writeFileSync(path.join(OUT, 'report.json'), JSON.stringify(report, null, 2));
});
