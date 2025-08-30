import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';

import { AuthService } from '../../frontend/src/app/services/auth.service';

describe('AuthService', () => {
  let service: AuthService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [AuthService]
    });
    service = TestBed.inject(AuthService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
    localStorage.clear();
  });

  it('should login and store token', () => {
    const mockResponse = {
      token: 'fake-jwt-token',
      user: { id: 1, username: 'test', email: 'test@example.com', roles: ['user'] }
    };

    service.login({ username: 'test', password: 'pass' }).subscribe(user => {
      expect(user).toBeTruthy();
      expect(localStorage.getItem('auth_token')).toBe('fake-jwt-token');
    });

    const req = httpMock.expectOne('http://localhost:8000/api/auth/login');
    expect(req.request.method).toBe('POST');
    req.flush(mockResponse);
  });

  it('should return isAuthenticated true if token exists', () => {
    localStorage.setItem('auth_token', 'token');
    expect(service.isAuthenticated()).toBeTrue();
  });

  it('should return isAuthenticated false if no token', () => {
    localStorage.removeItem('auth_token');
    expect(service.isAuthenticated()).toBeFalse();
  });
});
