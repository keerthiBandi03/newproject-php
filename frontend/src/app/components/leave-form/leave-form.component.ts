import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { LeaveService } from '../../services/leave.service';
import { Leave } from '../../models/leave.model';

@Component({
  selector: 'app-leave-form',
  templateUrl: './leave-form.component.html',
  styleUrls: ['./leave-form.component.css']
})
export class LeaveFormComponent {
  leave: Partial<Leave> = {
    type: '',
    startDate: '',
    endDate: '',
    reason: ''
  };
  isLoading = false;
  error = '';
  success = '';

  constructor(
    private leaveService: LeaveService,
    private router: Router
  ) {}

  onSubmit(): void {
    if (!this.leave.type || !this.leave.startDate || !this.leave.endDate || !this.leave.reason) {
      this.error = 'All fields are required';
      return;
    }

    this.isLoading = true;
    this.error = '';
    this.success = '';

    this.leaveService.createLeave(this.leave).subscribe({
      next: () => {
        this.success = 'Leave request submitted successfully';
        this.resetForm();
        this.isLoading = false;
      },
      error: (err) => {
        this.error = 'Failed to submit leave request';
        this.isLoading = false;
      }
    });
  }

  onCancel(): void {
    this.router.navigate(['/dashboard']);
  }

  private resetForm(): void {
    this.leave = {
      type: '',
      startDate: '',
      endDate: '',
      reason: ''
    };
  }
}