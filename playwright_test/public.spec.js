// @ts-check
import { test, expect } from '@playwright/test';

test.describe('Public pages', () => {
  test('home page renders', async ({ page }) => {
    const response = await page.goto('/');

    expect(response?.status()).toBe(200);
    await expect(
      page.getByText('University Faculty Association').first()
    ).toBeVisible();
  });

  for (const path of ['/transparency', '/trainings', '/organization', '/campuses']) {
    test(`renders ${path}`, async ({ page }) => {
      const response = await page.goto(path);

      expect(response?.status()).toBe(200);
    });
  }

  test('login page renders the sign-in form', async ({ page }) => {
    await page.goto('/login');

    await expect(page.getByRole('heading', { name: 'Welcome Back' })).toBeVisible();
    await expect(
      page.locator('form[action$="/login"] input[name="employee_id"]')
    ).toBeVisible();
  });

  test('register page renders the request-account form', async ({ page }) => {
    await page.goto('/register');

    await expect(page.getByRole('heading', { name: 'Request Account' })).toBeVisible();
  });
});
