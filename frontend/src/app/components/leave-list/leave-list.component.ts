import { Component, OnInit } from '@angular/core';
import { LeaveService } from '../../services/leave.service';
import { Leave } from '../../models/leave.model';

@Component({
  selector: 'app-leave-list',
  template: `
  <div class="leave-list">
    <h2>My Leave Requests</h2>
    <div class="filter">
      <label>
        Filter by status:
        <select [(ngModel)]="filterStatus" (change)="applyFilter()">
          <option value="">All</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </label>
    </div>
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Status</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>
        <tr *ngFor="let leave of filteredLeaves">
          <td>{{ leave.type }}</td>
          <td>{{ leave.startDate | date }}</td>
          <td>{{ leave.endDate | date }}</td>
          <td>{{ leave.status }}</td>
          <td>{{ leave.reason }}</td>
        </tr>
      </tbody>
    </table>
  </div>
  `,
  styles: [`
    .leave-list {
      padding: 1rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 0.5rem;
      text-align: left;
    }
    .filter {
      margin-bottom: 1rem;
    }
  `]
})
export class LeaveListComponent implements OnInit {
  leaves: Leave[] = [];
  filteredLeaves: Leave[] = [];
  filterStatus: string = '';

  constructor(private leaveService: LeaveService) {}

  ngOnInit(): void {
    this.leaveService.getLeaves().subscribe({
      next: leaves => {
        this.leaves = leaves;
        this.applyFilter();
      }
    });
  }

  applyFilter(): void {
    if (this.filterStatus) {
      this.filteredLeaves = this.leaves.filter(l => l.status === this.filterStatus);
    } else {
      this.filteredLeaves = [...this.leaves];
    }
  }
}
import { Component, OnInit } from '@angular/core';
import { LeaveService } from '../../services/leave.service';
import { AuthService } from '../../services/auth.service';
import { Leave } from '../../models/leave.model';

@Component({
  selector: 'app-leave-list',
  template: `
    <div class="leave-list-container">
      <div class="header">
        <h2>My Leave Requests</h2>
        <button routerLink="/leave-form" class="btn-primary">New Request</button>
      </div>
      
      <div class="filters">
        <select [(ngModel)]="statusFilter" (change)="applyFilter()" class="filter-select">
          <option value="">All Statuses</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>

      <div *ngIf="filteredLeaves.length === 0" class="no-data">
        No leave requests found.
      </div>

      <div class="leaves-table" *ngIf="filteredLeaves.length > 0">
        <div class="table-header">
          <div class="col">Start Date</div>
          <div class="col">End Date</div>
          <div class="col">Type</div>
          <div class="col">Reason</div>
          <div class="col">Status</div>
          <div class="col" *ngIf="isManager">Actions</div>
        </div>
        
        <div *ngFor="let leave of filteredLeaves" class="table-row">
          <div class="col">{{ leave.startDate | date:'short' }}</div>
          <div class="col">{{ leave.endDate | date:'short' }}</div>
          <div class="col">{{ leave.type }}</div>
          <div class="col">{{ leave.reason }}</div>
          <div class="col">
            <span class="status-badge" [class]="'status-' + leave.status">
              {{ leave.status }}
            </span>
          </div>
          <div class="col" *ngIf="isManager && leave.status === 'pending'">
            <button (click)="updateStatus(leave.id!, 'approved')" class="btn-approve">Approve</button>
            <button (click)="updateStatus(leave.id!, 'rejected')" class="btn-reject">Reject</button>
          </div>
        </div>
      </div>
    </div>
  `
})
export class LeaveListComponent implements OnInit {
  leaves: Leave[] = [];
  filteredLeaves: Leave[] = [];
  statusFilter = '';
  isManager = false;

  constructor(
    private leaveService: LeaveService,
    private authService: AuthService
  ) {}

  ngOnInit(): void {
    this.checkManagerRole();
    this.loadLeaves();
  }

  checkManagerRole(): void {
    const user = this.authService.getUser();
    this.isManager = user?.roles?.includes('manager') || false;
  }

  loadLeaves(): void {
    this.leaveService.getLeaves().subscribe({
      next: (leaves) => {
        this.leaves = leaves;
        this.applyFilter();
      },
      error: (err) => console.error('Error loading leaves:', err)
    });
  }

  applyFilter(): void {
    this.filteredLeaves = this.statusFilter 
      ? this.leaves.filter(leave => leave.status === this.statusFilter)
      : this.leaves;
  }

  updateStatus(id: number, status: string): void {
    this.leaveService.updateLeaveStatus(id, status).subscribe({
      next: () => {
        this.loadLeaves(); // Reload to get updated data
      },
      error: (err) => console.error('Error updating leave status:', err)
    });
  }
}
