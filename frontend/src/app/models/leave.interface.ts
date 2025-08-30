
export interface Leave {
  id: number;
  employeeId: number;
  employeeName: string;
  type: string;
  startDate: string;
  endDate: string;
  days: number;
  reason: string;
  status: 'pending' | 'approved' | 'rejected';
  appliedDate: string;
  approvedBy?: number;
  approvedDate?: string;
}

export interface LeaveCreateRequest {
  type: string;
  startDate: string;
  endDate: string;
  reason: string;
}

export interface LeaveUpdateRequest {
  status: 'pending' | 'approved' | 'rejected';
  approvedBy?: number;
}

export interface LeaveStatistics {
  total: number;
  pending: number;
  approved: number;
  rejected: number;
}
