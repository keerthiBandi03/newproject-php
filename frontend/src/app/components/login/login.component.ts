import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-login',
  template: `
  <div class="login-container">
    <h2>Login</h2>
    <form [formGroup]="loginForm" (ngSubmit)="onSubmit()">
      <label>
        Username:
        <input formControlName="username" type="text" />
      </label>
      <div *ngIf="loginForm.controls.username.invalid && loginForm.controls.username.touched" class="error">
        Username is required
      </div>

      <label>
        Password:
        <input formControlName="password" type="password" />
      </label>
      <div *ngIf="loginForm.controls.password.invalid && loginForm.controls.password.touched" class="error">
        Password is required
      </div>

      <button type="submit" [disabled]="loginForm.invalid">Login</button>
    </form>
    <div *ngIf="errorMessage" class="error">
      {{ errorMessage }}
    </div>
  </div>
  `,
  styles: [`
    .login-container {
      max-width: 400px;
      margin: 2rem auto;
      padding: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    label {
      display: flex;
      flex-direction: column;
      margin-bottom: 1rem;
    }
    input {
      padding: 0.5rem;
      font-size: 1rem;
    }
    button {
      padding: 0.5rem 1rem;
      font-size: 1rem;
    }
    .error {
      color: red;
      font-size: 0.85rem;
    }
  `]
})
export class LoginComponent {
  loginForm: FormGroup;
  errorMessage: string = '';

  constructor(private fb: FormBuilder, private authService: AuthService, private router: Router) {
    this.loginForm = this.fb.group({
      username: ['', Validators.required],
      password: ['', Validators.required]
    });
  }

  onSubmit(): void {
    if (this.loginForm.valid) {
      this.errorMessage = '';
      this.authService.login(this.loginForm.value).subscribe({
        next: (user) => {
          if (user) {
            this.router.navigate(['/dashboard']);
          } else {
            this.errorMessage = 'Invalid username or password.';
          }
        },
        error: () => {
          this.errorMessage = 'Login failed. Please try again.';
        }
      });
    }
  }
}
