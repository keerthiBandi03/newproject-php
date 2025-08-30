import { Component, OnInit } from '@angular/core';
import { EmployeeService } from '../../services/employee.service';
import { LeaveService } from '../../services/leave.service';
import { Employee } from '../../models/employee.model';
import { Leave } from '../../models/leave.model';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  employee: Employee | null = null;
  leaveCounts = {
    pending: 0,
    approved: 0,
    rejected: 0
  };

  constructor(private employeeService: EmployeeService, private leaveService: LeaveService) {}

  ngOnInit(): void {
    this.employeeService.getEmployeeDetails().subscribe({
      next: employee => this.employee = employee
    });

    this.leaveService.getLeaves().subscribe({
      next: leaves => {
        this.leaveCounts = {
          pending: leaves.filter(l => l.status === 'pending').length,
          approved: leaves.filter(l => l.status === 'approved').length,
          rejected: leaves.filter(l => l.status === 'rejected').length
        };
      }
    });
  }
}
import { Component, OnInit } from '@angular/core';
import { EmployeeService } from '../../services/employee.service';
import { LeaveService } from '../../services/leave.service';
import { Employee } from '../../models/employee.model';
import { Leave } from '../../models/leave.model';

@Component({
  selector: 'app-dashboard',
  template: `
    <div class="dashboard-container">
      <h1>Employee Dashboard</h1>
      
      <div class="profile-section" *ngIf="employee">
        <h3>Profile Information</h3>
        <div class="profile-card">
          <p><strong>Name:</strong> {{ employee.firstName }} {{ employee.lastName }}</p>
          <p><strong>Department:</strong> {{ employee.department }}</p>
          <p><strong>Position:</strong> {{ employee.position }}</p>
        </div>
      </div>

      <div class="stats-section">
        <h3>Leave Statistics</h3>
        <div class="stats-grid">
          <div class="stat-card">
            <h4>Total Requests</h4>
            <span class="stat-number">{{ leaves.length }}</span>
          </div>
          <div class="stat-card">
            <h4>Approved</h4>
            <span class="stat-number">{{ getLeavesByStatus('approved').length }}</span>
          </div>
          <div class="stat-card">
            <h4>Pending</h4>
            <span class="stat-number">{{ getLeavesByStatus('pending').length }}</span>
          </div>
          <div class="stat-card">
            <h4>Rejected</h4>
            <span class="stat-number">{{ getLeavesByStatus('rejected').length }}</span>
          </div>
        </div>
      </div>

      <div class="recent-leaves">
        <h3>Recent Leave Requests</h3>
        <div *ngIf="leaves.length === 0" class="no-data">
          No leave requests found.
        </div>
        <div *ngFor="let leave of leaves.slice(0, 5)" class="leave-item">
          <div class="leave-info">
            <span class="leave-dates">{{ leave.startDate }} - {{ leave.endDate }}</span>
            <span class="leave-type">{{ leave.type }}</span>
          </div>
          <span class="leave-status" [class]="'status-' + leave.status">
            {{ leave.status }}
          </span>
        </div>
      </div>
    </div>
  `
})
export class DashboardComponent implements OnInit {
  employee: Employee | null = null;
  leaves: Leave[] = [];

  constructor(
    private employeeService: EmployeeService,
    private leaveService: LeaveService
  ) {}

  ngOnInit(): void {
    this.loadEmployeeProfile();
    this.loadLeaves();
  }

  loadEmployeeProfile(): void {
    this.employeeService.getEmployeeDetails().subscribe({
      next: (employee) => this.employee = employee,
      error: (err) => console.error('Error loading employee profile:', err)
    });
  }

  loadLeaves(): void {
    this.leaveService.getLeaves().subscribe({
      next: (leaves) => this.leaves = leaves,
      error: (err) => console.error('Error loading leaves:', err)
    });
  }

  getLeavesByStatus(status: string): Leave[] {
    return this.leaves.filter(leave => leave.status === status);
  }
}
