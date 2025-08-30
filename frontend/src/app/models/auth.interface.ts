
export interface LoginCredentials {
  username: string;
  password: string;
}

export interface LoginResponse {
  token: string;
  user: {
    id: number;
    username: string;
    email: string;
    roles: string[];
  };
}

export interface AuthUser {
  id: number;
  username: string;
  email: string;
  roles: string[];
}
