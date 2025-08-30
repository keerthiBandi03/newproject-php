
export const API_ENDPOINTS = {
  AUTH: {
    LOGIN: '/api/auth/login',
    LOGOUT: '/api/auth/logout',
    REFRESH: '/api/auth/refresh'
  },
  EMPLOYEES: {
    PROFILE: '/api/employee/me',
    LIST: '/api/employees',
    CREATE: '/api/employees',
    UPDATE: '/api/employees',
    DELETE: '/api/employees'
  },
  LEAVES: {
    LIST: '/api/leaves',
    CREATE: '/api/leaves',
    UPDATE: '/api/leaves',
    DELETE: '/api/leaves',
    APPROVE: '/api/leaves/approve',
    REJECT: '/api/leaves/reject'
  }
};

export const LEAVE_TYPES = [
  { value: 'annual', label: 'Annual Leave' },
  { value: 'sick', label: 'Sick Leave' },
  { value: 'personal', label: 'Personal Leave' },
  { value: 'emergency', label: 'Emergency Leave' },
  { value: 'maternity', label: 'Maternity Leave' },
  { value: 'paternity', label: 'Paternity Leave' }
];

export const LEAVE_STATUS = {
  PENDING: 'pending',
  APPROVED: 'approved',
  REJECTED: 'rejected'
};

export const USER_ROLES = {
  ADMIN: 'admin',
  MANAGER: 'manager',
  EMPLOYEE: 'employee'
};

export const STORAGE_KEYS = {
  TOKEN: 'auth_token',
  USER: 'current_user'
};
