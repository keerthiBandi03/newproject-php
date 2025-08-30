import { Component, OnInit } from '@angular/core';
import { LeaveService } from '../../services/leave.service';
import { AuthService } from '../../services/auth.service';
import { Leave } from '../../models/leave.model';

@Component({
  selector: 'app-leave-list',
  templateUrl: './leave-list.component.html',
  styleUrls: ['./leave-list.component.css']
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