import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { tap, mapTo, catchError } from 'rxjs/operators';
import { User } from '../models/user.model';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private readonly TOKEN_KEY = 'auth_token';
  private readonly USER_KEY = 'auth_user';

  constructor(private http: HttpClient) {}

  login(credentials: { username: string; password: string }): Observable<User> {
    return this.http.post<{ token: string; user: User }>(`${environment.backendApiUrl}/auth/login`, credentials)
      .pipe(
        tap(response => {
          localStorage.setItem(this.TOKEN_KEY, response.token);
          localStorage.setItem(this.USER_KEY, JSON.stringify(response.user));
        }),
        mapTo({
          ...credentials,
          ...{ id: 0, email: '', roles: [] }
        }),
        catchError(() => {
          return of(null as any);
        }),
        mapTo(response => response.user)
      );
  }

  logout(): void {
    localStorage.removeItem(this.TOKEN_KEY);
    localStorage.removeItem(this.USER_KEY);
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem(this.TOKEN_KEY);
  }

  getToken(): string | null {
    return localStorage.getItem(this.TOKEN_KEY);
  }

  getUser(): User | null {
    const userJson = localStorage.getItem(this.USER_KEY);
    if (userJson) {
      return JSON.parse(userJson);
    }
    return null;
  }
}
