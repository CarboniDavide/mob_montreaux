# CI/CD Setup - Complete Beginner's Guide

This repository includes automated testing and deployment workflows using GitHub Actions.

## 📖 What is CI/CD?

**CI (Continuous Integration)**: Automatically test your code every time you push changes.
**CD (Continuous Deployment)**: Automatically deploy your code to production after tests pass.

**Benefits:**
- 🛡️ Catch bugs before they reach production
- 🚀 Deploy faster and more confidently
- ✅ Ensure code quality with automated tests
- 📊 Get immediate feedback on your changes

---

## 🚀 Getting Started - First Time Setup

### Step 1: Push Your Code to GitHub

```bash
# If you haven't already, initialize git and push to GitHub
cd /path/to/mob_montreaux
git add .
git commit -m "Add CI/CD pipeline"
git push origin feature/prepare-a-complete-ci-cd
```

### Step 2: Check GitHub Actions is Enabled

1. Go to your GitHub repository: `https://github.com/CarboniDavide/mob_montreaux`
2. Click on the **"Actions"** tab at the top
3. If you see "Get started with GitHub Actions", click **"I understand my workflows, go ahead and enable them"**

### Step 3: View Your First Workflow Run

After pushing your code:

1. Go to: `https://github.com/CarboniDavide/mob_montreaux/actions`
2. You'll see your workflow runs listed by commit message
3. Click on any workflow run to see details
4. You'll see three jobs:
   - **✓ Run Backend Tests** (runs first)
   - **✓ Run Frontend Tests** (runs in parallel with backend tests)
   - **🚀 Deploy Application** (only runs if both test jobs pass and on `main` branch)

### Step 4: Understanding the Actions Tab

**What you'll see on the Actions page:**

```
Actions tab
├── All workflows
│   ├── CI/CD Pipeline (your workflow name)
│   │   ├── Run #1 - ✓ Success (green checkmark)
│   │   ├── Run #2 - ✗ Failed (red X)
│   │   └── Run #3 - ⚡ Running (yellow dot)
```

**Click on any run to see:**
- Which commit triggered it
- How long it took
- Detailed logs for each step
- Any errors that occurred

---

## 🔍 How to Check Your CI/CD Pipeline

### Method 1: GitHub Web Interface (Easiest)

#### View All Workflow Runs
```
https://github.com/CarboniDavide/mob_montreaux/actions
```

#### View Specific Workflow Run
1. Go to Actions tab
2. Click on any workflow run
3. Click on "Run Tests" or "Deploy Application" to see detailed logs

#### View Workflow Status on Commits
1. Go to your repository main page
2. You'll see colored indicators next to each commit:
   - ✅ Green checkmark = All tests passed
   - ❌ Red X = Tests failed
   - 🟡 Yellow dot = Running
   - ⚪ Gray circle = Pending

#### Example View:
```
Recent commits:
✅ abc123 - Add CI/CD pipeline (2 hours ago)
❌ def456 - Fix bug (3 hours ago) - Tests failed!
✅ ghi789 - Update README (5 hours ago)
```

### Method 2: Pull Request Checks

When you create a pull request:

1. Open a PR: `https://github.com/CarboniDavide/mob_montreaux/pulls`
2. Scroll to the bottom - you'll see:
   ```
   All checks have passed
   ✓ CI/CD Pipeline / Run Tests — Passed
   ```
3. Click "Details" to see the full logs

### Method 3: Email Notifications

GitHub automatically sends emails when:
- ❌ A workflow fails
- ✅ A previously failed workflow succeeds

Configure in: `GitHub Profile → Settings → Notifications → Actions`

### Method 4: Commit Status Badges (Optional)

Add a badge to your README to show build status:

1. Go to Actions tab
2. Click on "CI/CD Pipeline" workflow
3. Click the "..." menu → "Create status badge"
4. Copy the markdown and add to README.md

Example badge:
```markdown
![CI/CD Pipeline](https://github.com/CarboniDavide/mob_montreaux/actions/workflows/ci-cd.yml/badge.svg)
```

---

## 📊 Understanding Workflow Results

### ✅ Successful Run

```
Run Backend Tests            ✓ 2m 34s
├─ Checkout code            ✓ 5s
├─ Setup PHP                ✓ 45s
├─ Install dependencies     ✓ 1m 20s
├─ Run migrations           ✓ 3s
└─ Run PHPUnit tests        ✓ 21s

Run Frontend Tests           ✓ 1m 45s
├─ Checkout code            ✓ 5s
├─ Setup Node.js            ✓ 15s
├─ Cache node modules       ✓ 10s
├─ Install dependencies     ✓ 40s
├─ Generate Nuxt types      ✓ 8s
└─ Run Vitest tests         ✓ 27s (26 tests)

Deploy Application           ✓ 1m 15s
└─ Deploy to production     ✓ 1m 15s
```

**What this means:**
- All tests passed ✅
- Code is safe to deploy ✅
- Deployment happened automatically ✅

### ❌ Failed Run

