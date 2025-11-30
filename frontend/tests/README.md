# Frontend Tests

This directory contains unit tests for the Nuxt 3 frontend application using Vitest.

## Test Structure

```
tests/
├── stores/           # Pinia store tests
│   ├── auth.test.ts
│   ├── stations.test.ts
│   └── routes.test.ts
└── pages/            # Page component tests (to be added)
```

## Running Tests

```bash
# Run all tests
npm test

# Run tests in watch mode
npm test -- --watch

# Run tests with UI
npm test:ui

# Run tests with coverage
npm test:coverage
```

## Test Coverage

The tests cover:

### Auth Store (`stores/auth.test.ts`)
- ✅ Initial state
- ✅ Login (success & error)
- ✅ Register (success & error)
- ✅ Logout
- ✅ Initialize from localStorage
- ✅ Token validation

### Stations Store (`stores/stations.test.ts`)
- ✅ Initial state
- ✅ Fetch stations (success & error)
- ✅ Caching (avoid re-fetch)
- ✅ Loading state management
- ✅ Reset functionality

### Routes Store (`stores/routes.test.ts`)
- ✅ Initial state
- ✅ Calculate route (success & error)
- ✅ Error handling (multiple formats)
- ✅ Route history (max 10 items)
- ✅ Clear current route
- ✅ Clear history

## Writing Tests

### Example Store Test

```typescript
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useMyStore } from '~/stores/myStore'

// Mock $apiFetch
const mockApiFetch = vi.fn()
vi.mock('#app', () => ({
  useNuxtApp: () => ({
    $apiFetch: mockApiFetch
  })
}))

describe('My Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
  })

  it('should do something', async () => {
    const store = useMyStore()
    mockApiFetch.mockResolvedValue({ data: 'test' })
    
    await store.someAction()
    
    expect(store.someState).toBe('expected')
  })
})
```

## CI/CD Integration

Tests run automatically on:
- Push to `main` or `dev` branches
- Pull requests to `main` or `dev` branches

See `.github/workflows/ci-cd.yml` for the full CI/CD pipeline.

## Dependencies

- **vitest**: Test runner
- **@vue/test-utils**: Vue component testing utilities
- **@nuxt/test-utils**: Nuxt-specific test utilities
- **happy-dom**: Fast DOM implementation for testing
- **@vitest/ui**: UI for running tests

## Best Practices

1. **Mock external dependencies**: Always mock `$apiFetch` and other external services
2. **Clear state between tests**: Use `beforeEach` to reset Pinia and clear mocks
3. **Test error cases**: Don't just test the happy path
4. **Use descriptive names**: Test descriptions should clearly explain what's being tested
5. **Keep tests isolated**: Each test should be independent and not rely on others
