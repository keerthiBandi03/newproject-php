
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from './services/auth.service';

@Component({
  selector: 'app-root',
  template: `
    <div class="app-container">
      <nav class="navbar" *ngIf="authService.isAuthenticated()">
        <div class="nav-brand">
          <h2>Leave Management System</h2>
        </div>
        <div class="nav-menu">
          <a routerLink="/dashboard" routerLinkActive="active">Dashboard</a>
          <a routerLink="/leaves" routerLinkActive="active">My Leaves</a>
          <a routerLink="/leave-form" routerLinkActive="active">Request Leave</a>
          <button (click)="logout()" class="logout-btn">Logout</button>
        </div>
      </nav>
      <main class="main-content">
        <router-outlet></router-outlet>
      </main>
    </div>
  `,
  styleUrls: ['./styles.css']
})
export class AppComponent {
  constructor(
    public authService: AuthService,
    private router: Router
  ) {}

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}