```
Run Backend Tests            ✗ 1m 45s
├─ Checkout code            ✓ 5s
├─ Setup PHP                ✓ 45s
├─ Install dependencies     ✓ 1m 20s
├─ Run migrations           ✓ 3s
└─ Run PHPUnit tests        ✗ 12s
    Error: Test failed in StationTest.php

Run Frontend Tests           ✓ 1m 45s
├─ All Vitest tests passed  ✓ 27s (26 tests)

Deploy Application           ⊘ Skipped
```

**What this means:**
- Tests failed ❌
- Deployment was blocked (good!) 🛡️
- You need to fix the code ⚠️

---

## 🧪 Automated Testing

### GitHub Actions
Tests run automatically on:
- ✅ Push to `main` or `dev` branches
- ✅ Pull requests to `main` or `dev` branches

The workflow runs two parallel test jobs:

**Backend Tests:**
1. Sets up PHP 8.4 and PostgreSQL service
2. Installs Composer dependencies
3. Runs database migrations with SQLite
4. Executes all PHPUnit tests

**Frontend Tests:**
1. Sets up Node.js 20
2. Caches node_modules for faster builds
3. Installs npm dependencies
4. Generates Nuxt type definitions
5. Executes all Vitest tests (26 tests)

**Deployment:**
- Only runs if BOTH test jobs pass
- Only on `main` branch pushes

### Local Testing
Run tests locally before committing:

**Backend Tests:**
```bash
# From project root
cd backend
./test.sh

# Or run specific tests
./test.sh --filter=TestName
./test.sh --testsuite=Feature
```

**Frontend Tests:**
```bash
# From project root
cd frontend
npm test

# Or run with coverage
npm run test:coverage

# Or run in watch mode (for development)
npm run test:watch
```

---

## 🚀 Deployment Process

### Automatic Deployment (GitHub Actions)

**Happens when:**
- You push to `main` branch
- All backend tests pass ✅
- All frontend tests pass ✅

**What happens:**
1. Code is pushed to GitHub
2. Backend and frontend tests run in parallel
3. If BOTH test jobs pass → Deployment starts
4. Application is deployed

**To trigger automatic deployment:**
```bash
# Make your changes
git add .
git commit -m "Your changes"
git push origin main
```

**Then watch it happen:**
1. Go to: `https://github.com/CarboniDavide/mob_montreaux/actions`
2. See your workflow running in real-time
3. Get notified when complete

### Manual Deployment
Deploy manually using the deployment script:

```bash
# From project root
./deploy.sh
```

This script will:
1. ✓ Run all tests
2. ✓ Pull latest code
3. ✓ Update dependencies
4. ✓ Run migrations
5. ✓ Cache configuration
6. ✓ Restart services

**Note:** Deployment is aborted if tests fail.

---

## 🎯 Common Workflows

### Workflow 1: Working on a Feature Branch

```bash
# 1. Create feature branch
git checkout -b feature/my-feature

# 2. Make changes and commit
git add .
git commit -m "Add new feature"

# 3. Push to GitHub
git push origin feature/my-feature

# 4. Check GitHub Actions
# Go to: https://github.com/CarboniDavide/mob_montreaux/actions
# ✓ Tests will run automatically

# 5. Create Pull Request
# Go to: https://github.com/CarboniDavide/mob_montreaux/pulls
# Click "New Pull Request"
# Tests will run again on the PR

# 6. Merge when tests pass
# Tests pass → Click "Merge pull request"
```

### Workflow 2: Hotfix to Production

```bash
# 1. Create hotfix branch from main
git checkout main
git pull origin main
git checkout -b hotfix/critical-bug

# 2. Fix the bug
# ... make changes ...

# 3. Test locally first
cd backend
./test.sh

# 4. Commit and push
git add .
git commit -m "Fix critical bug"
git push origin hotfix/critical-bug

# 5. Create PR to main
# Tests will run automatically

# 6. Merge to main
# → Tests run
# → If pass → Auto-deploy to production!
```

### Workflow 3: Testing Before Deploy

```bash
# Option 1: Use manual deployment script (runs tests first)
./deploy.sh

# Option 2: Just run tests
cd backend
./test.sh

# Option 3: Check specific tests
cd backend
./test.sh --filter=AuthTest
```

---

## 📋 Setup Requirements

### GitHub Repository Setup

**Already done if you have this code in GitHub:**
- ✅ Repository exists
- ✅ GitHub Actions enabled
- ✅ Workflow file exists (`.github/workflows/ci-cd.yml`)

**Optional - GitHub Secrets (for remote server deployment):**

If deploying to a remote server, add these secrets:

1. Go to: `https://github.com/CarboniDavide/mob_montreaux/settings/secrets/actions`
2. Click "New repository secret"
3. Add these secrets:
   - `SERVER_HOST`: Your server's hostname or IP (e.g., `example.com`)
   - `SERVER_USER`: SSH username (e.g., `ubuntu`)
   - `SSH_PRIVATE_KEY`: Your SSH private key

