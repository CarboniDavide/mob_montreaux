# MOB Montreaux - Swiss Train Route Calculator

A full-stack application for calculating optimal train routes and distances in the Swiss Montreaux region.

## 🚀 Tech Stack

### Backend
- **Framework:** Laravel 12
- **Language:** PHP 8.4
- **Database:** PostgreSQL 15 (production), SQLite (testing)
- **Testing:** PHPUnit 11.5
- **Authentication:** JWT tokens

### Frontend
- **Framework:** Nuxt 3
- **Language:** TypeScript
- **UI Library:** Vuetify 3
- **State Management:** Pinia
- **Testing:** Vitest 2.1.8

### DevOps
- **Containerization:** Docker & Docker Compose
- **CI/CD:** GitHub Actions
- **Container Registry:** GitHub Container Registry (GHCR)
- **Web Server:** Nginx

---

## 📁 Project Structure

```
mob_montreaux/
├── backend/                    # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/  # API endpoints
│   │   ├── Models/            # Database models
│   │   └── Services/          # Business logic
│   ├── database/
│   │   ├── migrations/        # Database schema
│   │   ├── factories/         # Test data factories
│   │   └── seeders/           # Database seeders
│   ├── tests/
│   │   ├── Feature/           # API integration tests
│   │   └── Unit/              # Unit tests
│   ├── Dockerfile             # Production Docker config
│   ├── Dockerfile.dev         # Development Docker config
│   ├── test.sh                # Test runner script
│   └── TESTS.md               # Backend testing guide
│
├── frontend/                   # Nuxt 3 application
│   ├── components/            # Vue components
│   ├── pages/                 # Application pages
│   │   ├── index.vue          # Homepage
│   │   ├── login.vue          # Login page
│   │   ├── register.vue       # Registration page
│   │   └── find-distance.vue  # Route calculator
│   ├── stores/                # Pinia stores
│   │   ├── auth.ts            # Authentication state
│   │   ├── stations.ts        # Stations data
│   │   └── routes.ts          # Route calculations
│   ├── tests/                 # Test suite
│   │   ├── setup.ts           # Test configuration
│   │   └── stores/            # Store tests (26 tests)
│   ├── Dockerfile             # Production Docker config
│   ├── Dockerfile.dev         # Development Docker config
│   └── TESTS.md               # Frontend testing guide
│
├── docker-compose.yml          # Development environment
├── docker-compose.prod.yml     # Production environment
├── push-images.sh             # Manual Docker image publishing script
├── deploy.sh                  # Deployment script
├── .github/workflows/
│   └── ci-cd.yml              # CI/CD pipeline
├── CI-CD.md                   # CI/CD documentation
├── DOCKER.md                  # Docker documentation
└── README.md                  # This file
```

---

---

## ✨ Features

- 🚂 **Route Calculation:** Find optimal train routes using Dijkstra's algorithm
- 🎨 **MOB Design System:** Swiss railway-inspired design matching MOB.ch
- 🔐 **Secure Authentication:** JWT-based user authentication and authorization
- 📊 **Route History:** Track and review past route calculations
- 🐳 **Docker Ready:** Fully containerized for consistent deployment
- ⚡ **CI/CD Pipeline:** Automated testing, building, and deployment
- 📦 **Container Registry:** Automated Docker image publishing to GHCR
- 🧪 **Comprehensive Testing:** PHPUnit (backend) and Vitest (frontend)
- 📱 **Responsive Design:** Mobile-first responsive interface

---

## 🚀 Quick Start

### Prerequisites

- **Docker** and **Docker Compose** installed
- **Git** installed
- Ports 8000 (API), 3000 (frontend), 5432 (PostgreSQL) available

### 1. Clone the Repository

```bash
git clone https://github.com/CarboniDavide/mob_montreaux.git
cd mob_montreaux
```

### 2. Start Development Environment

```bash
# Start all services (backend, frontend, database, nginx)
docker-compose up -d

# View logs
docker-compose logs -f

# Check running services
docker-compose ps
```

### 3. Access the Application

- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000
- **API Documentation:** http://localhost:8000/api

### 4. Initial Setup

```bash
# Run database migrations and seeders
docker-compose exec backend php artisan migrate --seed

# Verify setup
docker-compose exec backend php artisan --version
```

---

## 🧪 Testing

### Backend Tests (PHPUnit)

```bash
# Run all backend tests
cd backend
./test.sh

# Run specific test suite
./test.sh --testsuite Feature
./test.sh --testsuite Unit

# Run with coverage
vendor/bin/phpunit --coverage-html coverage
```

**See [backend/TESTS.md](backend/TESTS.md) for complete testing guide.**

### Frontend Tests (Vitest)

```bash
# Run all frontend tests
cd frontend
npm test

# Run in watch mode
npm run test:watch

# Run with UI
npm run test:ui

# Generate coverage report
npm run test:coverage
```

**See [frontend/TESTS.md](frontend/TESTS.md) for complete testing guide.**

### Test Coverage

- **Backend:** PHPUnit tests for API endpoints, models, and business logic
- **Frontend:** 26 Vitest tests covering authentication, stations, and routes
- **CI/CD:** Automated testing on every push and pull request

