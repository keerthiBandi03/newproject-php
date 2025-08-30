export interface Leave {
  id: number;
  employeeId: number;
  startDate: string; // ISO string
  endDate: string;   // ISO string
  type: string;
  status: 'pending' | 'approved' | 'rejected';
  reason: string;
}
