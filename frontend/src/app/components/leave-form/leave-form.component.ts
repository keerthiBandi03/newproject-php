import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { LeaveService } from '../../services/leave.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-leave-form',
  template: `
  <div class="leave-form">
    <h2>Submit Leave Request</h2>
    <form [formGroup]="leaveForm" (ngSubmit)="onSubmit()">
      <label>
        Start Date:
        <input formControlName="startDate" type="date" />
      </label>
      <div *ngIf="leaveForm.controls.startDate.invalid && leaveForm.controls.startDate.touched" class="error">
        Start date is required
      </div>

      <label>
        End Date:
        <input formControlName="endDate" type="date" />
      </label>
      <div *ngIf="leaveForm.controls.endDate.invalid && leaveForm.controls.endDate.touched" class="error">
        End date is required and must be after start date
      </div>

      <label>
        Type:
        <select formControlName="type">
          <option value="">Select leave type</option>
          <option value="vacation">Vacation</option>
          <option value="sick">Sick</option>
          <option value="personal">Personal</option>
          <option value="other">Other</option>
        </select>
      </label>
      <div *ngIf="leaveForm.controls.type.invalid && leaveForm.controls.type.touched" class="error">
        Leave type is required
      </div>

      <label>
        Reason:
        <textarea formControlName="reason"></textarea>
      </label>
      <div *ngIf="leaveForm.controls.reason.invalid && leaveForm.controls.reason.touched" class="error">
        Reason is required
      </div>

      <button type="submit" [disabled]="leaveForm.invalid">Submit</button>
    </form>
    <div *ngIf="submitError" class="error">
      {{ submitError }}
    </div>
  </div>
  `,
  styles: [`
    .leave-form {
      max-width: 500px;
      margin: 2rem auto;
    }
    label {
      display: flex;
      flex-direction: column;
      margin-bottom: 1rem;
    }
    input, select, textarea {
      padding: 0.5rem;
      font-size: 1rem;
    }
    textarea {
      resize: vertical;
      min-height: 80px;
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
export class LeaveFormComponent {
  leaveForm: FormGroup;
  submitError = '';

  constructor(private fb: FormBuilder, private leaveService: LeaveService, private router: Router) {
    this.leaveForm = this.fb.group({
      startDate: ['', Validators.required],
      endDate: ['', Validators.required],
      type: ['', Validators.required],
      reason: ['', Validators.required]
    }, { validators: this.dateRangeValidator });
  }

  dateRangeValidator(group: FormGroup) {
    const start = group.get('startDate')?.value;
    const end = group.get('endDate')?.value;
    if (start && end) {
      return new Date(end) >= new Date(start) ? null : { dateRangeInvalid: true };
    }
    return null;
  }

  onSubmit(): void {
    if (this.leaveForm.valid) {
      this.submitError = '';
      this.leaveService.submitLeave(this.leaveForm.value).subscribe({
        next: () => this.router.navigate(['/leave-list']),
        error: () => this.submitError = 'Failed to submit leave request. Please try again.'
      });
    }
  }
}
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { LeaveService } from '../../services/leave.service';
import { Leave } from '../../models/leave.model';

@Component({
  selector: 'app-leave-form',
  template: `
    <div class="leave-form-container">
      <h2>Submit Leave Request</h2>
      
      <form (ngSubmit)="onSubmit()" class="leave-form">
        <div class="form-group">
          <label for="startDate">Start Date:</label>
          <input 
            type="date" 
            id="startDate"
            [(ngModel)]="leave.startDate" 
            name="startDate"
            required
            class="form-control">
        </div>
        
        <div class="form-group">
          <label for="endDate">End Date:</label>
          <input 
            type="date" 
            id="endDate"
            [(ngModel)]="leave.endDate" 
            name="endDate"
            required
            class="form-control">
        </div>
        
        <div class="form-group">
          <label for="type">Leave Type:</label>
          <select 
            id="type"
            [(ngModel)]="leave.type" 
            name="type"
            required
            class="form-control">
            <option value="">Select leave type</option>
            <option value="annual">Annual Leave</option>
            <option value="sick">Sick Leave</option>
            <option value="personal">Personal Leave</option>
            <option value="emergency">Emergency Leave</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="reason">Reason:</label>
          <textarea 
            id="reason"
            [(ngModel)]="leave.reason" 
            name="reason"
            required
            rows="4"
            class="form-control"
            placeholder="Please provide a reason for your leave request">
          </textarea>
        </div>
        
        <div class="form-actions">
          <button type="submit" class="btn-primary" [disabled]="isSubmitting">
            {{ isSubmitting ? 'Submitting...' : 'Submit Request' }}
          </button>
          <button type="button" (click)="goBack()" class="btn-secondary">Cancel</button>
        </div>
        
        <div *ngIf="message" class="message" [class]="messageType">{{ message }}</div>
      </form>
    </div>
  `
})
export class LeaveFormComponent {
  leave: Partial<Leave> = {
    startDate: '',
    endDate: '',
    type: '',
    reason: ''
  };
  isSubmitting = false;
  message = '';
  messageType = '';

  constructor(
    private leaveService: LeaveService,
    private router: Router
  ) {}

  onSubmit(): void {
    this.isSubmitting = true;
    this.message = '';

    this.leaveService.submitLeave(this.leave as Leave).subscribe({
      next: (response) => {
        this.message = 'Leave request submitted successfully!';
        this.messageType = 'success';
        this.isSubmitting = false;
        setTimeout(() => {
          this.router.navigate(['/leaves']);
        }, 2000);
      },
      error: (err) => {
        this.message = 'Failed to submit leave request. Please try again.';
        this.messageType = 'error';
        this.isSubmitting = false;
      }
    });
  }

  goBack(): void {
    this.router.navigate(['/leaves']);
  }
}
