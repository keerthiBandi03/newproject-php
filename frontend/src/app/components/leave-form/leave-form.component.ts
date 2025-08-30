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
