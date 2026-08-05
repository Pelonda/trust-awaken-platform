export interface Verification {
  valid: boolean
  credential_number: string
  credential_type: string
  status: string
  issued_at: string
  expires_at: string | null
}