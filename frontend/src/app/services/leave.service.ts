import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Leave } from '../models/leave.model';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class LeaveService {
  constructor(private http: HttpClient) {}

  getLeaves(): Observable<Leave[]> {
    return this.http.get<Leave[]>(`${environment.backendApiUrl}/leaves`);
  }

  submitLeave(leave: Leave): Observable<Leave> {
    return this.http.post<Leave>(`${environment.backendApiUrl}/leaves`, leave);
  }

  updateLeaveStatus(id: number, status: string): Observable<any> {
    return this.http.put(`${environment.backendApiUrl}/leaves/${id}/status`, { status });
  }
}