---

## 🐳 Docker Commands

### Development

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# Rebuild services
docker-compose up -d --build

# View logs
docker-compose logs -f backend
docker-compose logs -f frontend

# Execute commands in containers
docker-compose exec backend php artisan migrate
docker-compose exec frontend npm install
```

### Production

```bash
# Start production environment
docker-compose -f docker-compose.prod.yml up -d

# View production logs
docker-compose -f docker-compose.prod.yml logs -f
```

### Useful Commands

```bash
# Clean up all containers and volumes
docker-compose down -v

# Rebuild from scratch
docker-compose down -v && docker-compose up -d --build

# Access container shell
docker-compose exec backend bash
docker-compose exec frontend sh
```

### Publishing Docker Images

```bash
# Automatic (via CI/CD)
# Push to main branch - images automatically built and pushed to GHCR
git push origin main

# Manual (using push-images.sh script)
# Requires GitHub Personal Access Token with write:packages permission
./push-images.sh

# Manual (using Docker commands)
docker buildx build --platform linux/amd64,linux/arm64 -t ghcr.io/carbonidavide/mob_montreaux/backend:latest ./backend --push
docker buildx build --platform linux/amd64,linux/arm64 -t ghcr.io/carbonidavide/mob_montreaux/frontend:latest ./frontend --push
```

**See [CI-CD.md](CI-CD.md) for complete Docker image publishing guide.**

---

## 📚 Documentation

| Document | Description |
|----------|-------------|
| [CI-CD.md](CI-CD.md) | CI/CD pipeline and Docker image publishing |
| [DOCKER.md](DOCKER.md) | Docker configuration, usage, and deployment |
| [backend/TESTS.md](backend/TESTS.md) | Backend testing guide (PHPUnit) |
| [frontend/TESTS.md](frontend/TESTS.md) | Frontend testing guide (Vitest) |
| [push-images.sh](push-images.sh) | Manual Docker image publishing script |

---

## 🔧 Development Workflow

### 1. Create Feature Branch

```bash
git checkout -b feature/my-feature
```

### 2. Make Changes

Edit code in `backend/` or `frontend/` directories.

### 3. Run Tests Locally

```bash
# Backend tests
cd backend && ./test.sh

# Frontend tests
cd frontend && npm test
```

### 4. Commit and Push

```bash
git add .
git commit -m "Add: my new feature"
git push origin feature/my-feature
```

### 5. Create Pull Request

- Go to GitHub repository
- Create pull request from your feature branch to `dev`
- CI/CD will automatically run tests
- Merge when tests pass

### 6. Deploy to Production

```bash
# Merge to main branch
git checkout main
git merge dev
git push origin main

# CI/CD automatically deploys if tests pass
```

---

## 🌐 API Endpoints

### Authentication

- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user
- `GET /api/auth/user` - Get authenticated user

### Stations

- `GET /api/stations` - List all stations
- `GET /api/stations/{id}` - Get station details
- `POST /api/stations` - Create station (admin)
- `PUT /api/stations/{id}` - Update station (admin)
- `DELETE /api/stations/{id}` - Delete station (admin)

### Routes

- `POST /api/routes/calculate` - Calculate route between stations
- `GET /api/routes/history` - Get user's route history

---

## 🛠️ Configuration

### Environment Variables

#### Backend (.env)

```env
APP_NAME="MOB Montreaux"
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=mob_db
DB_USERNAME=mob_user
DB_PASSWORD=mob_password

JWT_SECRET=your-secret-key
```

#### Frontend (.env)

```env
NUXT_PUBLIC_API_BASE=http://localhost:8000
```

---

## 🚨 Troubleshooting

### Port Already in Use

```bash
# Check what's using the port
lsof -i :3000
lsof -i :8000

# Kill the process
kill -9 <PID>
```

### Database Connection Issues

```bash
# Check database is running
docker-compose ps db

# Restart database
docker-compose restart db

# Check logs
docker-compose logs db
```

### Frontend/Backend Not Updating

```bash
# Rebuild containers
docker-compose down
docker-compose up -d --build

# Clear caches
docker-compose exec backend php artisan cache:clear
docker-compose exec frontend npm run build
```

### Tests Failing

```bash
# Backend: Ensure using SQLite for tests
cd backend
export DB_CONNECTION=sqlite
export DB_DATABASE=:memory:
./test.sh

# Frontend: Regenerate Nuxt types
cd frontend
npx nuxi prepare
npm test
```

---

## 📝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Run tests locally
4. Commit your changes (`git commit -m 'Add amazing feature'`)
5. Push to the branch (`git push origin feature/amazing-feature`)
6. Open a Pull Request

---

## 📄 License

This project is open source and available under the MIT License.

---

## 👥 Team

**Project:** MOB Montreaux Train Route Calculator  
**Repository:** https://github.com/CarboniDavide/mob_montreaux

---

## 🆘 Support

For issues, questions, or contributions:
- Create an issue on GitHub
- Check documentation in `CI-CD.md`, `DOCKER.md`, and `TESTS.md` files
- Review test suite examples in `backend/tests/` and `frontend/tests/`

---

**Happy Coding! 🚂✨**