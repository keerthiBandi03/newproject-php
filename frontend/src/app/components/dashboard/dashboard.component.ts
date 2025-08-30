import { Component, OnInit } from '@angular/core';
import { EmployeeService } from '../../services/employee.service';
import { LeaveService } from '../../services/leave.service';
import { Employee } from '../../models/employee.model';
import { Leave } from '../../models/leave.model';

@Component({
  selector: 'app-dashboard',
  template: `
  <div class="dashboard">
    <h2>Welcome, {{ employee?.firstName }} {{ employee?.lastName }}</h2>
    <div class="leave-summary">
      <h3>Leave Summary</h3>
      <ul>
        <li>Pending: {{ leaveCounts.pending }}</li>
        <li>Approved: {{ leaveCounts.approved }}</li>
        <li>Rejected: {{ leaveCounts.rejected }}</li>
      </ul>
    </div>
  </div>
  `,
  styles: [`
    .dashboard {
      padding: 1rem;
    }
    .leave-summary ul {
      list-style: none;
      padding-left: 0;
    }
    .leave-summary li {
      margin-bottom: 0.5rem;
    }
  `]
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
