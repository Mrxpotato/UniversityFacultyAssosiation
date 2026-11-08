// @ts-check
import { test, expect } from '@playwright/test';

const PASSWORD = 'password';

/**
 * Log in through the real UI (login uses Employee ID, not email).
 * @param {import('@playwright/test').Page} page
 * @param {string} employeeId
 */
async function login(page, employeeId) {
  await page.goto('/login');
  const form = page.locator('form[action$="/login"]');
  await form.locator('input[name="employee_id"]').fill(employeeId);
  await form.locator('input[name="password"]').fill(PASSWORD);
  await form.getByRole('button', { name: /sign in/i }).click();
}

test.describe('Authentication', () => {
  test('a faculty member can sign in and reach the dashboard', async ({ page }) => {
    await login(page, 'FAC-001');

    await page.waitForURL('**/dashboard');
    expect(page.url()).toContain('/dashboard');
    await expect(page).not.toHaveURL(/\/login/);
  });

  test('a super admin can sign in and reach the dashboard', async ({ page }) => {
    await login(page, 'ADMIN-001');

    await page.waitForURL('**/dashboard');
    expect(page.url()).toContain('/dashboard');
  });

  test('invalid credentials are rejected', async ({ page }) => {
    await login(page, 'NOPE-9999');

    await expect(
      page
        .locator('form[action$="/login"]')
        .getByText('These credentials do not match our records.')
    ).toBeVisible();
    expect(page.url()).toContain('/login');
  });

  test('an authenticated user can log out', async ({ page }) => {
    await login(page, 'FAC-001');
    await page.waitForURL('**/dashboard');

    // GET /logout shows a confirmation screen.
    await page.goto('/logout');
    await expect(page.getByRole('heading', { name: 'Log Out' })).toBeVisible();

    await page.getByRole('button', { name: /yes, log out/i }).click();
    await page.waitForURL(url => new URL(url).pathname === '/');

    // The session is gone: protected pages redirect to login.
    await page.goto('/dashboard');
    await expect(page).toHaveURL(/\/login/);
  });

  test('guests are redirected away from protected pages', async ({ page }) => {
    await page.goto('/dashboard');

    await expect(page).toHaveURL(/\/login/);
  });
});
