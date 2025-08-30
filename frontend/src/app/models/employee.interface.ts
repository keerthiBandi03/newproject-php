
export interface Employee {
  id: number;
  firstName: string;
  lastName: string;
  position: string;
  username: string;
  status: string;
  gender: string;
  company: string;
  department: string;
  employeeId: string;
  availableLeave: number;
}

export interface EmployeeCreateRequest {
  firstName: string;
  lastName: string;
  position: string;
  username: string;
  password: string;
  gender: string;
  company: string;
  department: string;
  employeeId: string;
}

export interface EmployeeUpdateRequest {
  firstName?: string;
  lastName?: string;
  position?: string;
  gender?: string;
  company?: string;
  department?: string;
  availableLeave?: number;
}
