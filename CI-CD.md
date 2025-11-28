# CI/CD Setup

This repository includes automated testing and deployment workflows.

## 🧪 Automated Testing

### GitHub Actions
Tests run automatically on:
- Push to `main` or `develop` branches
- Pull requests to `main` or `develop` branches

The workflow:
1. Sets up PHP 8.4 and PostgreSQL
2. Installs dependencies
3. Runs database migrations
4. Executes all PHPUnit tests
5. Deploys if all tests pass (only on `main` branch)

### Local Testing
Run tests locally before committing:

```bash
# From project root
cd backend
./test.sh

# Or run specific tests
./test.sh --filter=TestName
./test.sh --testsuite=Feature
```

## 🚀 Deployment

### Automatic Deployment (GitHub Actions)
When you push to the `main` branch:
1. Tests run automatically
2. If tests pass, deployment starts
3. Application is deployed to production

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

## 📋 Setup Requirements

### GitHub Secrets (for deployment)
If deploying to a remote server, add these secrets in GitHub repository settings:

- `SERVER_HOST`: Your server's hostname or IP
- `SERVER_USER`: SSH username
- `SSH_PRIVATE_KEY`: SSH private key for authentication

### Local Requirements
- Docker and Docker Compose
- PHP 8.4+ (for local testing)
- Git

## 🔧 Configuration Files

- `.github/workflows/ci-cd.yml`: GitHub Actions workflow
- `backend/test.sh`: Test runner script
- `backend/.env.testing`: Testing environment configuration
- `backend/phpunit.xml`: PHPUnit configuration
- `deploy.sh`: Deployment script

## 📊 Test Coverage

Run tests with coverage (requires Xdebug):

```bash
cd backend
vendor/bin/phpunit --coverage-html coverage
```

Open `backend/coverage/index.html` in your browser.

## 🐛 Troubleshooting

### Tests fail locally but pass in CI
- Ensure you're using SQLite in-memory for tests
- Check `.env.testing` configuration
- Clear cache: `php artisan config:clear`

### Deployment fails
- Check GitHub Actions logs
- Verify server credentials in GitHub Secrets
- Test SSH connection manually

### Database issues
- Run migrations: `php artisan migrate --force`
- Reset database: `php artisan migrate:fresh --seed`
- Check database connection in `.env`

## 📚 Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing](https://laravel.com/docs/testing)