**How to get SSH private key:**
```bash
# On your local machine
cat ~/.ssh/id_rsa
# Copy the entire output including:
# -----BEGIN OPENSSH PRIVATE KEY-----
# ... key content ...
# -----END OPENSSH PRIVATE KEY-----
```

### Local Requirements
- ✅ Docker and Docker Compose (already installed)
- ✅ PHP 8.4+ (already installed)
- ✅ Git (already installed)

---

## 🔧 Configuration Files

| File | Purpose |
|------|---------|
| `.github/workflows/ci-cd.yml` | GitHub Actions workflow - defines what happens on push/PR |
| `backend/test.sh` | Test runner script - runs backend tests safely with SQLite |
| `backend/.env.testing` | Testing environment - uses SQLite instead of PostgreSQL |
| `backend/phpunit.xml` | PHPUnit configuration - backend test settings |
| `frontend/vitest.config.ts` | Vitest configuration - frontend test settings |
| `frontend/tests/setup.ts` | Global test mocks - Nuxt composables and browser APIs |
| `deploy.sh` | Deployment script - manual deployment with tests |

---

## 🐛 Troubleshooting

### "I pushed code but don't see Actions running"

**Solution:**
1. Check Actions tab is enabled: `https://github.com/CarboniDavide/mob_montreaux/actions`
2. Verify workflow file exists: `.github/workflows/ci-cd.yml`
3. Make sure you pushed to `main` or `dev` branch

### "Tests fail on GitHub but pass locally"

**Possible causes:**
1. **Different PHP versions**: GitHub uses PHP 8.4, check your local version
   ```bash
   php -v
   ```

2. **Environment differences**: GitHub uses fresh environment
   ```bash
   # Clear local cache
   cd backend
   php artisan config:clear
   ./test.sh
   ```

3. **Database differences**: Make sure tests use SQLite
   ```bash
   # Check backend/phpunit.xml has:
   # <env name="DB_CONNECTION" value="sqlite"/>
   # <env name="DB_DATABASE" value=":memory:"/>
   ```

### "How do I view detailed test failures?"

**For Backend Tests:**
1. Go to: `https://github.com/CarboniDavide/mob_montreaux/actions`
2. Click on the failed workflow run
3. Click on "Run Backend Tests" job
4. Click on "Run PHPUnit tests" step
5. Scroll through logs to see exact error

**For Frontend Tests:**
1. Go to: `https://github.com/CarboniDavide/mob_montreaux/actions`
2. Click on the failed workflow run
3. Click on "Run Frontend Tests" job
4. Click on "Run Vitest tests" step
5. Scroll through logs to see which tests failed

**Common Frontend Test Issues:**
- **Missing Nuxt types**: Fixed by `npx nuxi prepare` step in CI
- **Mocking errors**: Check `frontend/tests/setup.ts` for global mocks
- **Store tests failing**: Ensure `mockApiFetch` is properly configured

### "Deployment didn't happen after tests passed"

**Check:**
1. Are you on `main` branch?
   ```bash
   git branch --show-current
   ```
2. Look at workflow logs - deployment job should show
3. Verify the deployment configuration in `ci-cd.yml`

### "I want to test the workflow before merging to main"

**Solution:** Push to `dev` branch first
```bash
git checkout -b dev
git push origin dev
# Tests will run but won't deploy
```

---

## 📚 Next Steps

### Learn More About GitHub Actions

- **GitHub Actions Documentation**: https://docs.github.com/en/actions
- **Workflow syntax**: https://docs.github.com/en/actions/reference/workflow-syntax-for-github-actions
- **Example workflows**: https://github.com/actions/starter-workflows

### Improve Your CI/CD

1. **Add code coverage reports**
   ```bash
   # Backend
   vendor/bin/phpunit --coverage-html coverage
   
   # Frontend
   npm run test:coverage
   ```

2. **Add code quality checks** (PHPStan, PHP CS Fixer, ESLint)
3. **Add security scanning**
4. **Add E2E tests** (Playwright, Cypress)
5. **Add staging environment**
6. **Set coverage thresholds** (e.g., 80% minimum)
7. **Add component tests** for Vue pages

### Monitor Your Deployments

- Set up error tracking (Sentry, Bugsnag)
- Add uptime monitoring (UptimeRobot, Pingdom)
- Configure deployment notifications (Slack, Discord)

---

## 🎓 Learning Resources

- [GitHub Actions Tutorial](https://docs.github.com/en/actions/quickstart)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing Guide](https://laravel.com/docs/testing)
- [CI/CD Best Practices](https://www.atlassian.com/continuous-delivery/principles/continuous-integration-vs-delivery-vs-deployment)

---

## 💡 Pro Tips

1. **Always check Actions tab after pushing** - catch failures early
2. **Test locally before pushing** - saves time and CI minutes
3. **Use feature branches** - keep `main` stable
4. **Review test logs** - understand why tests fail
5. **Keep tests fast** - faster feedback loop

**Happy deploying! 🚀**
