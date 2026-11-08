// @ts-check
import { defineConfig, devices } from '@playwright/test';
import path from 'node:path';

/**
 * Dedicated SQLite database for E2E runs so the developer database is never
 * touched. It is migrated + seeded by the webServer command below.
 */
const E2E_DB = path.resolve('database/e2e.sqlite');
const E2E_PORT = 8123;
const BASE_URL = `http://127.0.0.1:${E2E_PORT}`;

/**
 * Environment shared by the artisan migrate/seed step and the dev server.
 * Turnstile is disabled so the bot-protection widget doesn't block tests.
 * @type {Record<string, string>}
 */
const e2eEnv = {
  APP_ENV: 'local',
  DB_CONNECTION: 'sqlite',
  DB_DATABASE: E2E_DB,
  CLOUDFLARE_TURNSTILE_SITE_KEY: '',
  CLOUDFLARE_TURNSTILE_SECRET: '',
};

/**
 * @see https://playwright.dev/docs/test-configuration
 */
export default defineConfig({
  testDir: './playwright_test',
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  // SQLite can be finicky under heavy concurrency; keep it serial + stable.
  workers: 1,
  reporter: 'list',
  timeout: 30_000,

  use: {
    baseURL: BASE_URL,
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
  },

  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
  ],

  /* Prepare the E2E database and boot the Laravel app before the tests run. */
  webServer: {
    command: 'php artisan migrate:fresh --seed --force && php artisan serve --host=127.0.0.1 --port=' + E2E_PORT,
    url: `${BASE_URL}/up`,
    reuseExistingServer: !process.env.CI,
    timeout: 180_000,
    env: e2eEnv,
  },
});
