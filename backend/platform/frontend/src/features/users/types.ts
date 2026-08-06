export interface User {
  uuid: string
  name: string
  email: string
  phone: string | null
  job_title: string | null
  user_type: string
  status: string
}