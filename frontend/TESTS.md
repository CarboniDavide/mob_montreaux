# Frontend Testing Guide

This guide covers testing for the Nuxt 3 frontend application using Vitest.

## 📋 Table of Contents

- [Overview](#overview)
- [Test Framework](#test-framework)
- [Running Tests](#running-tests)
- [Test Structure](#test-structure)
- [Writing Tests](#writing-tests)
- [Mocking](#mocking)
- [Coverage Reports](#coverage-reports)
- [Troubleshooting](#troubleshooting)
- [Best Practices](#best-practices)

---

## 🔍 Overview

The frontend test suite includes:
- **26 unit tests** covering Pinia stores
- **Test framework**: Vitest 2.1.8
- **Test utilities**: @vue/test-utils 2.4.6
- **Test environment**: happy-dom 15.11.7
- **Coverage tool**: @vitest/coverage-v8 2.1.8

**Test coverage:**
- ✅ Auth store (10 tests) - login, register, logout, initialization
- ✅ Stations store (8 tests) - fetch, cache, loading, errors
- ✅ Routes store (8 tests) - calculate, history, errors

---

## 🧪 Test Framework

### Technology Stack

- **Vitest**: Fast unit test framework powered by Vite
- **@vue/test-utils**: Official testing library for Vue 3
- **happy-dom**: Lightweight DOM implementation for tests
- **Coverage**: V8 coverage provider for code coverage reports

### Configuration Files

| File | Purpose |
|------|---------|
| `vitest.config.ts` | Vitest configuration and test settings |
| `tests/setup.ts` | Global mocks and test environment setup |
| `tests/stores/*.test.ts` | Store unit tests |

---

## 🚀 Running Tests

### Quick Commands

```bash
# Run all tests once (CI mode)
npm test

# Run tests in watch mode (development)
npm run test:watch

# Run tests with UI (interactive)
npm run test:ui

# Generate coverage report
npm run test:coverage
```

### Detailed Commands

#### 1. Run All Tests (Default)
```bash
npm test
```
- Runs all tests once and exits
- Used in CI/CD pipeline
- Shows test results and summary

**Output:**
```
✓ tests/stores/auth.test.ts (10 tests) 234ms
✓ tests/stores/stations.test.ts (8 tests) 189ms
✓ tests/stores/routes.test.ts (8 tests) 201ms

Test Files  3 passed (3)
     Tests  26 passed (26)
  Start at  14:23:45
  Duration  1.24s
```

#### 2. Watch Mode (Development)
```bash
npm run test:watch
```
- Watches for file changes
- Re-runs tests automatically
- Useful during development

**Interactive commands in watch mode:**
- Press `a` to run all tests
- Press `f` to run only failed tests
- Press `u` to update snapshots
- Press `q` to quit

#### 3. UI Mode (Visual Interface)
```bash
npm run test:ui
```
- Opens browser-based test UI
- Interactive test exploration
- View test results visually
- Accessible at: `http://localhost:51204/__vitest__/`

#### 4. Coverage Report
```bash
npm run test:coverage
```
- Generates detailed coverage report
- Creates HTML report in `coverage/` directory
- Shows line, branch, function, and statement coverage

**View coverage report:**
```bash
open coverage/index.html
```

---

## 📁 Test Structure

```
frontend/tests/
├── setup.ts                    # Global mocks and configuration
├── README.md                   # Test documentation
└── stores/
    ├── auth.test.ts           # Auth store tests (10 tests)
    ├── routes.test.ts         # Routes store tests (8 tests)
    └── stations.test.ts       # Stations store tests (8 tests)
```

### Test File Naming Convention

- `*.test.ts` - Unit test files
- `*.spec.ts` - Alternative naming (not currently used)
- Co-locate tests with source files or keep in `tests/` directory

---

## ✍️ Writing Tests

### Basic Test Structure

```typescript
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'

describe('MyStore', () => {
  beforeEach(() => {
    // Create fresh Pinia instance for each test
    setActivePinia(createPinia())
    
    // Reset mocks
    vi.clearAllMocks()
  })

  it('should do something', () => {
    // Arrange: Set up test data
    const store = useMyStore()
    
    // Act: Perform action
    store.someMethod()
    
    // Assert: Verify result
    expect(store.someState).toBe(expectedValue)
  })
})
```

### Testing Pinia Stores

#### Example: Auth Store Test

```typescript
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAuthStore } from '~/stores/auth'

describe('Auth Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
    localStorage.clear()
  })

  it('should login successfully', async () => {
    // Arrange
    const store = useAuthStore()
    const mockResponse = { token: 'test-token', user: { id: 1, name: 'Test' } }
    vi.mocked($fetch).mockResolvedValue(mockResponse)

    // Act
    await store.login('test@example.com', 'password')

    // Assert
    expect(store.isAuthenticated).toBe(true)
    expect(store.token).toBe('test-token')
    expect(localStorage.getItem('token')).toBe('test-token')
  })

  it('should handle login errors', async () => {
    // Arrange
    const store = useAuthStore()
    vi.mocked($fetch).mockRejectedValue(new Error('Invalid credentials'))

    // Act & Assert
    await expect(store.login('test@example.com', 'wrong')).rejects.toThrow()
    expect(store.isAuthenticated).toBe(false)
  })
})
```

#### Example: Testing with API Mocks

```typescript
import { mockApiFetch } from '../setup'

describe('Stations Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
  })

  it('should fetch stations', async () => {
    // Arrange
    const store = useStationsStore()
    const mockStations = [
      { id: 1, name: 'Station A' },
      { id: 2, name: 'Station B' }
    ]
    mockApiFetch.mockResolvedValue({ data: mockStations })

    // Act
    await store.fetchStations()

    // Assert
    expect(store.stations).toEqual(mockStations)
    expect(store.loading).toBe(false)
    expect(mockApiFetch).toHaveBeenCalledWith('/api/stations')
  })
})
```

### Testing Async Operations

```typescript
it('should handle async operations', async () => {
  const store = useMyStore()
  
  // Mock API response
  vi.mocked($fetch).mockResolvedValue({ data: 'test' })
  
  // Wait for async operation
  await store.fetchData()
  
  // Assert after completion
  expect(store.data).toBe('test')
  expect(store.loading).toBe(false)
})
```

### Testing Error Handling

```typescript
it('should handle errors gracefully', async () => {
  const store = useMyStore()
  const errorMessage = 'Network error'
  
  // Mock API rejection
  vi.mocked($fetch).mockRejectedValue(new Error(errorMessage))
  
  // Expect error to be thrown
  await expect(store.fetchData()).rejects.toThrow(errorMessage)
  
  // Verify error state
  expect(store.error).toBe(errorMessage)
  expect(store.loading).toBe(false)
})
```

---

## 🎭 Mocking

### Global Mocks (setup.ts)

The test setup file provides global mocks for:

#### 1. Browser APIs
```typescript
// localStorage
global.localStorage = {
  getItem: vi.fn(),
  setItem: vi.fn(),
  removeItem: vi.fn(),
  clear: vi.fn()
}
```

#### 2. Nuxt Composables
```typescript
// useRuntimeConfig
vi.mock('#app', () => ({
  useRuntimeConfig: () => ({
    public: {
      apiBase: 'http://localhost:8000'
    }
  })
}))

// useNuxtApp
vi.mock('nuxt/app', () => ({
  useNuxtApp: () => ({
    $apiFetch: mockApiFetch
  })
}))
```

#### 3. API Fetch Mock
```typescript
// $fetch mock (imported from setup.ts)
import { mockApiFetch } from '../setup'

// Use in tests
mockApiFetch.mockResolvedValue({ data: 'test' })
mockApiFetch.mockRejectedValue(new Error('API error'))
```

### Custom Mocks in Tests

#### Mocking Store Methods
```typescript
it('should mock store method', () => {
  const store = useMyStore()
  
  // Mock a store method
  vi.spyOn(store, 'myMethod').mockImplementation(() => {
    return 'mocked result'
  })
  
  expect(store.myMethod()).toBe('mocked result')
})
```

#### Mocking External Dependencies
```typescript
vi.mock('~/composables/useApi', () => ({
  useApi: () => ({
    get: vi.fn().mockResolvedValue({ data: 'test' })
  })
}))
```

### Mock Utilities

```typescript
// Clear all mocks between tests
beforeEach(() => {
  vi.clearAllMocks()
})

// Reset mock implementation
vi.mocked($fetch).mockReset()

// Restore original implementation
vi.mocked($fetch).mockRestore()

// Check mock calls
expect(mockApiFetch).toHaveBeenCalledTimes(1)
expect(mockApiFetch).toHaveBeenCalledWith('/api/endpoint')
expect(mockApiFetch).toHaveBeenLastCalledWith('/api/last')
```

---

## 📊 Coverage Reports

### Generating Coverage

```bash
npm run test:coverage
```

### Coverage Output

**Console output:**
```
----------------------|---------|----------|---------|---------|
File                  | % Stmts | % Branch | % Funcs | % Lines |
----------------------|---------|----------|---------|---------|
All files             |   85.23 |    78.45 |   82.16 |   85.23 |
 stores               |   92.31 |    87.50 |   90.00 |   92.31 |
  auth.ts             |   95.00 |    90.00 |   95.00 |   95.00 |
  routes.ts           |   88.89 |    85.00 |   87.50 |   88.89 |
  stations.ts         |   93.75 |    87.50 |   88.89 |   93.75 |
----------------------|---------|----------|---------|---------|
```

**HTML report:**
- Location: `coverage/index.html`
- Interactive line-by-line coverage
- Click files to see uncovered lines
- Color-coded coverage indicators

### Coverage Configuration

In `vitest.config.ts`:
```typescript
test: {
  coverage: {
    provider: 'v8',
    reporter: ['text', 'json', 'html'],
    include: ['stores/**', 'composables/**', 'pages/**', 'components/**'],
    exclude: ['**/*.test.ts', '**/*.spec.ts']
  }
}
```

### Setting Coverage Thresholds

Add to `vitest.config.ts`:
```typescript
test: {
  coverage: {
    thresholds: {
      lines: 80,
      functions: 80,
      branches: 80,
      statements: 80
    }
  }
}
```

---

## 🐛 Troubleshooting

### Common Issues and Solutions

#### 1. "Cannot find module './.nuxt/tsconfig.json'"

**Problem:** Nuxt type files not generated

**Solution:**
```bash
# Generate Nuxt types
npx nuxi prepare

# Then run tests
npm test
```

**In CI:** Already handled by `npx nuxi prepare` step in workflow.

#### 2. "useRuntimeConfig is not defined"

**Problem:** Nuxt composables not mocked

**Solution:** Ensure `tests/setup.ts` is imported in `vitest.config.ts`:
```typescript
setupFiles: ['./tests/setup.ts']
```

#### 3. "localStorage is not defined"

**Problem:** Browser APIs not available in test environment

**Solution:** Already mocked in `tests/setup.ts`. Make sure setup file is loaded.

#### 4. Tests Pass Locally but Fail in CI

**Possible causes:**
- Missing `npx nuxi prepare` step
- Different Node.js versions
- Missing dependencies

**Solutions:**
```bash
# Check Node version matches CI (20.x)
node -v

# Clean install dependencies
rm -rf node_modules package-lock.json
npm install

# Regenerate Nuxt files
npx nuxi prepare

# Run tests
npm test
```

#### 5. Mock Not Working

**Problem:** Mock not applied correctly

**Solutions:**
```typescript
// Reset mocks before each test
beforeEach(() => {
  vi.clearAllMocks()
})

// Verify mock is called
expect(mockApiFetch).toHaveBeenCalled()

// Check mock implementation
console.log(mockApiFetch.mock.calls)
```

#### 6. Async Test Timeout

**Problem:** Test times out waiting for async operation

**Solutions:**
```typescript
// Increase timeout for specific test
it('slow test', async () => {
  // test code
}, 10000) // 10 second timeout

// Or in config
test: {
  testTimeout: 10000
}
```

---

## 💡 Best Practices

### 1. Test Organization

```typescript
describe('Feature/Store Name', () => {
  describe('Getter/Method Name', () => {
    it('should handle specific case', () => {
      // test implementation
    })
  })
})
```

### 2. Arrange-Act-Assert Pattern

```typescript
it('should do something', async () => {
  // Arrange: Set up test data and mocks
  const store = useMyStore()
  vi.mocked($fetch).mockResolvedValue({ data: 'test' })
  
  // Act: Perform the action
  await store.fetchData()
  
  // Assert: Verify the results
  expect(store.data).toBe('test')
})
```

### 3. Test Independence

```typescript
beforeEach(() => {
  // Reset state for each test
  setActivePinia(createPinia())
  vi.clearAllMocks()
  localStorage.clear()
})
```

### 4. Descriptive Test Names

```typescript
// ✅ Good
it('should set authentication state when login succeeds', async () => {})
it('should clear token from localStorage when logout is called', () => {})

// ❌ Bad
it('test login', () => {})
it('works', () => {})
```

### 5. Test Both Success and Failure

```typescript
describe('login', () => {
  it('should authenticate when credentials are valid', async () => {
    // test success case
  })
  
  it('should throw error when credentials are invalid', async () => {
    // test failure case
  })
  
  it('should handle network errors', async () => {
    // test error case
  })
})
```

### 6. Don't Test Implementation Details

```typescript
// ✅ Good - Test public API
expect(store.isAuthenticated).toBe(true)

// ❌ Bad - Test internal implementation
expect(store._internalState).toBe(true)
```

### 7. Use Appropriate Matchers

```typescript
// toBe for primitives
expect(store.count).toBe(5)

// toEqual for objects/arrays
expect(store.user).toEqual({ id: 1, name: 'Test' })

// toBeTruthy/toBeFalsy for booleans
expect(store.isLoading).toBeFalsy()

// toHaveBeenCalled for mocks
expect(mockApiFetch).toHaveBeenCalledTimes(1)

// toThrow for errors
expect(() => store.dangerousMethod()).toThrow()
```

### 8. Mock Only What You Need

```typescript
// ✅ Good - Mock only external dependencies
vi.mocked($fetch).mockResolvedValue({ data: 'test' })

// ❌ Bad - Over-mocking internal logic
vi.spyOn(store, 'everyInternalMethod').mockImplementation(...)
```

### 9. Keep Tests Fast

```typescript
// Use fake timers for time-based tests
vi.useFakeTimers()
vi.advanceTimersByTime(1000)
vi.useRealTimers()

// Mock heavy operations
vi.mock('heavy-library', () => ({
  heavyOperation: vi.fn().mockReturnValue('instant result')
}))
```

### 10. Document Complex Tests

```typescript
it('should handle edge case with multiple conditions', async () => {
  // Given: User has expired token and no network connection
  localStorage.setItem('token', 'expired-token')
  vi.mocked($fetch).mockRejectedValue(new Error('Network error'))
  
  // When: App initializes
  const store = useAuthStore()
  await store.init()
  
  // Then: Should clear invalid state and remain unauthenticated
  expect(store.isAuthenticated).toBe(false)
  expect(localStorage.getItem('token')).toBeNull()
})
```

---

## 📚 Additional Resources

- [Vitest Documentation](https://vitest.dev/)
- [Vue Test Utils](https://test-utils.vuejs.org/)
- [Pinia Testing Guide](https://pinia.vuejs.org/cookbook/testing.html)
- [Nuxt Testing Documentation](https://nuxt.com/docs/getting-started/testing)
- [Testing Best Practices](https://kentcdodds.com/blog/common-mistakes-with-react-testing-library)

---

## 🎯 Current Test Suite

### Coverage Summary

| Store | Tests | Coverage |
|-------|-------|----------|
| Auth Store | 10 | Login, Register, Logout, Init |
| Stations Store | 8 | Fetch, Cache, Loading, Errors |
| Routes Store | 8 | Calculate, History, Clear |
| **Total** | **26** | **All passing ✅** |

### Test Commands Reference

```bash
# Development workflow
npm run test:watch          # Watch mode for TDD

# CI/CD workflow
npm test                    # Run once and exit

# Coverage and reporting
npm run test:coverage       # Generate coverage report
npm run test:ui            # Visual test interface

# Specific tests
npx vitest auth            # Run tests matching "auth"
npx vitest --run stores    # Run all store tests
```

---

**Happy Testing! 🧪✨**
