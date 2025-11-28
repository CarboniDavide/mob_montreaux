# Docker Configuration Guide

This project uses separate Docker configurations for development and production environments.

## Files Structure

### Backend
- `backend/Dockerfile` - Production build (optimized, no dev dependencies)
- `backend/Dockerfile.dev` - Development build (includes Xdebug, dev tools)
- `backend/docker/php/Dockerfile` - Legacy file (can be removed)

### Frontend
- `frontend/Dockerfile` - Production build (multi-stage, optimized)
- `frontend/Dockerfile.dev` - Development build (hot reload enabled)

### Docker Compose
- `docker-compose.yml` - Development environment
- `docker-compose.prod.yml` - Production environment

## Key Differences

### Backend: Development vs Production

#### Development (`Dockerfile.dev`)
- ✅ Xdebug enabled (debugging support)
- ✅ All composer dependencies (including dev)
- ✅ Git and unzip for troubleshooting
- ✅ OPcache with file validation (sees code changes immediately)
- ✅ Volume mounted (live code updates)
- ⚠️ Runs as root (easier file permissions)

#### Production (`Dockerfile`)
- ✅ Optimized OPcache (no file validation)
- ✅ Only production composer dependencies (--no-dev)
- ✅ Smaller image size
- ✅ Better security (runs as www-data)
- ✅ No Xdebug overhead
- ⚠️ No dev tools
- ⚠️ Code baked into image (no live updates)

### Frontend: Development vs Production

#### Development (`Dockerfile.dev`)
- ✅ Hot Module Replacement (HMR)
- ✅ Instant code updates
- ✅ Development server
- ✅ Volume mounted source code
- ⚠️ Slower performance

#### Production (`Dockerfile`)
- ✅ Multi-stage build (smaller final image)
- ✅ Optimized SSR bundle
- ✅ Faster performance
- ✅ No source maps or dev dependencies
- ⚠️ Code baked into image

## Usage

### Development Environment

```bash
# Start all services in development mode
docker-compose up -d

# View logs
docker-compose logs -f

# Rebuild after changing Dockerfile.dev
docker-compose up -d --build

# Stop services
docker-compose down

# Stop and remove volumes (fresh start)
docker-compose down -v
```

**Access:**
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000/api/v1
- Database: localhost:5432

**Features:**
- Live code reload for frontend and backend
- Xdebug available on port 9003
- Full error messages and stack traces
- All dev dependencies available

### Production Environment

```bash
# Start all services in production mode
docker-compose -f docker-compose.prod.yml up -d

# View logs
docker-compose -f docker-compose.prod.yml logs -f

# Rebuild images
docker-compose -f docker-compose.prod.yml build --no-cache

# Stop services
docker-compose -f docker-compose.prod.yml down
```

**Access:**
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000/api/v1

**Features:**
- Optimized performance
- Minimal image sizes
- Production-ready configuration
- No debug overhead

## Environment Variables

### Development (.env)
```env
DB_DATABASE=mob
DB_USERNAME=mob
DB_PASSWORD=mob
NUXT_PUBLIC_API_BASE=http://localhost:8000/api/v1
```

### Production (.env.production)
```env
APP_ENV=production
APP_DEBUG=false
DB_DATABASE=your_prod_db
DB_USERNAME=your_prod_user
DB_PASSWORD=your_secure_password
NUXT_PUBLIC_API_BASE=https://api.yourdomain.com/api/v1
```

## Backend Xdebug Configuration (Development Only)

To use Xdebug with VS Code, add this to `.vscode/launch.json`:

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug (Docker)",
      "type": "php",
      "request": "launch",
      "port": 9003,
      "pathMappings": {
        "/var/www/html": "${workspaceFolder}/backend"
      }
    }
  ]
}
```

## Best Practices

1. **Never use development Dockerfiles in production**
   - Development images are larger and less secure
   - Xdebug adds significant performance overhead

2. **Always use .env files**
   - Never commit sensitive credentials
   - Use different .env files for dev/prod

3. **Rebuild images after Dockerfile changes**
   ```bash
   docker-compose up -d --build
   ```

4. **Clean up regularly**
   ```bash
   # Remove unused images
   docker image prune -a
   
   # Remove unused volumes
   docker volume prune
   ```

5. **Test production build locally before deploying**
   ```bash
   docker-compose -f docker-compose.prod.yml up --build
   ```

## Troubleshooting

### Backend container exits immediately
- Check logs: `docker-compose logs backend`
- Verify database connection in .env
- Ensure entrypoint.sh has correct permissions

### Frontend hot reload not working
- Make sure you're using `docker-compose.yml` (dev mode)
- Check that volumes are mounted correctly
- Restart: `docker-compose restart frontend`

### Database connection failed
- Wait for healthcheck: `docker-compose ps`
- Check credentials in .env match docker-compose.yml
- Verify database is healthy: `docker-compose exec db pg_isready`

### Xdebug not connecting
- Verify Xdebug is enabled: `docker-compose exec backend php -v`
- Check IDE is listening on port 9003
- Ensure pathMappings in launch.json are correct
