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
